<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{{Docore::configs('title')}} | {{ trans('docore::lang.login') }}</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.5 -->
  <link rel="stylesheet" href="{{ asset("/packages/docore/AdminLTE/bootstrap/css/bootstrap.min.css") }}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset("/packages/docore/font-awesome/css/font-awesome.min.css") }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset("/packages/docore/AdminLTE/dist/css/AdminLTE.min.css") }}">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{ asset("/packages/docore/AdminLTE/plugins/iCheck/square/blue.css") }}">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="{{ Docore::url('/') }}"><b>{{Docore::configs('name')}}</b></a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">{{ trans('docore::lang.login') }}</p>

    <form action="{{ Docore::url('auth/login') }}" method="post">
      <div class="form-group has-feedback {!! !$errors->has('username') ?: 'has-error' !!}">

        @if($errors->has('username'))
          @foreach($errors->get('username') as $message)
            <label class="control-label" for="inputError"><i class="fa fa-times-circle-o"></i>{{$message}}</label></br>
          @endforeach
        @endif

        <input type="input" class="form-control" placeholder="{{ trans('docore::lang.username') }}" name="username" value="{{ old('username') }}">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback {!! !$errors->has('password') ?: 'has-error' !!}">

        @if($errors->has('password'))
          @foreach($errors->get('password') as $message)
            <label class="control-label" for="inputError"><i class="fa fa-times-circle-o"></i>{{$message}}</label></br>
          @endforeach
        @endif

        <input type="password" class="form-control" placeholder="{{ trans('docore::lang.password') }}" name="password" value="{{ old('username') }}">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">

        <!-- /.col -->
        <div class="col-xs-4 col-md-offset-4">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <button type="submit" class="btn btn-primary btn-block btn-flat">{{ trans('docore::lang.login') }}</button>
        </div>
        <!-- /.col -->
      </div>
    </form>

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 2.1.4 -->
<script src="{{ asset("/packages/docore/AdminLTE/plugins/jQuery/jQuery-2.2.3.min.js")}} "></script>
<!-- Bootstrap 3.3.5 -->
<script src="{{ asset("/packages/docore/AdminLTE/bootstrap/js/bootstrap.min.js")}}"></script>
<!-- iCheck -->
<script src="{{ asset("/packages/docore/AdminLTE/plugins/iCheck/icheck.min.js")}}"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });
</script>
</body>
</html>
