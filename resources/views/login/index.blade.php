<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Tonase Pretest | BE</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{asset('admin/plugins/fontawesome-free/css/all.min.css')}}">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="{{asset('admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('admin/assets/css/adminlte.min.css')}}">
  <style>
    .error-feedback{
      color: red;
      font-size: medium;
    }

    #errFeedback{
      display: none;
    }
  </style>
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="#">Pretest<b>BE</b></a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
  <form action="" id="form1">
      <div class="card-body login-card-body">
        <div class="input-group mb-3">
          <input type="email" class="form-control" placeholder="Email" id="email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Password" id="password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3" id="errFeedback">
          <span class="error-feedback"></span>
        </div>
        <div class="text-center">
          <button type="button" class="btn btn-primary btn-block" id="signIn">
          <span class="spinner-border spinner-border-sm check-loader" role="status" aria-hidden="true" style="display:none;"></span><span id="btnTxt">Sign In</span></button>
        </div>
      </div>
      </form>

      <div class="social-auth-links text-center mb-3">

      <p class="mb-1">
        <a href="forgot-password.html">I forgot my password</a>
      </p>
      <p class="mb-0">
        <a href="register.html" class="text-center">Register a new membership</a>
      </p>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="{{asset('admin/plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('admin/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('admin/js/adminlte.min.js')}}"></script>
<script src="{{asset('admin/plugins/jquery-validation/jquery.validate.min.js')}}"></script>
<script>
  $("#signIn").click(function(){
    if(!validate()){
      return false;
    }
    $.ajax({
      url: "/api/user/login",
      dataType: "json",
      type: "POST",
      data: {
        "email" : $("#email").val(),
        "password" : $("#password").val()
      },
      beforeSend: function(){
        $("#btnTxt").text('Signing In...');
        $(".check-loader").css('display', "inline-block");
      },
      success: function(res){
        res = res.data;
        window.location = "/set_session?user_id="+res.user_id+"&name="+res.name;
      },
      error: function(err){
        err = err.responseJSON;
        $("#errFeedback").show();
        $(".error-feedback").text(err.data);
      },
      complete: function(){
        $("#btnTxt").text('Sign In');
        $(".check-loader").css('display', "none");
      }
    });
  });

  function validate(){
    let email = $("#email").val();
    let pw = $("#password").val();
    if(email == "" || password == ""){
      $("#errFeedback").show();
      $(".error-feedback").text("Email/Password can't be empty");
      return false;
    }
    
    return true;
  }
</script>
</body>
</html>
