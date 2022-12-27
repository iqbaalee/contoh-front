@extends('layout.default') @section('content')
<style>
    .card-border {
        border: 3px solid #07bc4c;
    }
</style>
<link rel="stylesheet" href="{{ asset('custom.css') }}" />
<div class="col-12">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-5">
                    <div class="input-group mb-3">
                        <select class="custom-select" id="month" name="month">
                            <option value="">Pilih Bulan</option>
                            @foreach($listMonth as $month)
                            <option
                                value="{{Carbon\Carbon::parse($month)->format('m')}}"
                            >
                                {{ $month }}
                            </option>
                            @endforeach
                        </select>
                        <select class="custom-select" id="year" name="year">
                            <option value="">Pilih Tahun</option>
                            @foreach($listYear as $year)
                            <option value="{{ $year }}">
                                {{ $year }}
                            </option>
                            @endforeach
                        </select>
                        <div class="input-group-append">
                            <button
                                class="btn btn-sm btn-primary"
                                type="button"
                                onclick="getTable()"
                            >
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div style="position: relative" class="calendar-container">
                <button
                    class="btn btn-sm btn-primary button-scroll"
                    onclick="prev()"
                >
                    Prev
                </button>
                <div
                    class="d-flex overflow-auto scroll-container"
                    style="margin-left: -10px; scroll-behavior: smooth"
                >
                    @php $day =
                    \Carbon\Carbon::now()->timezone('Asia/Jakarta')->format('d');
                    @endphp @for($i=1;$i<=$dateNow;$i++)
                    <button
                        id="day-{{ $i }}"
                        onclick="getTable({{ $i }})"
                        class="btn {{
                            $day == $i ? 'btn-primary' : 'btn-outline-primary'
                        }} p-3"
                        style="
                            margin-left: 10px;
                            min-width: 50px;
                            scroll-snap-align: start;
                        "
                    >
                        {{ $i }}
                    </button>
                    @endfor
                </div>

                <button
                    class="btn btn-sm btn-primary button-scroll"
                    onclick="next()"
                >
                    Next
                </button>
            </div>

            <div class="row mt-4" id="listTable"></div>

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
                        <form class="form-horizontal" id="scheduleForm">
                            @csrf
                            <input type="hidden" name="id" id="id" />
                            <div class="modal-body">
                                <div class="alert alert-success" role="alert">
                                    <h5 class="alert-heading font-weight-bold">
                                        Informasi Pemesan
                                    </h5>
                                    <div class="row">
                                        <div class="col-sm-3">Nama</div>
                                        <div
                                            class="col-sm-4"
                                            id="order-name"
                                        ></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-3">Hp</div>
                                        <div
                                            class="col-sm-4"
                                            id="order-phone"
                                        ></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-3">Pembayaran</div>
                                        <div
                                            class="col-sm-4"
                                            id="order-dp"
                                        ></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-3">Tgl Order</div>
                                        <div
                                            class="col-sm-4"
                                            id="order-order_date"
                                        ></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"
                                        >Nama Meja</label
                                    >
                                    <input
                                        type="text"
                                        name="name"
                                        class="form-control"
                                        id="name"
                                        value=""
                                        readonly
                                    />
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"
                                        >Kapasitas (orang)</label
                                    >
                                    <input
                                        type="text"
                                        name="capacity"
                                        class="form-control"
                                        id="capacity"
                                        value=""
                                        readonly
                                    />
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"
                                        >Deskripsi</label
                                    >
                                    <textarea
                                        name="description"
                                        id="description"
                                        cols="2"
                                        rows="2"
                                        class="form-control"
                                        readonly
                                    ></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <!-- <button
                                    type="button"
                                    class="btn btn-secondary"
                                    data-dismiss="modal"
                                >
                                    Batal
                                </button>
                                <button
                                    type="submit"
                                    class="btn btn-primary submit-button"
                                >
                                    Simpan
                                </button> -->
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    scheduleForm = document.querySelector("#scheduleForm");
    filterForm = document.querySelector("#filterForm");
    scrollContainer = document.querySelector(".scroll-container");
    alertContainer = document.querySelector(".alert");

    $(function () {
        alertContainer.style.display = "none";
        getTable();
    });

    addTable = () => {
        $(".modal-title").html("Tambah Jadwal");
        $(".submit-button").html("Simpan");
        $("#exampleModal").modal("show");
    };

    scheduleForm.addEventListener("submit", (e) => {
        e.preventDefault();
        let url;
        let method;
        let formData = new FormData(e.currentTarget);
        if (formData.get("id") == "") {
            url = "{{route('table.store')}}";
            method = "POST";
        } else {
            url = "{{route('table.update', '')}}" + `/${formData.get("id")}`;
            method = "POST";
            formData.append("_method", "PUT");
        }
        requestJs(url, method, formData).then((res) => {
            if (res.status) {
                $("#exampleModal").modal("hide");
                location.reload();
            }
        });
    });

    generateList = (res) => {
        let temp = "";
        res.data?.forEach((element) => {
            let html = `<div class="col-md-3"><div class="card ${
                element.orders.length != 0 ? "card-border" : ""
            }" id="card-${element.id}" style="cursor: pointer;"
                onclick="detailTable(${element.id}, ${element.orders[0]?.id})">

                <div class="card-body  p-2" style="border-width:3px !important;">
                    <div class="row d-flex align-items-center">
                        <img src="https://picsum.photos/70/70" class="rounded mx-2 p-0" alt="logo-tim">
                        <div class="col">
                            <h4 class="my-0">${element?.name}</h4>
                            <span class="badge badge-primary mt-0">${
                                element.orders.length != 0
                                    ? element?.orders[0]?.name
                                    : "Kosong"
                            }</span>
                        </div>
                    </div>
                </div>
             </div>
            </div>`;

            temp += html;
        });
        document.getElementById("listTable").innerHTML = temp;
    };

    getTable = (day = 0) => {
        var url = "{{route('table.ajax_get_table')}}";
        var param = {
            day: day > 0 ? (day < 10 ? "0" + day : day) : null,
            month: $("#month").val(),
            year: $("#year").val() ? $("#year").val() : null,
        };
        url = url + "?" + $.param(param);

        if (day != 0) {
            $(".scroll-container > button").removeClass("btn-primary");
            $(".scroll-container > button").addClass("btn-outline-primary");
            $(`#day-${day}`)
                .removeClass("btn-outline-primary")
                .addClass("btn-primary");

            requestJs(url, "GET").then((res) => {
                generateList(res);
            });
        } else {
            requestJs(url, "GET").then((res) => {
                generateList(res);
            });
        }
    };

    prev = () => {
        scrollContainer.scrollLeft -= scrollContainer.clientWidth;
    };
    next = () => {
        scrollContainer.scrollLeft += scrollContainer.clientWidth;
    };

    detailTable = (product_id, order_id) => {
        let url = "{{route('table.detail', '')}}" + `/${product_id}`;
        let param = {
            order_id: order_id ?? "",
        };
        url = order_id != "" ? url + "?" + $.param(param) : url;
        requestJs(url, "GET").then((res) => {
            $(".modal-title").html("Detail Meja");
            $("#id").val(res.data?.id);
            $("#name").val(res.data?.name);
            $("#description").val(res.data?.description);
            $("#capacity").val(res.data?.capacity);

            if (res.data?.length != 0 && res.data?.orders.length > 0) {
                $("#order-name").html(res?.data?.orders[0]?.name);
                $("#order-phone").html(res?.data?.orders[0]?.phone);
                //number format
                $("#order-dp").html(
                    new Intl.NumberFormat("id-ID", {
                        style: "currency",
                        currency: "IDR",
                    }).format(res?.data?.orders[0]?.down_payment)
                );
                $("#order-order_date").html(
                    dateFormat(res.data?.orders[0]?.order_date)
                );
                alertContainer.style.display = "block";
            } else {
                $("#order-name").html("");
                $("#order-phone").html("");
                $("#order-dp").html("");
                $("#order-order_date").html("");
                alertContainer.style.display = "none";
            }
            $("#exampleModal").modal("show");
        });
    };
    dateFormat = (date) => {
        let d = new Date(date);
        let month = "" + (d.getMonth() + 1);
        let day = "" + d.getDate();
        let year = d.getFullYear();

        if (month.length < 2) month = "0" + month;
        if (day.length < 2) day = "0" + day;

        return [day, month, year].join("/");
    };

    $("#exampleModal").on("hidden.bs.modal", function () {
        scheduleForm.reset();
        alertContainer.style.display = "none";
    });
</script>
@endsection
