@extends('plantilla')

@section('contenido')
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="header">
                <h2><b>Tipos de Movimiento</b></h2>
            </div>
            <div class="body">
                <div class="align-right">
                    <a href="{{ route('tipo-movimiento.create') }}" class="btn btn-primary">Nuevo Tipo de Movimiento</a>
                </div>
                <br />
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover dataTable">
                        <thead>
                            <tr>
                                <th>Codigo</th>
                                <th>Descripcion</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($tipos as $tipo)
                            <tr>
                                <td>{{ $tipo->codigo }}</td>
                                <td>{{ $tipo->descripcion }}</td>
                                <td>
                                    <form method="post" action="{{ route('tipo-movimiento.destroy',$tipo->id) }}">
                                        <a href="{{ route('tipo-movimiento.edit',$tipo->id) }}" class="btn btn-success">Editar</a>
                                        {{ method_field('delete') }}
                                        {{ csrf_field()  }}
                                        <button class="btn btn-danger" onclick="if (! window.confirm('¿Desea elminar este Tipo de Movimiento?')){ return false }">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
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
    $(".table").dataTable();
</script>
@endsection