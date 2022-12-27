@extends('layout.default')
@section('content')
<style>
    .card-border {
        border: 3px solid #07bc4c
    }

</style>
<link rel="stylesheet" href="{{asset('custom.css')}}">
<div class="col-md-3">

    <div class="input-group mb-3">
        <select class="custom-select" id="month" name="month">
            <option value="">Pilih Bulan</option>
            @foreach($listMonth as $month)
            <option value="{{Carbon\Carbon::parse($month)->format('m')}}">
                {{$month}}
            </option>
            @endforeach
        </select>
        <select class="custom-select" id="year" name="year">
            <option value="">Pilih Tahun</option>
            @foreach($listYear as $year)
            <option value="{{$year}}">{{$year}}</option>
            @endforeach
        </select>
        <div class="input-group-append">
            <button class=" btn btn-sm btn-primary" type="button" onclick="getSchedule()"><i
                    class=" fas fa-search"></i></button>
        </div>
    </div>

</div>
<div class="col-md-12">


    <div style="position: relative;" class="calendar-container">

        <button class="btn btn-sm btn-primary button-scroll" onclick="prev()">Prev</button>
        <div class="d-flex overflow-auto scroll-container" style="margin-left:-10px; scroll-behavior: smooth;">
            @for($i=1;$i<=$dateNow;$i++)
            
            <button id="day-{{$i}}" onclick="getSchedule({{$i}})"
                class="btn btn-outline-primary p-3"
                style="margin-left: 10px; min-width: 50px; scroll-snap-align:start;">
                {{$i}}</button>
            @endfor
        </div>

        <button class="btn btn-sm btn-primary button-scroll" onclick="next()">Next</button>
    </div>

    <div class="row mt-4" id="listSchedule">
       
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
                <form class="form-horizontal" id="scheduleForm">
                    @csrf
                    <input type="hidden" name="id" id="id">
                    <div class="modal-body">

                        <div class="alert alert-success" role="alert">
                            <h5 class="alert-heading font-weight-bold">Informasi Pemesan</h5>
                            <div class="row">
                                <div class="col-sm-3">Nama</div>
                                <div class="col-sm-4" id="order-name"></div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3">Hp</div>
                                <div class="col-sm-4" id="order-phone"></div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3">Pembayaran</div>
                                <div class="col-sm-4" id="order-dp"></div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3">Tgl Order</div>
                                <div class="col-sm-4" id="order-order_date"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Waktu</label>
                            <input type="text" name="name" class="form-control" id="name" value="09.00 - 10.00"
                                readonly>

                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Jam Awal Main (jam)</label>
                            <input type="text" name="time" class="form-control" id="time" value="" readonly>

                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Deskripsi</label>
                            <textarea name="description" id="description" cols="2" rows="2"
                                class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Harga</label>
                            <input type="number" name="price" class="form-control" id="price" min="0" max="200000">

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary submit-button">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
</div>

<script>
    scheduleForm = document.querySelector('#scheduleForm');
    filterForm = document.querySelector('#filterForm');
    scrollContainer = document.querySelector('.scroll-container');
    alertContainer = document.querySelector('.alert');

    alertContainer.style.display = 'none';

    $(function(){
        getSchedule();
    })

    addSchedule = () => {
        $(".modal-title").html('Tambah Jadwal')
        $(".submit-button").html('Simpan')
        $("#exampleModal").modal('show')
    }

    scheduleForm.addEventListener('submit', (e) => {
        e.preventDefault();
        let url;
        let method;
        let formData = new FormData(e.currentTarget);
        if (formData.get('id') == '') {
            url = "{{route('schedule.store')}}";
            method = "POST";
        } else {
            url = "{{route('schedule.update', '')}}" + `/${formData.get('id')}`;
            method = "POST";
            formData.append('_method', 'PUT');
        }
        requestJs(url, method, formData).then(res => {
            if (res.status) {
                $("#exampleModal").modal('hide')
                location.reload();
            }
        })

    })

    getSchedule = (day = 0) => {
       
       

        var url = "{{route('schedule.ajax_get_schedule')}}";
        var param = {
            day: (day > 0) ? day < 10 ? '0' + day : day : null,
            month: $('#month').val(),
            year: $('#year').val() ? $('#year').val() : null,
        }
        url = url + '?' + $.param(param);

        if (day != 0) {
 
            $(".scroll-container > button").removeClass('btn-primary')
            $(".scroll-container > button").addClass('btn-outline-primary')
            $(`#day-${day}`).removeClass('btn-outline-primary').addClass('btn-primary')

            requestJs(url, 'GET').then((res) => {
                console.log(res)
                generateList(res)
            })

        } else {
            requestJs(url, 'GET').then((res) => {
                generateList(res)
            })
        }
    }


    prev = () => {
        scrollContainer.scrollLeft -= scrollContainer.clientWidth;
    }
    next = () => {
        scrollContainer.scrollLeft += scrollContainer.clientWidth;
    }

    detailSchedule = (product_id, order_id='') => {
        let url = "{{route('schedule.detail', '')}}" + `/${product_id}`;
        let param = {
            order_id: order_id ?? ''
        }
        url = order_id != '' ? url + '?' + $.param(param) : url;
        requestJs(url, 'GET').then(res => {
           console.log(res)
            $(".modal-title").html('Detail Jadwal')
            $("#exampleModal").modal('show')
            $("#id").val(res.data?.id)
            $("#name").val(res.data?.name)
            $("#time").val(res.data?.time)
            $("#description").val(res.data?.description)  
            $("#price").val(res.data?.price)

            if (res.data?.length != 0 && res.data?.orders != []) {
                $("#order-name").html(res.data?.orders[0]?.name)
                $("#order-phone").html(res.data?.orders[0].phone)
                $("#order-dp").html(res.data?.orders[0].down_payment)
                $("#order-order_date").html(dateFormat(res.data?.orders[0].order_date))
                alertContainer.style.display = 'block';
            } else {
                $("#order-name").html('')
                $("#order-phone").html('')
                $("#order-dp").html('')
                $("#order-order_date").html('')
                alertContainer.style.display = 'none';
            }
        })
    }
    dateFormat = (date) => {
        let d = new Date(date);
        let month = '' + (d.getMonth() + 1);
        let day = '' + d.getDate();
        let year = d.getFullYear();

        if (month.length < 2) month = '0' + month;
        if (day.length < 2) day = '0' + day;

        return [day, month, year].join('/');
    }

    $("#exampleModal").on('hidden.bs.modal', function () {
        scheduleForm.reset()
        alertContainer.style.display = 'none';
    });

    generateList = (res) => {
        let temp = '';
        res.data?.forEach(element => {
                    
                    let html = `<div class="col-md-3"><div class="card ${element.orders.length != 0 ? 'card-border' : ''}" id="card-${element.id}" style="cursor: pointer;"
                onclick="detailSchedule('${element.id}', '${element.orders[0]?.id ?? ''}')">

                <div class="card-body  p-2" style="border-width:3px !important;">
                    <div class="row d-flex align-items-center">
                        <img src="https://picsum.photos/70/70" class="rounded mx-2 p-0" alt="logo-tim">
                        <div class="col">
                            <h4>${element.orders.length != 0 ? element?.orders[0]?.name : 'Kosong'}</h4>
                            <h6>${element?.name}</h6>
                            <h6 class="mt-0">Rp${new Intl.NumberFormat('id-ID').format(element?.price)}</h6>
                        </div>
                    </div>
                </div>
             </div>
            </div>`;

            
            temp += html;
        });
        document.getElementById('listSchedule').innerHTML = temp;
    }

</script>
@endsection
