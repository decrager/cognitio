
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BPKP | Log in</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('bower_components/font-awesome/css/font-awesome.min.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('dist/css/AdminLTE.min.css')}}">
<body class="hold-transition h-100 d-flex justify-content-center" style="background: #ececec">
<div class="login-box m-auto">
    <div class="login-logo">
        <img class="w-50" src="{{asset('dist/img/BPKP_Logo.png')}}">
        <h5 class="text-bold mt-4">Aplikasi Managemen Pelatihan Pegawai</h5>
    </div>
    <!-- /.login-logo -->
    <div class="card mt-2">
        <div class="card-body login-card-body">
            <p class="login-box-msg">Silahkan Login</p>

            @error('email')
                <div class="alert alert-danger" role="alert">
                    Kredensial yang diberikan tidak sesuai.
                </div>
            @enderror

            <form action="{{route('login-action')}}" method="post">
                @csrf
                <div class="input-group mb-3">
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email" value="{{ old('email') }}" required>
                    <div class="input-group-append" style="width: 10%">
                        <div class="input-group-text px-2">
                            <span class="fa fa-envelope"></span>
                        </div>
                    </div>
                    {{-- @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>Email yang dimasukkan belum terdaftar</strong>
                        </span>
                    @enderror --}}
                </div>
                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control @error('email') is-invalid @enderror" placeholder="Password" required>
                    <div class="input-group-append" style="width: 10%">
                        <div class="input-group-text px-2 w-100 d-flex justify-content-center">
                            <span class="fa fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <!-- /.col -->
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-block">Login</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>
        </div>
        <!-- /.login-card-body -->
    </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('dist/js/adminlte.min.js')}}"></script>
</body>
</html>
