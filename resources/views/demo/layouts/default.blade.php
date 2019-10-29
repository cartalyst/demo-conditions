<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Conditions Demo</title>

    <link href="{{ url('/assets/demo/css/bootstrap.min.css') }}" rel="stylesheet" media="screen">
    <link href="{{ url('/assets/demo/css/font-awesome.min.css') }}" rel="stylesheet" media="screen">
    <link href="{{ url('/assets/demo/css/codemirror.css') }}" rel="stylesheet" media="screen">
    <link href="{{ url('/assets/demo/css/app.css') }}" rel="stylesheet" media="screen">
</head>
<body>
    <div class="flux clearfix">
        <div class="flux--1"></div>
        <div class="flux--2"></div>
        <div class="flux--3"></div>
        <div class="flux--4"></div>
        <div class="flux--5"></div>
    </div>

    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="{{ route('demo.home') }}">Conditions</a>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item{!! request()->is('demo') ? ' active"' : null !!}"><a class="nav-link" href="{{ route('demo.home') }}">Home</a></li>
                    <li class="nav-item{!! request()->is('demo/regular') ? ' active"' : null !!}"><a class="nav-link" href="{{  route('demo.regular') }}">Regular</a></li>
                    <li class="nav-item{!! request()->is('demo/percentage') ? ' active"' : null !!}"><a class="nav-link" href="{{ route('demo.percentage')}}">Percentage</a></li>
                    <li class="nav-item{!! request()->is('demo/inclusive') ? ' active"' : null !!}"><a class="nav-link" href="{{ route('demo.inclusive') }}">Inclusive</a></li>
                </ul>

                <ul class="navbar-nav navbar-right">
                    <li class="nav-item"><a class="nav-link" href="https://cartalyst.com/manual/conditions" target="_blank">Manual</a></li>
                </ul>
            </div>
        </nav>

        @yield('content')
    </div>

    <script src="{{ url('/assets/demo/js/jquery.min.js') }}"></script>
    <script src="{{ url('/assets/demo/js/bootstrap.min.js') }}"></script>
    <script src="{{ url('/assets/demo/js/codemirror.js') }}"></script>
    <script src="{{ url('/assets/demo/js/codemirror.javascript.js') }}"></script>
    <script src="{{ url('/assets/demo/js/waypoints.js') }}"></script>
    <script src="{{ url('/assets/demo/js/counterUp.js') }}"></script>

    <script type="text/javascript">
        var self = {};

        self.myCodeMirror = CodeMirror.fromTextArea(document.getElementById('textarea'), {
            lineNumbers: true,
            matchBrackets: true
        });

        self.myCodeMirror1 = CodeMirror.fromTextArea(document.getElementById('textarea1'), {
            lineNumbers: true,
            matchBrackets: true
        });

        $('button').on('click', function(e) {
            e.preventDefault();

            mirror = $(this).data('mirror');

            var data = $.parseJSON(self[mirror].getValue());

            console.log($.isArray(data));

            if ($.isArray(data)) {
                var oldData = data;
                data = {};

                $.each(oldData, function(i) {
                    data[i] = oldData[i];
                });
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: 'process',
                data: data,
                type: 'post',
            }).done(function(res) {
                $('h1 .tax').text(res.tax);
                $('h1 .price').text(res.price);

                $('.price').counterUp({
                    delay: 5,
                    time: 500
                });

                $('.tax').counterUp({
                    delay: 5,
                    time: 500
                });
            });
        });
    </script>

    @yield('scripts')

    <!-- Google Analytics -->
    <script>
        window.ga=window.ga||function(){(ga.q=ga.q||[]).push(arguments)};ga.l=+new Date;
        ga('create', 'UA-26550564-1', 'auto');
        ga('send', 'pageview');
    </script>
    <script async src='https://www.google-analytics.com/analytics.js'></script>
    <!-- End Google Analytics -->
</body>

</html>
