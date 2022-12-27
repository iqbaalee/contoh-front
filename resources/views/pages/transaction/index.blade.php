@extends('layout.default') @section('content')

<link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css"
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
    href="{{ asset('adminlte/plugins/select2/css/select2.min.css') }}"
/>
<style>
    .select2-container--bootstrap4
        .select2-selection--multiple
        .select2-selection__choice {
        background-color: #007bff;
        color: #fff;
    }
</style>
<script src="{{ asset('jquery.repeater/jquery.repeater.js') }}"></script>
<div class="col-md-12">
    <div class="row">
        <div class="col-md-3">
            <input
                type="text"
                id="search"
                class="form-control"
                placeholder="Cari nama tim"
            />
        </div>
        <div class="col-0">
            <div class="input-group">
                <button
                    style="background-color: white"
                    type="button"
                    class="btn btn-default float-right"
                    id="daterange-btn"
                >
                    Filter Waktu
                    <i class="fas fa-caret-down"></i>
                </button>
            </div>
        </div>
        <div class="col-sm-2">
            <select name="status" id="status_payment" class="form-control">
                <option value="">Filter Status</option>
                <option value="initial">Belum Bayar</option>
                <option value="down_payment">DP</option>
                <option value="paid">Lunas</option>
                <option value="cancel">Dibatalkan</option>
            </select>
        </div>
    </div>
    <div class="card mt-3">
        <div class="card-body">
            <button class="btn btn-sm btn-primary" onclick="addTransaction()">
                Tambah
            </button>
            <table class="table" id="transaction" width="100%">
                <thead>
                    <th>No Order</th>
                    <th>Nama Customer</th>
                    <th>Tanggal Order</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </thead>
                <tbody></tbody>
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
    <div class="modal-dialog modal-xl">
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
            <form class="form-horizontal" id="transactionForm">
                @csrf
                <input type="hidden" name="id" id="id" />
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Tanggal Booking:</label>
                                <div
                                    class="input-group date"
                                    id="reservationdate"
                                    data-target-input="nearest"
                                >
                                    <input
                                        type="text"
                                        name="order_date"
                                        class="form-control datetimepicker-input"
                                        data-target="#reservationdate"
                                        autocomplete="off"
                                    />
                                    <div
                                        class="input-group-append"
                                        data-target="#reservationdate"
                                        data-toggle="datetimepicker"
                                    >
                                        <div class="input-group-text">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1"
                                    >Customer:</label
                                >

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <select
                                            name="customer_id"
                                            id="customer_id"
                                            class="form-control"
                                        >
                                            <option value="">
                                                -Pilih Customer-
                                            </option>
                                            @if(!empty($customer))
                                            @foreach($customer as $c)
                                            <option value="{{$c->id}}">
                                                {{$c->name}}
                                            </option>
                                            @endforeach @endif
                                        </select>
                                    </div>

                                    <button
                                        type="button"
                                        class="btn btn-sm btn-primary"
                                        onclick="inputNewCustomer()"
                                    >
                                        Input Customer Baru
                                    </button>
                                </div>
                                <div class="new-customer">
                                    <input
                                        type="text"
                                        name="new_customer"
                                        id="new_customer"
                                        class="form-control"
                                        placeholder="Customer Baru"
                                    />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Meja:</label>
                                <select
                                    class="select2"
                                    name="table_id[]"
                                    id="table_id"
                                    multiple="multiple"
                                    data-placeholder="Select a State"
                                    style="width: 100%"
                                >
                                    <option value="">-Pilih Meja-</option>
                                    @if(!empty($table)) @foreach($table as $t)
                                    <option value="{{$t->id}}/{{$t->capacity}}">
                                        {{$t->name}}
                                    </option>
                                    @endforeach @endif
                                </select>
                            </div>
                            <div
                                class="alert alert-warning d-flex align-items-center"
                            >
                                <label for="" class=""
                                    >Total Pembayaran : Rp<label
                                        id="total_amount"
                                        >0</label
                                    ></label
                                >
                            </div>
                        </div>
                        <div class="col-md-8">
                            <label for="exampleInputEmail1">Hidangan:</label>
                            <div class="repeater">
                                <div data-repeater-list="meals">
                                    <div data-repeater-item>
                                        <div class="row mb-2">
                                            <div class="col-sm-6 mx-0">
                                                <select
                                                    onchange="addStock(this)"
                                                    name="product_id"
                                                    id="product_id"
                                                    class="form-control"
                                                >
                                                    <option value="">
                                                        -Pilih Hidangan-
                                                    </option>
                                                    @foreach($meal->data as $m)
                                                    <option
                                                        value="{{$m->id}}/{{$m->stock}}/{{$m->price}}"
                                                    >
                                                        {{$m->name}}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div
                                                class="col-sm-2 mx-0 qty_amount"
                                            >
                                                <input
                                                    onchange="editQty(this)"
                                                    data-price=""
                                                    min="1"
                                                    type="number"
                                                    name="qty"
                                                    id="qty"
                                                    class="form-control"
                                                />
                                            </div>
                                            <input
                                                data-repeater-delete
                                                type="button"
                                                class="btn btn-sm btn-danger"
                                                value="Hapus"
                                            />
                                        </div>
                                        <div class="row" style="display: none">
                                            <div class="col-sm-8 mb-1">
                                                <div
                                                    class="row d-flex align-items-center justify-content-end"
                                                >
                                                    <div
                                                        class="col-sm-3 mx-0 price_amount"
                                                    >
                                                        <input
                                                            min="1"
                                                            type="number"
                                                            name="price"
                                                            class="form-control price"
                                                            readonly
                                                        />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <input
                                    data-repeater-create
                                    type="button"
                                    class="btn btn-primary"
                                    value="Tambah"
                                />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-sm btn-secondary"
                        data-dismiss="modal"
                    >
                        Batal
                    </button>
                    <button
                        type="submit"
                        class="btn btn-sm btn-primary submit-button"
                    >
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div
    class="modal fade"
    id="detailModal"
    tabindex="-1"
    aria-labelledby="detailModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel"></h5>
                <button
                    type="button"
                    class="close"
                    data-dismiss="modal"
                    aria-label="Close"
                >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="detailTransaction"></form>
            </div>
            <div
                class="modal-footer footer-detail d-flex justify-content-between"
            ></div>
        </div>
    </div>
