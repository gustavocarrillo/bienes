@extends('plantilla')

@section('contenido')
    <main>
        <div class="col-lg-7 col-md-7">
            @include('flash::message')
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="card">
                <div class="header">
                    <h2><b>Incorporacion de Bienes</b></h2>
                </div>
                <div class="body">
                        <h2 class="card-inside-title">Seleccionar Elemento</h2>
                    <form action="{{ route('bienes.store') }}" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="codigo" id="codigo_input" v-model="codigo_input">
                        <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                        <div class="row clearfix">
                            <div class="col-md-12">
                                <div class="p-b-15">
                                    <select v-model="elemento" @change="bienesPorLote" name="elemento" id="elementos" class="form-control show-tick" data-live-search="true">
                                        <option value="" selected>Seleccione un elemento</option>
                                        @foreach($elementos as $elemento)
                                            <option value="{{ $elemento->id }}">{{ $elemento->codigo.' - '.$elemento->descripcion }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="row">
                                    <div v-show="elemento" :class="[inc_lote ? 'col-md-4 col-sm-4 col-md-offset-4' : 'col-md-4 col-sm-4 col-md-offset-7']" id="inc_div">
                                        <div class="form-group">
                                            <label for="inc_por">Incorporación por: </label>
                                            <select @change="inc_lote = !inc_lote" name="inc_por" id="inc_por" class="form-control show-tick">
                                                <option value="unidad">Unidad</option>
                                                <option value="lote">Lote</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div v-show="inc_lote" class="col-sm-3" id="cantidad_div">
                                        <div class="form-group">
                                            <label for="cantidad">Cantidad</label>
                                            <div class="form-line">
                                                <input type="number" min="2" max="9999" @keyup="lote" v-model="cantidad" name="cantidad" id="cantidad" class="form-control"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <h2 class="card-inside-title">Datos de Incorporación</h2>
                                <hr>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="inc_por">Descripcion: </label>
                                            <div class="form-line">
                                                <input type="text" name="descripcion" id="descripcion" class="form-control" placeholder="" value="" maxlength="65" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-offset-5 col-md-8  p-l-30 m-t-20">
                                        <div class="form-group">
                                            <label for="inc_por">Codigo: </label>
                                            <span id="codigo" v-text="codigo"></span>
                                            <template v-if="inc_lote">
                                                <span id="separador"> -- </span>
                                                <span id="cod-lote">@{{ code_lote }}</span>
                                            </template>

                                        </div>
                                    </div>
                                </div>
                                <div class="row ">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="orden">Orden de Compra:</label>
                                            <select name="nro_orden" id="orden" class="form-control show-tick" data-live-search="true">
                                                <option value="" selected>Seleccione..</option>
                                                @foreach($ordenes as $orden)
                                                    <option value="{{ $orden->id }}">{{ $orden->numero }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="p-t-30">
                                            <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#smallModal">Nueva</button>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 col-md-offset-2">
                                        <div class="form-group">
                                            <label for="orden">Valor del Bien:</label>
                                            <div class="form-line">
                                                <input type="text" name="valor" id="valor_bien" class="form-control decimal" maxlength="16"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row ">
                                    <div class="col-sm-4">
                                        <label>Fecha de Incorporación</label>
                                        <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="material-icons">date_range</i>
                                                </span>
                                            <div class="form-line">
                                                <input type="text" name="fecha_incorp" id="f_incorp" class="form-control date" placeholder="Ejem: 30/07/2016">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4  col-md-offset-2">
                                        <div class="form-group">
                                            <label for="valor_actual_bien">Valor actual del Bien:</label>
                                            <div class="form-line">
                                                <input type="text" name="valor_actual" id="valor_actual_bien" class="form-control decimal" maxlength="16"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <h2 class="card-inside-title">Destino</h2>
                                        <div class="form-group">
                                            <div class="form-group">
                                                <select name="departamento" id="departamento" class="form-control show-tick" data-live-search="true">
                                                    <option value="" selected>Seleccione un departamento</option>
                                                    @foreach($departamentos as $departamento)
                                                        <option value="{{ $departamento->id }}">{{ $departamento->codigo.' - '.$departamento->descripcion }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <h2 class="card-inside-title">Tipo de Movimiento</h2>
                                        <div class="form-group">
                                            <div class="form-group">
                                                <select name="t_movimiento" id="t_movimiento" class="form-control show-tick" data-live-search="true">
                                                    <option value="" selected>Seleccione un elemento</option>
                                                    @foreach($tipos as $tipo)
                                                        <option value="{{ $tipo->id }}">{{ $tipo->codigo.' - '.$tipo->descripcion }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 p-t-10 p-b-8">
                                        <button id="btnGuardar" class="btn btn-primary waves-effect">
                                            <i class="material-icons">save</i>
                                            <span>GUARDAR</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        {{--Modal--}}
        <div class="modal fade" id="smallModal" tabindex="-1" role="dialog">
        <form action="" id="modal">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="smallModalLabel">Nueva Orden de Compra</h4>
                </div>
                <div class="modal-body">
                   <div class="row">
                       <div class="col-md-5">
                           <div class="form-group">
                               <label for="">N° de Orden:</label>
                               <div class="form-line">
                                   <input type="text" id="nro_ordenModal" class="form-control int-">
                               </div>
                           </div>
                       </div>
                       <div class="col-md-7">
                           <div class="form-group">
                               <label for="">Fecha:</label>
                               <div class="input-group">
                                   <span class="input-group-addon">
                                       <i class="material-icons">date_range</i>
                                   </span>
                                   <div class="form-line">
                                       <input type="text" id="fechaModal"class="form-control date" placeholder="">
                                   </div>
                               </div>
                           </div>
                       </div>
                   </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Proveedor:</label>
                                <div class="form-line">
                                    <input type="text" id="proveedorModal" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Rif:</label>
                                <div class="form-line">
                                    <input type="text" id="rifModal" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="">Factura N°:</label>
                                <div class="form-line">
                                    <input type="text" id="nro_ordenModal" class="form-control int">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="form-group">
                                <label for="">Fecha:</label>
                                <div class="input-group">
                                   <span class="input-group-addon">
                                       <i class="material-icons">date_range</i>
                                   </span>
                                    <div class="form-line">
                                        <input type="text" id="fechaFacturaModal" class="form-control date" placeholder="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="">Control N°:</label>
                                <div class="form-line">
                                    <input type="text" id="nro_controlModal" class="form-control int">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="form-group">
                                <label for="">Total:</label>
                                <div class="input-group">
                                    <div class="form-line">
                                        <input type="text" id="totalModal"class="form-control decimal" placeholder="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="guardarModal" class="btn btn-primary waves-effect">
                        <i class="material-icons">save</i>
                        <span>GUARDAR</span>
                    </button>
                    <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">
                        <i class="material-icons">cancel</i>
                        <span>Cancelar</span>
                    </button>
                </div>
            </div>
        </div>
        </form>
    </div>
        <pre>@{{ $data }}</pre>

    </main>
    {{--Fin - Modal--}}

@endsection

@section('js')
<script>

    const vm = new Vue({
        el: "main",
        data: {
            elemento: "",
            codigo: "",
            cantidad: 2,
            code_lote: "",
            codigo_input: "",
            inc_lote: false,
            data: "",
        },
        methods: {
            bienesPorLote(){
                this.$http.get("bien/"+this.elemento+"/cantidad/2").then( function (resp) {
                    //this.data = resp.body;
                    this.codigo = this.codigo_input = resp.body.bien;
                    //this.code_lote = resp.body.lote;
                })
            },
            lote(){
                if( this.codigo != "00-0-000-0000" ) {
                    this.code_lote = this.codigo.split('-');
                    this.data = parseInt(this.code_lote[4]) + parseInt(this.cantidad);

                    if(this.data <= 999 && this.data > 100){
                        this.data = "0" + this.data.toString();
                        this.data = this.data.toString();
                    }else if(this.data <= 99 && this.data > 10){
                        this.data = "00" + this.data.toString();
                        this.data = this.data.toString();
                    }else if(this.data <= 9){
                        this.data = "000" + this.data.toString();
                        this.data = this.data.toString();
                    }

                    this.code_lote[4] = this.data;
                    this.code_lote = this.code_lote.join('-');
                }
            }
        }
    })

    $(function(){
        $('.int').inputmask('numeric', { placeholder: '0' });
        $('#rifModal').inputmask('J-99999999-9', { placeholder: "J-00000000-0"});
        $('.date').inputmask('dd/mm/yyyy', { placeholder: '__/__/____' });
        $('.decimal').inputmask('decimal', { radixPoint: ",", groupSeparator: ".", autoGroup: true, placeholder: "0.00", numericInput: true});
    })

    /*function fillSelect(url,selector){
        $.ajax({
            method: 'POST',
            url: url,
            data : {_token :$('#token').val()},
            dataType: 'JSON',
        }).done(function (x) {
            $.each( x, function(i,v){
                var option = '<option value="'+v.id+'">'+v.codigo+' - '+v.descripcion+'</option>';
                $(selector).append(option);
            })
            $(selector).selectpicker('refresh');
        }).fail(function () {
            alert("NO SE HAN PODIDO CARGAR LOS ELEMENTOS")
        })
    }*/

    function attrLote(){
        $("#inc_por").selectpicker('val','unidad');
        $("#cantidad_div").addClass('hidden');
        $("#cantidad").val(0);
        $("#inc_div").removeClass('col-md-offset-4').addClass('col-md-offset-7');
        $("#separador").addClass("hidden");
        $("#cod-lote").addClass("hidden");
    }

    //Cargar el select con los elementos
    //fillSelect("elementos",'#elementos');

    //Cargar el select con los departamentos
    //fillSelect("departamentos","#departamento");

    //Cargar el select con los tipo_movimientos
    //fillSelect("tipo_movimientos","#t_movimiento")

    //Cambia estado de div inc_por
    /*$("#inc_por").change(function () {
        if($(this).val() == "lote"){
            $("#cantidad_div").removeClass('hidden');
            $("#inc_div").removeClass('col-md-offset-7').addClass('col-md-offset-4');
            $("#cantidad").val(0);
            $("#cantidad").focus();
            $("#separador").removeClass("hidden");
            $("#cod-lote").removeClass("hidden");
        }else{
            attrLote()
        }
    })
    //fin - inc_por

    /*$("#elementos").change(function () {

        attrLote();
        cantidad = 0;

        if($("#cantidad").val()){
            var cantidad = $("#cantidad").val()
        }

        $.ajax({
            method: 'get',
            url: 'bien/' + $(this).val() + "/cantidad/" + cantidad,
            data : {_token: "{{ csrf_token() }}" },
            dataType: 'JSON',
        }).done(function (e) {
            //alert(e.bien)
            $("#codigo").html(e.bien);
            $("#codigo_input").val(e.bien);
            bien = e.bien;
            $("#cod-lote").html(e.lote);
            $("#codido_lote").val(e.lote);
        }).fail(function () {
            alert("NO SE HAN PODIDO CARGAR LOS ELEMENTOS")
        })
    })

    /*$('#cantidad').change(function () {
        $.ajax({
            method: 'get',
            url: 'bien/' + $("#elementos").val() + "/cantidad/" + $('#cantidad').val(),
            data : {_token :$('#token').val()},
            dataType: 'JSON',
        }).done(function (e) {
            $("#codigo").html(e.bien);
            $("#cod-lote").html(e.lote);
        }).fail(function () {
            alert("NO SE HAN PODIDO CARGAR LOS ELEMENTOS")
        })
    })*/

    $('.js-modal-buttons .btn').on('click', function () {
        var color = $(this).data('color');
        $('#mdModal .modal-content').removeAttr('class').addClass('modal-content modal-col-' + color);
        $('#mdModal').modal('show');
    });
</script>
@endsection