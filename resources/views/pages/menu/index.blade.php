@extends('layout.default') @section('content')
<link
    href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css"
    rel="stylesheet"
/>
<link
    rel="stylesheet"
    href="{{
        asset(
            'adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css'
        )
    }}"
/>
<link
    rel="stylesheet"
    href="{{
        asset(
            'adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css'
        )
    }}"
/>
<link
    rel="stylesheet"
    href="{{ asset('adminlte/plugins/daterangepicker/daterangepicker.css') }}"
/>
<link
    rel="stylesheet"
    href="{{ asset('adminlte/plugins/dropzone/min/dropzone.min.css') }}"
/>
<link
    rel="stylesheet"
    href="{{ asset('adminlte/plugins/toastr/toastr.min.css') }}"
/>
<script src="{{ asset('adminlte/plugins/toastr/toastr.min.js') }}"></script>

<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <table class="table" id="menuTable" width="100%">
                <thead>
                    <th>No</th>
                    <th>Menu</th>
                    <th>URL</th>
                </thead>
                <tbody>
                    @foreach($menu as $key=> $m)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{$m->name}}</td>
                        <td>{{$m->url}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div
    class="modal fade"
    id="exampleModal"
    tabindex="-1"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button
                    type="button"
                    class="close"
                    data-dismiss="modal"
                    aria-label="Close"
                >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form-horizontal" id="menuForm">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Nama</label>
                        <input
                            type="text"
                            name="name"
                            class="form-control"
                            id="menu_name"
                            placeholder="Contoh : Laporan"
                            required
                        />
                    </div>

                    <div class="form-group">
                        <label for="exampleInputEmail1">URL</label>
                        <input
                            type="text"
                            name="url"
                            class="form-control"
                            id="url"
                            placeholder="Contoh : laporan"
                            readonly
                        />
                    </div>
                </div>
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-dismiss="modal"
                    >
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary submit-button">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<script src="{{
        asset('adminlte/plugins/datatables/jquery.dataTables.min.js')
    }}"></script>
<script src="{{
        asset('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')
    }}"></script>
<script src="{{
        asset(
            'adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js'
        )
    }}"></script>
<script src="{{
        asset(
            'adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js'
        )
    }}"></script>
<script src="{{ asset('adminlte/plugins/moment/moment.min.js') }}"></script>
<script src="{{
        asset('adminlte/plugins/daterangepicker/daterangepicker.js')
    }}"></script>

<script>
    menuForm = document.querySelector("#menuForm");
    $(function () {
        tabel = $("#menuTable").DataTable({
            searching: false,
            lengthChange: false,
        });
    });

    document
        .querySelector("#menu_name")
        .addEventListener("keyup", function (e) {
            document.querySelector("#url").value = e.target.value
                .toLowerCase()
                .replace(/ /g, "-");
        });

    addMenu = () => {
        document.querySelector(".modal-title").innerHTML = "Tambah Menu";
        document.querySelector(".submit-button").innerHTML = "Simpan";

        $("#exampleModal").modal("show");
    };

    editMenu = (id) => {
        requestJs("{{route('menu.detail', '')}}" + `/${id}`, "GET").then(
            (res) => {
                document.querySelector(".modal-title").innerHTML = "Edit Menu";
                document.querySelector(".submit-button").innerHTML = "Update";
                $("#id").val(res.data.id);
                $("#menu_name").val(res.data.name);
                $("#url").val(res.data.url);

                $("#exampleModal").modal("show");
            }
        );
    };

    menuForm.addEventListener("submit", (e) => {
        e.preventDefault();
        formData = new FormData(e.currentTarget);

        requestJs("{{route('menu.store')}}", "POST", formData).then(
            async (res) => {
                if (res.code == 200 && res.status == true) {
                    await window.location.reload();

                    menuForm.reset();
                    $("#exampleModal").modal("hide");
                } else {
                    Object.keys(res.message).map((key) => {
                        toastr.options.closeButton = true;
                        toastr.options.progressBar = true;
                        toastr.error(res.message[key], {
                            timeOut: 1000,
                        });
                    });
                }
            }
        );
    });

    $("#exampleModal").on("hidden.bs.modal", function () {
        $("#id").val("");
        menuForm.reset();
        $('input[type="checkbox"]').prop("checked", false).change();
    });
</script>
@endsection
