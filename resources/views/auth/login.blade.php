﻿<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Sistema de Gestión de Bienes Municipales</title>
    <!-- Favicon-->
    <link rel="icon" href="{{ asset('favicon.ico') }}favicon.ico" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="{{ asset("css/material-icons.css") }}" rel="stylesheet" type="text/css">

    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link href="{{ asset("plugins/bootstrap/css/bootstrap.css") }}" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="{{ asset("plugins/node-waves/waves.css") }}" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="{{ asset("plugins/animate-css/animate.css") }}" rel="stylesheet" />

    <!-- Custom Css -->
    <link href="{{ asset("css/style.css") }}" rel="stylesheet">
</head>

<body class="login-page">
    <div class="login-box">
        <div class="logo">
            <a href="javascript:void(0);">Sistema de gestion de <b>Bienes Municipales</b></a>
            <small>Alcaldia de Maturín</small>
        </div>
        <div class="card">
            <div class="body">
                <form id="login" method="POST" action="{{ route("loguear") }}">
                    {{ csrf_field() }}
                    <div class="msg">Introduce tu Usuario y Contraseña</div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">person</i>
                        </span>
                        <div class="form-line">
                            <input type="text" class="form-control" name="username" placeholder="Usuario"  autofocus>
                        </div>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>
                        <div class="form-line">
                            <input type="password" class="form-control" name="password" placeholder="Contraseña" >
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-4">
                            <button class="btn btn-block bg-pink waves-effect" type="submit">ENTRAR</button>
                        </div>
                    </div>
                    <div class="row m-t-15 m-b--20">
                        <div class="col-xs-12 align-right">
                            <a href="">Olvidaste tu Contraseña?</a>
                        </div>
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

    <!-- Jquery Core Js -->
    <script src="{{ asset("plugins/jquery/jquery.min.js") }}"></script>

    <!-- Bootstrap Core Js -->
    <script src="{{ asset("plugins/bootstrap/js/bootstrap.js") }}"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="{{ asset("plugins/node-waves/waves.js") }}"></script>

    <!-- Validation Plugin Js -->
    <script src="{{ asset("plugins/jquery-validation/jquery.validate.js") }}"></script>

    <!-- Custom Js -->
    <script src="{{ asset("js/admin.js") }}"></script>
    <script src="{{ asset("js/pages/examples/login.js") }}"></script>
</body>

</html>