<?php

namespace App\Http\Controllers;

use App\Direccion;
use App\Movimiento;
use App\Orden;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Bien;
use App\Elemento;
use App\Departamento;
use App\TipoMovimiento;
use Auth;
use Validator;
use Illuminate\Support\Facades\Storage;

class BienesController extends Controller
{
    public function store(Request $request)
    {
//        dd($request->all());
        /*$this->validate($request,[
             'codigo' => 'required|unique:bienes,codigo',
             'descripcion' => 'required',
             'fecha_incorp' => 'required',
             'valor' => 'required',
             'valor_actual' => 'required',
             't_movimiento' => 'required',
             'nro_orden' => 'required_unless:t_movimiento,11',
             'direccion' => 'required',
             'elemento' => 'required',
         ]);*/

        $validator = Validator::make($request->all(),[
            'codigo' => 'required|unique:bienes,codigo',
            'descripcion' => 'required',
            'fecha_incorp' => 'required',
            'valor' => 'required',
            'valor_actual' => 'required',
            't_movimiento' => 'required',
            //'nro_orden' => 'required_unless:t_movimiento,11',
            'direccion' => 'required',
            'elemento' => 'required',
        ]);

        if ($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $valor = str_replace('.',"",$request->valor);
        $valor = str_replace(',',".",$valor);

        $valor_actual = str_replace('.',"",$request->valor_actual);
        $valor_actual = str_replace(',',".",$valor_actual);

        if($request->cantidad) {

            $codigo = explode('-',$request->codigo);

            $cont = 0;

            for ($i = 0; $i < $request->cantidad; $i++) {

                $codigo[4] += $cont;

                if (strlen($codigo[4]) == 1){
                    $codigo[4] = "000".$codigo[4];
                }elseif (strlen($codigo[4]) == 2){
                    $codigo[4] = "00".$codigo[4];
                }elseif (strlen($codigo[4]) == 3){
                    $codigo[4] = "0".$codigo[4];
                }

                $_codigo = join('-',$codigo);

                $bien = Bien::create([
                    'codigo' => $_codigo,
                    'descripcion' => ucfirst($request->descripcion),
                    'fecha_incorp' => date('Y-m-d',strtotime(trim($request->fecha_incorp))),
                    'valor' => $valor,
                    'valor_actual' => $valor_actual,
                    'nro_orden' => $request->nro_orden,
                    'elemento' => $request->elemento,
                    'direccion' => $request->direccion,
                    'departamento' => $request->departamento,
                    'usuario' => Auth::id(),
                ]);

                Movimiento::create([
                    "bien" => $bien->id,
                    "t_movimiento" => $request->t_movimiento,
                    "fecha" => date('Y-m-d',strtotime($request->fecha_incorp)),
                    "direccion" => $request->direccion,
                    "departamento" => $request->departamento,
                    "idU" => $bien->id.'-'. date('Y-m-d',strtotime($request->fecha)).'-'.$request->t_movimiento,
                    "observacion" => $request->observacion,
                    "tipo" => 1,
                    "usuario" => Auth::id()
                ]);

                $cont = 1;
            }
        }else{
            $bien = Bien::create([
                'codigo' => $request->codigo,
                'descripcion' => ucfirst($request->descripcion),
                'fecha_incorp' => date('Y-m-d',strtotime(trim($request->fecha_incorp))),
                'valor' => $valor,
                'valor_actual' => $valor_actual,
                'nro_orden' => $request->nro_orden,
                'elemento' => $request->elemento,
                'direccion' => $request->direccion,
                'departamento' => $request->departamento,
                'usuario' => Auth::id(),
            ]);

            Movimiento::create([
                "bien" => $bien->id,
                "t_movimiento" => $request->t_movimiento,
                "fecha" => date('Y-m-d',strtotime($request->fecha_incorp)),
                "direccion" => $request->direccion,
                "departamento" => $request->departamento,
                "idU" => $bien->id.'-'. date('Y-m-d',strtotime($request->fecha)).'-'.$request->t_movimiento,
                "observacion" => $request->observacion,
                "tipo" => 1,
                "usuario" => Auth::id()
            ]);
        }

        if($request->hasFile('foto')){
            $bien = Bien::find($bien->id);
            $foto = $request->file('foto');
            $bien->foto = $request->codigo.'.'.$request->file('foto')->extension();
            $foto->move(public_path().'/fotos/',$request->codigo.'.'.$request->file('foto')->extension());
//            Storage::put('public/bienes/'.$request->codigo.'.'.$request->file('foto')->extension(), $request->file('foto'));
            $bien->save();
        }

        flash('El bien ha sido registrado exitosamente')->success();
        return response()->redirectToRoute('bienes.index');
    }

    public function index()
    {
        $bienes = Bien::with('orden')
            ->orderBy('fecha_incorp','desc')
            ->where('estatus','!=','desincorporado')
            ->get();

        return view('gestion-bienes.index')->with(compact('bienes'));
    }

    public function show($id)
    {
        $bien = Bien::where('id',$id)->with('orden','_direccion','_departamento')->first();
        $movimientos = Movimiento::with('_tipo','_direccion','_departamento','_usuario')
            ->where('bien',$bien->id)
            ->get();

        //dd($movimientos->toarray());
        return view('gestion-bienes.ver')->with(compact('bien','movimientos'));
    }

    public function edit($id)
    {
        $bien = Bien::find($id);
        $elementos = Elemento::all();
        $direcciones = Direccion::all();
        $departamentos = Departamento::where('direccion',$bien->direccion)->get();
        $tipos = TipoMovimiento::all();
        $ordenes = Orden::where('anno',Carbon::now()->year)->get();

        return view('gestion-bienes.editar')->with(compact(['bien','elementos','direcciones','departamentos','tipos','ordenes']));
    }

    public function update(Request $request,$id)
    {
        $bien = Bien::find($id);

        $this->validate($request,[
            'descripcion' => 'required',
            'fecha_incorp' => 'required',
            'valor' => 'required',
            'valor_actual' => 'required',
            //'nro_orden' => 'required',
            'direccion' => 'required',
            //'departamento' => 'required',
        ]);

        $valor = str_replace('.',"",$request->valor);
        $valor = str_replace(',',".",$valor);

        $valor_actual = str_replace('.',"",$request->valor_actual);
        $valor_actual = str_replace(',',".",$valor_actual);

        $bien->descripcion = $request->descripcion;
        $bien->fecha_incorp = date('Y-m-d',strtotime($request->fecha_incorp));
        $bien->valor = $valor;
        $bien->valor_actual = $valor_actual;
        $bien->nro_orden = $request->nro_orden;
        $bien->direccion = $request->direccion;
        $bien->departamento = ($request->departamento || isset($request->departamento) ? $request->departamento : null);
        $bien->save();

        if($request->hasFile('foto')){
            $bien = Bien::find($id);
            $foto = $request->file('foto');
            $bien->foto = $bien->codigo.'.'.$request->file('foto')->extension();
//            dd($bien->codigo.'.'.$request->file('foto')->extension());
            $foto->move(public_path().'/fotos/',$bien->codigo.'.'.$request->file('foto')->extension());
            $bien->save();
        }

        flash('El bien ha sido modificado exitosamente')->success();
        return response()->redirectToRoute('bienes.show',$bien->id);
    }

    public function getLastAjax($bienid,$cantidadid)
    {
        $bien = Bien::where('elemento',$bienid)->orderBy('codigo',"desc")->first();

        if (! $bien){

            $elemento = Elemento::find($bienid);

            $bien = $elemento->codigo."-0001";

            if (strlen($cantidadid) == 1){
                $lote = $elemento->codigo."-000".$cantidadid;
            }elseif (strlen($cantidadid) == 2){
                $lote = $elemento->codigo."-00".$cantidadid;
            }elseif (strlen($cantidadid) == 3){
                $lote = $elemento->codigo."-0".$cantidadid;
            }else{
                $lote = $elemento->codigo."-".$cantidadid;
            }

            return response()->json(["bien" => $bien, "lote" => $lote],200);
        }

        $bien = explode('-',$bien->codigo);

        //dd($bien);

        $bien[4] += 1;

        if (strlen($bien[4]) == 1){
            $bien[4] = '000'.$bien[4];
        }elseif (strlen($bien[4]) == 2){
            $bien[4] = '00'.$bien[4];
        }elseif (strlen($bien[4]) == 3){
            $bien[4] = '0'.$bien[4];
        }

        $lote = "";

        if ($cantidadid){

            $_lote = $bien[4] + $cantidadid - 1;

            if (strlen($_lote) == 1){
                $lote = '000'.$_lote;
            }elseif (strlen($_lote) == 2){
                $lote = '00'.$_lote;
            }elseif (strlen($_lote) == 3){
                $lote = '0'.$_lote;
            }

            $lote = $bien[0].'-'.$bien[1].'-'.$bien[2].'-'.$bien[3].'-'.$lote;
        }

        $bien = join('-',$bien);

        return response()->json(["bien" => $bien,'lote' => $lote],200);
    }

    public function create()
    {
        $elementos = Elemento::all();
        $direcciones = Direccion::all();
        $departamentos = Departamento::all();
        $tipos = TipoMovimiento::where('tipo',1)->get();
        $ordenes = Orden::where('anno',Carbon::now()->year)->get();

        return view('gestion-bienes.incorporacion')->with(compact(['elementos','direcciones','departamentos','tipos','ordenes']));
    }

    public function desincorporacion($id)
    {
        $bien = Bien::find($id);
        $direcciones = Direccion::all();
        $departamentos = Departamento::all();
        $tipos = TipoMovimiento::where('tipo',0)->get();

        return view('gestion-bienes.desincorporar')->with(compact(['bien','direcciones','departamentos','tipos']));
    }

    public function movimiento($id)
    {
        $bien = Bien::find($id);
        $direcciones = Direccion::all();
        $departamentos = Departamento::all();
        $tipos = TipoMovimiento::where('tipo',1)->get();

        return view('gestion-bienes.mover')->with(compact(['bien','direcciones','departamentos','tipos']));
    }

    public function desincorporado(Request $request,$id)
    {
        $this->validate($request,[
            "movimiento" => "required",
            "fecha" => "required",
            "direccion" => "required_if:movimiento,18",
            "departamento" => "required_if:movimiento,18",
        ],[
            "movimiento.required" => "Debe selccionar un tipo de movimiento",
            "fecha.required" => "Debe introducir un fecha",
            "direccion.required_if" => "Debe seleccionar una dirección",
            "departamento.required_if" => "Debe seleccionar un departamento",
        ]);

        $tmovimiento = TipoMovimiento::find($request->movimiento);

        $bien = Bien::find($id);

        if ($tmovimiento->codigo !== "51"){
            $bien->estatus = 'desincorporado';
        }

        Movimiento::create([
            "bien" => $id,
            "t_movimiento" => $request->movimiento,
            "fecha" => date('Y-m-d',strtotime($request->fecha)),
            "direccion" => $bien->direccion,
            "departamento" => $bien->departamento,
            "idU" => $id.'-'. date('Y-m-d',strtotime($request->fecha)).'-'.$request->movimiento,
            "observacion" => $request->observacion,
            "tipo" => 0,
            "usuario" => Auth::id()
        ]);

        if ($tmovimiento->codigo === "51"){

            $bien->direccion = $request->direccion;
            $bien->departamento = $request->departamento;
            $inc_traspaso = TipoMovimiento::where('codigo',2)->first();

            Movimiento::create([
                "bien" => $id,
                "t_movimiento" => $inc_traspaso->id,
                "fecha" => date('Y-m-d',strtotime($request->fecha)),
                "direccion" => $request->direccion,
                "departamento" => $request->departamento,
                "idU" => $id.'-'. date('Y-m-d',strtotime($request->fecha)).'-'.$inc_traspaso->id,
                "observacion" => $request->observacion,
                "tipo" => 1,
                "usuario" => Auth::id()
            ]);
        }

        $bien->save();
        flash('El bien ha sido desincorporado','success');
        return response()->redirectToRoute('bienes.show',$bien->id);
    }

    public function mover(Request $request,$id)
    {
        $this->validate($request,[
            "movimiento" => "required",
            "fecha" => "required",
            "direccion" => "required_if:movimiento,18",
            "departamento" => "required_if:movimiento,18",
        ],[
            "movimiento.required" => "Debe selccionar un tipo de movimiento",
            "fecha.required" => "Debe introducir un fecha",
            "direccion.required_if" => "Debe seleccionar una dirección",
            "departamento.required_if" => "Debe seleccionar un departamento",
        ]);

        $bien = Bien::find($id);

        if(!$bien){
            flash('El bien que intenta mover no existe','warning');
        }

        Movimiento::create([
            "bien" => $id,
            "t_movimiento" => $request->movimiento,
            "fecha" => date('Y-m-d',strtotime($request->fecha)),
            "direccion" => $bien->direccion,
            "departamento" => $bien->departamento,
            "idU" => $id.'-'. date('Y-m-d',strtotime($request->fecha)).'-'.$request->movimiento,
            "observacion" => $request->observacion,
            "tipo" => 1,
            "usuario" => Auth::id()
        ]);
        $bien->direccion = $request->direccion;
        $bien->departamento = $request->departamento;
        $bien->save();
        flash('El bien ha sido movido','success');
        return response()->redirectToRoute('bienes.show',$bien->id);
    }

    public function reportes()
    {
        return view('gestion-bienes.reportes');
    }

    public function bienFaltante(Request $request,$id)
    {
        $bien = Bien::find($id);
        $bien->fecha_faltante = Carbon::now();
        $bien->estatus = 'faltante';

        $movimiento = TipoMovimiento::where('codigo','60')->first();

        Movimiento::create([
            "bien" => $bien->id,
            "t_movimiento" => $movimiento->id,
            "fecha" => Carbon::now(),
            "direccion" => $bien->direccion,
            "departamento" => $bien->departamento,
            "idU" => $bien->id.'-'. Carbon::now().'-'.$movimiento,
            "observacion" => $request->observacion,
            "tipo" => 2,
            "usuario" => Auth::id()
        ]);

        $bien->save();

        return redirect()->route('bienes.show',$bien->id);
    }
}
