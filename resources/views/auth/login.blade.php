<!DOCTYPE html>

<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <title>{{ env("APP_NAME") }} | {{ $title }}</title>

        <!-- Google Font: Source Sans Pro -->
        <link
            rel="stylesheet"
            href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback"
        />
        <link
            rel="icon"
            href="{{ asset('warehouse.png') }}"
            type="image/x-icon"
        />

        <!-- Font Awesome -->
        <link
            rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
            integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg=="
            crossorigin="anonymous"
            referrerpolicy="no-referrer"
        />
        <!-- Theme style -->
        <link
            rel="stylesheet"
            href="{{ asset('adminlte/dist/css/adminlte.min.css') }}"
        />
        <link
            rel="stylesheet"
            type="text/css"
            href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.css"
        />
        {{-- Toastr JS CSS --}}
        <script src="{{
                asset('adminlte/plugins/jquery/jquery.min.js')
            }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    </head>

    <body class="hold-transition login-page">
        <div class="login-box">
            <!-- /.login-logo -->
            <div class="card card-outline card-primary">
                <div class="card-header text-center">
                    <a href="#" class="h4"
                        ><b>{{ env("APP_NAME") }}</b
                        ><br />Admin Dashboard</a
                    >
                </div>
                <div class="card-body">
                    <p class="login-box-msg">Masuk untuk memulai sesi</p>
                    <form
                        action="{{ route('auth.login_action') }}"
                        method="post"
                    >
                        @csrf
                        <div class="input-group">
                            <input
                                type="text"
                                name="email"
                                class="form-control"
                                placeholder="Email"
                                required
                                value="{{ old('email') }}"
                            />
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                        </div>
                        @if ($errors->has('email'))
                        <span
                            class="text-danger"
                            >{{ $errors->first('email') }}</span
                        >
                        @endif
                        <div class="input-group mt-3">
                            <input
                                type="password"
                                name="password"
                                class="form-control"
                                placeholder="Password"
                            />
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>
                        @if ($errors->has('password'))
                        <span
                            class="text-danger"
                            >{{ $errors->first('password') }}</span
                        >
                        @endif
                        <div class="row mt-3">
                            <!-- /.col -->
                            <div class="col-12">
                                <button
                                    type="submit"
                                    class="btn btn-primary btn-block"
                                >
                                    Sign In
                                </button>
                            </div>
                            <!-- /.col -->
                        </div>
                    </form>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.login-box -->

        <!-- Bootstrap 4 -->
        <script src="{{
                asset('plugins/bootstrap/js/bootstrap.bundle.min.js')
            }}"></script>
        <!-- AdminLTE App -->
        <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
        <script>
            $(function () {
                toastr.options = {
                    "closeButton": true,
                    "debug": false,
                    "newestOnTop": false,
                    "progressBar": true,
                    "positionClass": "toast-bottom-right",
                    "preventDuplicates": false,
                    "onclick": null,
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut"
                }
                @if(Session::has('success'))

                toastr.success("{{ Session::get('success') }}", 'Informasi', toastr.options);
                @endif

                @if(Session::has('info'))
                toastr.info("{{ Session::get('info') }}", 'Informasi', toastr.options);
                @endif

                @if(Session::has('warning'))
                toastr.warning("{{ Session::get('warning') }}", 'Informasi', toastr.options);
                @endif

                @if(Session::has('error'))
                toastr.error("{{ Session::get('error') }}", 'Informasi', toastr.options);
                @endif
            });
        </script>
    </body>
</html>
