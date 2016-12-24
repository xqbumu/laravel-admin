<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ Docore::title() }}</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <link rel="stylesheet" href="{{ asset("/packages/docore/AdminLTE/bootstrap/css/bootstrap.min.css") }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset("/packages/docore/font-awesome/css/font-awesome.min.css") }}">

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset("/packages/docore/AdminLTE/dist/css/skins/_all-skins.min.css") }}">
    <link rel="stylesheet" href="{{ asset("/packages/docore/AdminLTE/dist/css/skins/" . Docore::configs('skin') .".min.css") }}">

    {!! Docore::css() !!}
    <link rel="stylesheet" href="{{ asset("/packages/docore/nestable/nestable.css") }}">
    <link rel="stylesheet" href="{{ asset("/packages/docore/bootstrap3-editable/css/bootstrap-editable.css") }}">
    <link rel="stylesheet" href="{{ asset("/packages/docore/google-fonts/fonts.css") }}">
    <link rel="stylesheet" href="{{ asset("/packages/docore/AdminLTE/dist/css/AdminLTE.min.css") }}">

    <!-- REQUIRED JS SCRIPTS -->
    <script src="{{ asset ("/packages/docore/AdminLTE/plugins/jQuery/jQuery-2.2.3.min.js") }}"></script>
    <script src="{{ asset ("/packages/docore/AdminLTE/plugins/jQueryUI/jquery-ui.min.js") }}"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
    $.widget.bridge('uibutton', $.ui.button);
    </script>
    <script src="{{ asset ("/packages/docore/AdminLTE/bootstrap/js/bootstrap.min.js") }}"></script>
    <script src="{{ asset ("/packages/docore/AdminLTE/plugins/slimScroll/jquery.slimscroll.min.js") }}"></script>
    <script src="{{ asset ("/packages/docore/AdminLTE/dist/js/app.min.js") }}"></script>
    <script src="{{ asset ("/packages/docore/jquery-pjax/jquery.pjax.js") }}"></script>

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body class="hold-transition {{Docore::configs('skin')}} {{join(' ', Docore::configs('layout'))}}">
<div class="wrapper">

    @include('docore::partials.header')

    @include('docore::partials.sidebar')

    <div class="content-wrapper" id="pjax-container">
        @yield('content')
        {!! Docore::script() !!}
    </div>

    @include('docore::partials.footer')

    @include('docore::partials.controlbar')
</div>

<!-- ./wrapper -->

<!-- REQUIRED JS SCRIPTS -->
<script src="{{ asset ("/packages/docore/AdminLTE/plugins/chartjs/Chart.min.js") }}"></script>
<script src="{{ asset ("/packages/docore/nestable/jquery.nestable.js") }}"></script>
<script src="{{ asset ("/packages/docore/noty/jquery.noty.packaged.min.js") }}"></script>
<script src="{{ asset ("/packages/docore/bootstrap3-editable/js/bootstrap-editable.min.js") }}"></script>

{!! Docore::js() !!}
<script src="{{ asset ("/packages/docore/AdminLTE/dist/js/demo.js") }}"></script>

<link rel="stylesheet" href="{{ asset ("/packages/docore/incore/ui.css") }}">
<script src="{{ asset ("/packages/docore/incore/ui.js") }}"></script>

<script>

    $.fn.editable.defaults.params = function (params) {
        params._token = '{{ csrf_token() }}';
        params._editable = 1;
        params._method = 'PUT';
        return params;
    };

    $.noty.defaults.layout = 'topRight';
    $.noty.defaults.theme = 'relax';

    $.pjax.defaults.timeout = 5000;
    $.pjax.defaults.maxCacheLength = 0;
    $(document).pjax('a:not(a[target="_blank"])', {
        container: '#pjax-container'
    });

    $(document).on('submit', 'form[pjax-container]', function(event) {
        $.pjax.submit(event, '#pjax-container')
    });

    $(document).on('pjax:error', function(event, xhr) {

        var message = '';

        try{
            response = JSON.parse(xhr.responseText);
            message = response.message || 'error';
        }catch(e){

            if (xhr.status == 0) {
                return;
            }

            noty({
                text: "<strong>Warning!</strong><br/>"+xhr.statusText,
                type:'warning',
                timeout: 5000
            });
            return false;
        }

        if (message) {
            noty({
                text: "<strong>Warning!</strong><br/>"+message,
                type:'warning',
                timeout: 5000
            });
        }

        return false;
    });

    $(document).on("pjax:popstate", function() {

        $(document).one("pjax:end", function(event) {
            $(event.target).find("script[data-exec-on-popstate]").each(function() {
                $.globalEval(this.text || this.textContent || this.innerHTML || '');
            });
        });
    });

</script>

</body>
</html>
