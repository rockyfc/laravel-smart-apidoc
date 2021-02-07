<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('vendor/doc/css/bootstrap.min.css') }}">
    <script src="https://cdn.staticfile.org/jquery/2.1.1/jquery.min.js"></script>
    <script src="{{ asset('vendor/doc/js/bootstrap.min.js') }}"></script>

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/10.3.2/styles/default.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/10.3.2/highlight.min.js"></script>

    <style>
        .label-default {
            background: #cfefdf;
            color: #00a854;
            font-weight: normal;
        }

        .table > thead > tr > td, .table > thead > tr > th {
            border: 0;
            background: #eee;
        }

        .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th {
            padding: 8px;
            line-height: 1.42857143;
            vertical-align: middle;
            border-top: 1px solid #eee;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <nav class="navbar navbar-default navbar-fixed-top navbar-inverse">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand " href="#">
                        <img alt="Brand" height="25" src="{{asset('images/logo.png')}}">
                    </a>

                </div>


                <p class="navbar-text navbar-right" style="padding-right: 10px;">
                    <a href="{{route('gii.index')}}" class="navbar-link">GII </a>
                    <a href="#" class="navbar-link">欢迎使用 </a>
                </p>
            </div>
        </nav>
    </div>
</div>

<div class="container">
    <div class="row" style="margin-top: 60px;"></div>

    <div class="row">

        <div class="col-md-3">
            @include('doc::route._left')
        </div>

        <div class="col-md-9">
            @yield('content')
        </div>


    </div>


</div>

</body>
</html>
