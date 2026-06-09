<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ERP LOGIN</title>
    <link rel="stylesheet" href="{{url('css/style.css')}}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

</head>
<style>
.authentication-wrapper {
  display: flex;
  flex-basis: 100%;
  min-height: 100vh;
  width: 100%;
}
.authentication-wrapper .authentication-inner {
  width: 100%;
}
.authentication-wrapper.authentication-basic {
  align-items: baseline;
  justify-content: center;
}
.authentication-wrapper .authentication-image {
  z-index: -1;
  inline-size: 100%;
  inset-block-end: 7%;
  position: absolute;
  inset-inline-start: 0;
}
.authentication-wrapper.authentication-cover {
  align-items: flex-start;
}
.authentication-wrapper.authentication-basic .authentication-inner {
  max-width: 450px;
}
.authentication-wrapper .auth-input-wrapper .auth-input {
  max-width: 50px;
  padding-left: 0.4rem;
  padding-right: 0.4rem;
  font-size: 150%;
}
.help-center-header {
    background-image: url(image/hero-bg.png);
    background-repeat: no-repeat;
    background-size: cover;
    background-position: center;
}
</style>

<body>
    <section>
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
        <div class="help-center-header">
            <div class="authentication-basic authentication-wrapper container-p-y">
                <div class="authentication-inner mx-4">
                    <div class="bg-light card p-2">
                        <div class="card-body">
                            <h4 class="text-center">LOGIN</h4>
                            <div class="logo">
                                <img src="{{url('logo.png')}}" alt="logo">
                            </div>
                            <form action="{{ route('admin.login') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label>User Name</label>
                                    <input type="text" name="uname" class="form-control" placeholder="Enter Email">
                                </div>
                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="password" name="upass" class="form-control"  placeholder="Password">
                                </div>
                                <button type="submit" class="btn btn-primary m-0 w-100">Login</button>
                            </form>
                        </div>
                </div>
                </div>
            </div>
        </div>
    </section>
</body>
<footer>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</footer>
</html>