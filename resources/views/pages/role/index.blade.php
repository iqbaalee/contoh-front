@extends('layout.default')
@section('content')
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('adminlte/plugins/daterangepicker/daterangepicker.css')}}">
<link rel="stylesheet" href="{{asset('adminlte/plugins/dropzone/min/dropzone.min.css')}}">
<link rel="stylesheet" href="{{asset('adminlte/plugins/toastr/toastr.min.css')}}">
<script src="{{asset('adminlte/plugins/toastr/toastr.min.js')}}"></script>

<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <button class="btn btn-sm btn-primary" onclick="addRole()">Tambah</button>
            <table class="table" id="roleTable" width="100%">
                <thead>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Hak Akses Menu</th>
                    <th>Aksi</th>
                </thead>
                <tbody>
                    @foreach($roles as $key=> $role)
                    <tr>
                        <td>{{$key + 1}}</td>
                        <td>{{$role->name}}</td>
                        <td>
                            @foreach($role->permissions as $permission)
                            <span class="badge badge-success">{{$permission->menu->name}}</span>
                            @endforeach
                        </td>
                        <td>
                            <button class="btn btn-sm btn-primary" onclick="editRole('{{$role->id}}')"><i
                                    class="fas fa-pen"></i></button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form-horizontal" id="roleForm">
                @csrf
                <input type="hidden" name="id" id="id">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Nama</label>
                        <input type="text" name="name" class="form-control" id="role_name" placeholder="Contoh : Admin"
                            required>
                    </div>

                    <label for="exampleInputEmail1">Menu</label>
                    @foreach($menu as $v)
                    <div class="form-group row d-flex align-items-center">
                        <div class="col-sm-4">
                            <h6>{{$v->name}}</h6>
                        </div>
                        <div class="col-sm-8">
                            <input name="permissions[]" value="{{$v->id}}" id="permission_id-{{$v->id}}" type="checkbox"
                                data-toggle="toggle" data-onstyle="success" data-offstyle="danger" data-size="small">
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary submit-button">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<script src="{{asset('adminlte/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
<script src="{{asset('adminlte/plugins/moment/moment.min.js')}}"></script>
<script src="{{asset('adminlte/plugins/daterangepicker/daterangepicker.js')}}"></script>

<script>
    roleForm = document.querySelector('#roleForm');
    $(function () {
        tabel = $('#roleTable').DataTable({
            searching: false,
            lengthChange: false,

        });

    })

    addRole = () => {
        document.querySelector('.modal-title').innerHTML = 'Tambah Hak Akses'
        document.querySelector('.submit-button').innerHTML = 'Simpan'

        $('#exampleModal').modal('show')
    }

    editRole = (id) => {
        requestJs("{{route('role.detail', '')}}" + `/${id}`, 'GET').then(res => {

            document.querySelector('.modal-title').innerHTML = 'Edit Hak Akses'
            document.querySelector('.submit-button').innerHTML = 'Update'
            $('#id').val(res.data.id)
            $('#role_name').val(res.data.name)
            res.data.permissions.forEach(v => {
                $('#permission_id-' + v.menu.id).bootstrapToggle('on')
            })
            $('#exampleModal').modal('show')
        })
    }

    roleForm.addEventListener('submit', (e) => {
        e.preventDefault()
        formData = new FormData(e.currentTarget)

        requestJs("{{route('role.store')}}", "POST", formData).then(async (res) => {

            if (res.code == 200 && res.status == true) {
                await window.location.reload()

                roleForm.reset()
                $('#exampleModal').modal('hide')
            } else {
                Object.keys(res.message).map((key) => {
                    toastr.options.closeButton = true;
                    toastr.options.progressBar = true;
                    toastr.error(res.message[key], {
                        timeOut: 1000

                    })
                })
            }
        })
    })



    $("#exampleModal").on("hidden.bs.modal", function () {
        $("#id").val('')
        roleForm.reset()
        $('input[type="checkbox"]').prop('checked', false).change()
    });

</script>
@endsection