</div>

<script src="{{ asset('adminlte/plugins/moment/moment.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
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
<script src="{{
        asset('adminlte/plugins/daterangepicker/daterangepicker.js')
    }}"></script>
<script src="{{
        asset('adminlte/plugins/select2/js/select2.full.min.js')
    }}"></script>
<script
    type="text/javascript"
    src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="SB-Mid-client-gQUBKrZAWIA4Vj9q"
></script>
<script>
    const search = $("#search");
    let startDate = "";
    let endDate = "";
    const transactionForm = document.getElementById("transactionForm");
    const totalAmount = document.querySelector("#total_amount");
    document.querySelector("#qty").setAttribute("disabled", true);
    document.querySelector(".price").setAttribute("disabled", true);

    search.unbind();

    $(function () {
        tabel = $("#transaction").DataTable({
            searching: false,
            lengthChange: false,
            processing: true,
            serverSide: true,
            ordering: false,
            ajax: {
                url: "{{route('transaction.get_transaction')}}",
                data: function (d) {
                    d.search = document.querySelector("#search").value;
                    d.start_date = startDate;
                    d.end_date = endDate;
                    d.status_payment =
                        document.querySelector("#status_payment").value;
                },
            },
            columns: [
                {
                    data: "order_number",
                    name: "order_number",
                },
                {
                    data: "customer.name",
                },
                {
                    data: "order_date",
                    name: "order_date",
                },
                {
                    data: "status_payment",
                    name: "status_payment",
                },
                {
                    data: "action",
                    name: "action",
                },
            ],
            order: [[2, "desc"]],
        });

        $(".select2").select2({
            theme: "bootstrap4",
        });

        $(".new-customer").hide();

        $("#daterange-btn").daterangepicker(
            {
                ranges: {
                    "Hari Ini": [moment(), moment()],
                    "Bulan Ini": [
                        moment().startOf("month"),
                        moment().endOf("month"),
                    ],
                    "Tahun Ini": [
                        moment().startOf("year"),
                        moment().endOf("year"),
                    ],
                },
            },
            function (start, end) {
                $("#reportrange span").html(
                    start.format("MMMM D, YYYY") +
                        " - " +
                        end.format("MMMM D, YYYY")
                );
            }
        );

        $("#reservationdate").datepicker({
            format: "dd-mm-yyyy",
            autoclose: true,
            todayHighlight: true,
            toggleActive: true,
            startDate: new Date(),
        });

        $(".repeater").repeater({
            show: function () {
                $(this).slideDown();
            },

            hide: function (deleteElement) {
                if (confirm("Are you sure you want to delete this element?")) {
                    const selectorAmountQty =
                        document.querySelectorAll(".qty_amount");
                    const selectorAmountPrice =
                        document.querySelectorAll(".price_amount");
                    const selectorQty = document.querySelectorAll("#qty");
                    const selectorPrice = document.querySelectorAll(".price");
                    let price =
                        selectorQty[selectorQty.length - 1].dataset.price;
                    let total = 0;
                    total =
                        parseInt(totalAmount.innerHTML) -
                        parseInt(price) *
                            parseInt(
                                selectorQty[selectorAmountQty.length - 1].value
                            );

                    totalAmount.innerHTML = total < 0 ? 0 : total;
                    $(this).slideUp(deleteElement);
                }
            },
        });
    });

    addTransaction = () => {
        $(".modal-title").html("Tambah Transaksi");
        $("#exampleModal").modal("show");
    };

    inputNewCustomer = () => {
        $(".new-customer").show();
    };

    $("#exampleModal").on("hidden.bs.modal", () => {
        $("#reservationdate").val("");
        transactionForm.reset();
        $(".new-customer").hide();
    });

    transactionForm.addEventListener("submit", (e) => {
        e.preventDefault();
        formData = new FormData(e.currentTarget);

        requestJs('{{route("transaction.store")}}', "POST", formData).then(
            (res) => {
                console.log(res);
                if (res.status && res.code == 200) {
                    $("#exampleModal").modal("hide");

                    window.snap.pay(res.data.snap_token, {
                        onSuccess: function (result) {
                            location.reload();
                        },
                        onPending: function (result) {
                            alert("wating your payment!");
                            console.log(result);
                        },
                        onError: function (result) {
                            alert("payment failed!");
                            console.log(result);
                        },
                        onClose: function () {
                            alert(
                                "you closed the popup without finishing the payment"
                            );
                        },
                    });
                    // location.reload();
                } else if (res.status == false && res.code == 404) {
                    console.log(res);
                } else {
                    Object.keys(res.message).map((key) => {
                        toastrNotif("", res.message[key], "error");
                    });
                }
            }
        );
    });

    function addStock(id) {
        const selectorAmountQty = document.querySelectorAll(".qty_amount");
        const selectorAmountPrice = document.querySelectorAll(".price_amount");
        const selectorQty = document.querySelectorAll("#qty");
        const selectorPrice = document.querySelectorAll(".price");

        if (id.value != "") {
            const data = id.value.split("/");

            let meal_id = data[0];
            let qty = data[1];
            let price = data[2];
            selectorQty[selectorAmountQty.length - 1].dataset.price = price;
            selectorPrice[selectorAmountPrice.length - 1].value = price;
            selectorQty[selectorAmountQty.length - 1].value = qty;
            selectorQty[selectorAmountQty.length - 1].value = 1;
            selectorQty[selectorAmountQty.length - 1].max = qty;

            selectorQty[selectorAmountQty.length - 1].removeAttribute(
                "disabled"
            );

            let total = 0;
            for (let i = 0; i < selectorQty.length; i++) {
                total += parseInt(selectorPrice[i].value);
            }
            totalAmount.innerHTML = total;
        } else {
            console.log("tidakkkkkk");
            let price = selectorQty[selectorAmountQty.length - 1].dataset.price;

            selectorPrice[selectorAmountPrice.length - 1].value =
                price * qty.value;
            totalAmount.innerHTML = price * qty.value;
            selectorPrice[selectorAmountPrice.length - 1].value = "";
            selectorQty[selectorAmountQty.length - 1].setAttribute(
                "disabled",
                true
            );
        }
    }

    function editQty(qty) {
        const selectorAmountQty = document.querySelectorAll(".qty_amount");
        const selectorAmountPrice = document.querySelectorAll(".price_amount");
        const selectorQty = document.querySelectorAll("#qty");
        const selectorPrice = document.querySelectorAll(".price");
        let price = selectorQty[selectorAmountQty.length - 1].dataset.price;
        let total = 0;

        selectorPrice[selectorAmountPrice.length - 1].value = price * qty.value;

        for (let i = 0; i < selectorQty.length; i++) {
            total += parseInt(selectorPrice[i].value);
        }
        totalAmount.innerHTML = total;
    }

    function pay(order_number) {
        const formData = new FormData();
        formData.append("_method", "PUT");
        formData.append("_token", "{{ csrf_token() }}");
        requestJs(
            `{{route('transaction.update', '')}}/${order_number}`,
            "POST",
            formData
        ).then((res) => {
            // console.log(res);
            // return;
            if (res.code == 200 && res.status) {
                window.snap.pay(res.data.snap_token, {
                    onSuccess: function (result) {
                        tabel.draw();
                    },
                    onPending: function (result) {
                        alert("wating your payment!");
                        console.log(result);
                    },
                    onError: function (result) {
                        alert("payment failed!");
                        console.log(result);
                    },
                    onClose: function () {
                        alert(
                            "you closed the popup without finishing the payment"
                        );
                    },
                });
            }
        });
    }

    function detailOrder(order_number) {
        requestJs(
            `{{route("transaction.detail", '')}}/${order_number}`,
            "GET"
        ).then((res) => {
            document.getElementById(
                "detailTransaction"
            ).innerHTML = `<div class="form-group row my-0">
                         <label for="staticEmail" class="col-sm-3 col-form-label"
                             >No. Order</label
                         >
                         <div class="col-sm-8 order_number">: ${
                             res.data.order_number
                         }</div>
                     </div>
                     <div class="form-group row my-0">
                         <label
                             for="inputPassword"
                             class="col-sm-3 col-form-label"
                             >Tanggal</label
                         >
                         <div class="col-sm-8 order_date">: ${
                             res.data.order_date
                         }</div>
                     </div>
                     <div class="form-group row my-0">
                         <label for="staticEmail" class="col-sm-3 col-form-label"
                             >Status Pembayaran</label
                         >
                         <div class="col-sm-8 status">: ${
                             res.data.status == "paid"
                                 ? "Lunas"
                                 : res.data.status == "down_payment"
                                 ? "DP"
                                 : res.data.status == "done"
                                 ? "Selesai"
                                 : res.data.status == "initial"
                                 ? "Inisialisasi"
                                 : "Dibatalkan"
                         }</div>
                     </div>
                     <div class="form-group row my-0">
                         <label
                             for="inputPassword"
                             class="col-sm-3 col-form-label"
                             >Telah Bayar</label
                         >
                         <div class="col-sm-8 down_payment">: ${rupiahFormat(
                             res.data.down_payment
                         )}</div>
                     </div>
                     <div class="form-group row my-0">
                         <label for="staticEmail" class="col-sm-3 col-form-label"
                             >Nama Customer</label
                         >
                         <div class="col-sm-8 customer_name">: ${
                             res.data.customer.name
                         }</div>
                     </div>
                     <div class="form-group row my-0">
                         <label
                             for="inputPassword"
                             class="col-sm-3 col-form-label"
                             >No. HP</label
                         >
                         <div class="col-sm-8 phone_number">: ${
                             res.data.customer.phone ?? "-"
                         }</div>
                     </div>
                     <div class="form-group row my-0">
                         <label
                             for="inputPassword"
                             class="col-sm-3 col-form-label"
                             >Meja</label
                         >
                         <div class="col-sm-8 list_table"></div>
                     </div>
                     <hr />
                     <div class="detail_order_product"></div>
                   `;
            let listTable = ": ";
            let detailOrderProduct = "";
            let amountPrice = 0;
            res.data.order_detail.forEach((item) => {
                if (item.product.type == "table") {
                    listTable += `<span class="badge badge-primary mr-1">${item.product.name}</span>`;
                }
            });
            document.querySelector(".list_table").innerHTML = listTable;
            res.data?.order_detail.forEach((element) => {
                product = element.product;
                if (product.type == "meal") {
                    const html = ` <div class="row d-flex align-items-center mb-2">
                 <div class="col-sm-1">
                     <img
                         src="${
                             product.photo != ""
                                 ? product.photo
                                 : "{{asset('images/lunch.png')}}"
                         }"
                         class="img-thumbnail"
                         alt="${product.name}"
                     />
                 </div>
                 <div class="col-sm-3 py-0">
                     <div class="col my-0">
                         <label class="my-0" for="" style="font-size: large"
                             >${product.name}</label
                         >
                     </div>

                    <div class="col">
                     <div class="row">
                         <div class="col-sm-8 my-0">
                             <label class="my-0" for="" style="color:grey;font-size:12px;font-weight:font-weight-normal" class="font-weight-normal"
                             >${
                                 element.qty + "x" + rupiahFormat(product.price)
                             }</label
                             >
                         </div>
                         <div class="col-sm-3 my-0">
                             <label class="my-0" for="" style="color:grey;font-size:12px;font-weight:font-weight-normal" class="font-weight-normal"
                             >${rupiahFormat(
                                 element.qty * product.price
                             )}</label
                             >
                             </div>
                         </div>
                     </div>
                    </div>
                 </div>
             </div>`;
                    detailOrderProduct += html;
                    amountPrice += element.qty * product.price;
                }
            });
            document.querySelector(".detail_order_product").innerHTML =
                detailOrderProduct;
            document.querySelector(
                ".footer-detail"
            ).innerHTML = `<h5 class="font-weight-bold">${
                res.data.status == "down_payment" ||
                res.data.status == "initial"
                    ? `Sisa yang harus dibayar : ${rupiahFormat(
                          amountPrice - res.data.down_payment
                      )} 
                     
                      `
                    : `Total Pembayaran : ${rupiahFormat(amountPrice)} </h5>`
            } `;
            if (res.status && res.code == 200) {
                $(".modal-title").html("Detail Transaksi");
                $("#detailModal").modal("show");
            } else if (res.status == false && res.code == 404) {
                console.log(res);
            } else {
                Object.keys(res.message).map((key) => {
                    toastrNotif("", res.message[key], "error");
                });
            }
        });
    }

    search.bind("input", function (e) {
        if (this.value.length >= 3) {
            tabel.search(this.value).draw();
        }
        if (this.value == "") {
            tabel.search("").draw();
        }
        return;
    });

    $("#daterange-btn").on("apply.daterangepicker", function (ev, picker) {
        $("#daterange-btn").html(
            `${picker.chosenLabel}  <i class="fas fa-caret-down"></i>`
        );
        startDate = picker.startDate.format("YYYY-MM-DD");
        endDate = picker.endDate.format("YYYY-MM-DD");
        tabel.draw();
    });

    document
        .querySelector("#status_payment")
        .addEventListener("change", function (e) {
            tabel.draw();
        });



</script>
@endsection
