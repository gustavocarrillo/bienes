<div class="menu">
    <ul class="list">
        <li class="header">NAVEGACION PRINCIPAL</li>
        <li class="active">
            <a href="{{ route("pl") }}">
                <i class="material-icons">home</i>
                <span>Inicio</span>
            </a>
        </li>
        <li>
            <a href="javascript:void(0);" class="menu-toggle">
                <i class="material-icons">content_copy</i>
                <span>Gestion de Bienes</span>
            </a>
            <ul class="ml-menu">
            <li>
                <a href="{{ route('incorporacion') }}">Incorporación</a>
            </li>
            <li>
                <a href="../../pages/widgets/infobox/infobox-1.html">Busquedas</a>
            </li>
            </ul>
        </li>
        <li>
            <a href="javascript:void(0);" class="menu-toggle">
                <i class="material-icons">content_copy</i>
                <span>Proveedores</span>
            </a>
            <ul class="ml-menu">
                <li>
                    <a href="{{ route('proveedor.create') }}">Registrar Proveedor</a>
                </li>
                <li>
                    <a href="{{ route('proveedor.index') }}">Listar Proveedores</a>
                </li>
            </ul>
        </li>
        <li>
            <a href="javascript:void(0);" class="menu-toggle">
                <i class="material-icons">content_copy</i>
                <span>Ordenes de Compra</span>
            </a>
            <ul class="ml-menu">
                <li>
                    <a href="{{ route('orden.create') }}">Registrar Orden</a>
                </li>
                <li>
                    <a href="{{ route('orden.index') }}">Listar Ordenes</a>
                </li>
            </ul>
        </li>
        {{--<li class="header">LABELS</li>
        <li>
            <a href="javascript:void(0);">
                <i class="material-icons col-red">donut_large</i>
                <span>Important</span>
            </a>
        </li>
        <li>
            <a href="javascript:void(0);">
                <i class="material-icons col-amber">donut_large</i>
                <span>Warning</span>
            </a>
        </li>
        <li>
            <a href="javascript:void(0);">
                <i class="material-icons col-light-blue">donut_large</i>
                <span>Information</span>
            </a>
        </li>--}}
    </ul>
</div>
