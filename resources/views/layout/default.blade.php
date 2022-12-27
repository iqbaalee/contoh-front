<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{env('APP_NAME')}} | {{$title}}</title>

    @include('includes.style')

</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        {{-- navbar --}}
        @include('includes.navbar')

        {{-- Sidebar --}}
        @include('includes.sidebar')

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h4 class="m-0">{{$title}}</h4>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        {{-- Content --}}
                        @yield('content')
                    </div>
                </div>
            </section>
        </div>
        <footer class="main-footer">
            <strong>2022 | Admin Resto
                <!-- <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 3.2.0
    </div> -->
        </footer>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    {{-- script --}}

    @include('includes.script')
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
