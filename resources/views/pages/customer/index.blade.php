@extends('layout.default')
@section('content')
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('adminlte/plugins/daterangepicker/daterangepicker.css')}}">
<link rel="stylesheet" href="{{asset('adminlte/plugins/dropzone/min/dropzone.min.css')}}">
<link rel="stylesheet" href="{{asset('adminlte/plugins/toastr/toastr.min.css')}}">
<link rel="stylesheet" href="{{asset('adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css')}}">
<script src="{{asset('adminlte/plugins/toastr/toastr.min.js')}}"></script>
<script src="{{asset('adminlte/plugins/sweetalert2/sweetalert2.min.js')}}"></script>

<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <button class="btn btn-sm btn-primary" onclick="addRole()">Tambah</button>
            <table class="table" id="roleTable" width="100%">
                <thead>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Telepon</th>
                    <th>Alamat</th>
                    <th>Aksi</th>
                </thead>
                <tbody>
                    @foreach($customer as $key=> $c)
                    <tr>
                        <td>{{$key + 1}}</td>
                        <td>{{$c->name}}</td>
                        <td>{{$c->email}}</td>
                        <td>{{$c->phone}}</td>
                        <td>{{$c->address}}</td>
                        <td>
                            <button class="btn btn-sm btn-primary" onclick="editCustomer('{{$c->id}}')"><i
                                    class="fas fa-pen"></i></button>
                            <button class="btn btn-sm btn-danger" onclick="deleteCustomer('{{$c->id}}')"><i
                                    class="fas fa-trash"></i></button>
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
            <form class="form-horizontal" id="customerForm">
                @csrf
                <input type="hidden" name="id" id="id">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Nama</label>
                        <input type="text" name="name" class="form-control" id="name" placeholder="Contoh : Admin"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Email</label>
                        <input type="email" name="email" class="form-control" id="email"
                            placeholder="Contoh : haris@gmail.com" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">No. Telepon</label>
                        <input type="number" name="phone" class="form-control" id="phone" value="62"
                            placeholder="Contoh : 6285505999" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Alamat</label>
                        <textarea name="address" class="form-control" id="address" rows="3" required></textarea>
                    </div>
                    <label for="" class="text-danger">*) Password sudah ditetapkan pada saat simpan akun</label>
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
    customerForm = document.querySelector('#customerForm');
    $(function () {
        tabel = $('#roleTable').DataTable({
            searching: false,
            lengthChange: false,

        });

    })

    addRole = () => {
        document.querySelector('.modal-title').innerHTML = 'Tambah Pengguna'
        document.querySelector('.submit-button').innerHTML = 'Simpan'

        $('#exampleModal').modal('show')
    }

    editCustomer = (id) => {
        requestJs("{{route('customer.detail', '')}}" + `/${id}`, 'GET').then(res => {

            document.querySelector('.modal-title').innerHTML = 'Edit Customer'
            document.querySelector('.submit-button').innerHTML = 'Update'
            $('#id').val(res.data.id)
            $('#name').val(res.data.name)
            $('#email').val(res.data.email)
            $("#phone").val(res.data.phone)
            $('#address').val(res.data.address)
            $('#exampleModal').modal('show')
        })
    }

    deleteCustomer = (id) => {
        swalNotif('Informasi', 'Apakah anda yakin ingin menghapus data ini?', 'warning', 'Ya, Hapus!', 'Tidak')
            .then((result) => {
                if (result.isConfirmed) {
                    formData = new FormData();
                    formData.append('_method', 'DELETE')
                    formData.append('id', id)
                    formData.append('_token', "{{csrf_token()}}")
                    requestJs("{{route('customer.delete')}}", "POST", formData).then(res => {
                        if (res.code == 200 && res.status) {
                            location.reload()
                        }
                    })

                }
            })
    }

    customerForm.addEventListener('submit', (e) => {
        e.preventDefault()
        formData = new FormData(e.currentTarget)

        requestJs("{{route('customer.store')}}", "POST", formData).then(async (res) => {

            if (res.code == 200 && res.status == true) {
                await window.location.reload()

                customerForm.reset()
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
        customerForm.reset()
    });

</script>
@endsection
