@extends('plantilla')

@section('contenido')
    <div class="col-lg-6 col-md-6">
        <div class="card">
            <div class="header">
                <h2><b>Orden de Compra #{{ $orden->numero }}</b></h2>
            </div>
            <div class="body">
                <form action="{{ route('orden.store') }}" method="post">
                    {{ csrf_field() }}
                    <div class="row clearfix">
                        <div class="col-md-6 col-lg-6">
                            <div class="form-group">
                                <label for="fecha">Fecha:</label>
                                <div>
                                    {{ date('d-m-Y',strtotime($orden->fecha)) }}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="orden">Proveedor:</label>
                                <div>
                                    {{ $orden->proveedor->nombre }}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="f_factura">Fecha de Factura:</label>
                                <div>
                                    {{ date('d-m-Y',strtotime($orden->f_factura)) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6">
                            <div class="form-group">
                                <label for="nro_factura">Numero de Factura:</label>
                                <div>
                                    {{ $orden->nro_factura }}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="nro_control">Numero de Control:</label>
                                <div>
                                    {{ $orden->nro_control }}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="total">Total:</label>
                                <div>
                                    {{ $orden->total }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <a href="{{ route('orden.index') }}" class="btn btn-block btn-primary">Volver</a>
                    </div>
                </form>
            </div>
        </div>
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
    </div>
@endsection

@section('js')
<script>
    $(function(){
        $('.int').inputmask('numeric', { placeholder: '' });
        $('#rifModal').inputmask('J-99999999-9', { placeholder: "J-00000000-0"});
        $('.date').inputmask('dd-mm-yyyy', { placeholder: '__-__-____' });
        $('.decimal').inputmask('decimal', { radixPoint: ",", groupSeparator: ".", autoGroup: true, placeholder: "0.00", numericInput: true});
    })

    $.ajax({
        method: 'POST',
        url: "proveedorJson",
        data : {_token : "{{ csrf_token() }}" },
        dataType: 'JSON',
    }).done(function (x) {
        $.each( x, function(i,v){
            var option = '<option value="'+v.id+'">'+v.nombre+'</option>';
            $("#proveedores").append(option);
        })
        $("#proveedores").selectpicker('refresh');
    }).fail(function () {
        alert("NO SE HAN PODIDO CARGAR LOS PROVEEDORES")
    })


    /*function attrLote(){
        $("#inc_por").selectpicker('val','unidad');
        $("#cantidad_div").addClass('hidden');
        $("#cantidad").val(0);
        $("#inc_div").removeClass('col-md-offset-4').addClass('col-md-offset-7');
        $("#separador").addClass("hidden");
        $("#cod-lote").addClass("hidden");
    }

    //Cargar el select con los elementos
    fillSelect("elementos",'#elementos');

    //Cargar el select con los departamentos
    fillSelect("departamentos","#departamento");

    //Cargar el select con los tipo_movimientos
    fillSelect("tipo_movimientos","#t_movimiento")

    //Cambia estado de div inc_por
    $("#inc_por").change(function () {
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

    $("#elementos").change(function () {

        attrLote();
        cantidad = 0;

        if($("#cantidad").val()){
            var cantidad = $("#cantidad").val()
        }

        $.ajax({
            method: 'get',
            url: 'bien/' + $(this).val() + "/cantidad/" + cantidad,
            data : {_token :$('#token').val()},
            dataType: 'JSON',
        }).done(function (e) {
            $("#codigo").html(e.bien);
            bien = e.bien;
            $("#cod-lote").html(e.lote);
        }).fail(function () {
            alert("NO SE HAN PODIDO CARGAR LOS ELEMENTOS")
        })
    })

    $('#cantidad').change(function () {
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
    })

    $('.js-modal-buttons .btn').on('click', function () {
        var color = $(this).data('color');
        $('#mdModal .modal-content').removeAttr('class').addClass('modal-content modal-col-' + color);
        $('#mdModal').modal('show');
    });*/
</script>
@endsection