@extends('layout.default') @section('content')
<link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css"
/>
<link
    rel="stylesheet"
    href="{{ asset('adminlte/plugins/daterangepicker/daterangepicker.css') }}"
/>
<div class="col-md-12">
    <div class="row">
        @foreach($most_booking as $key => $m)
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="row d-flex align-items-center">
                        <div class="col-sm-3">
                            <i class="fas fa-3x fa-trophy"></i>
                        </div>
                        <div class="col-sm-9">
                            <h5>{{ $m->customer_name }}</h5>
                            <h5>
                                Total Booking :
                                {{ $m->total_booking }}
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="col d-flex justify-content-between">
                        <div class="col-0">
                            <h4>Grafik Jumlah Customer</h4>
                        </div>
                        <div class="col-0">
                            <div class="input-group">
                                <button
                                    type="button"
                                    class="btn btn-default float-right"
                                    id="daterange-btn-customer"
                                >
                                    Filter Waktu
                                    <i class="fas fa-caret-down"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <canvas id="customer-chart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="col d-flex justify-content-between">
                        <div class="col-0">
                            <h4>Grafik Keuangan</h4>
                        </div>
                        <div class="col-0">
                            <div class="input-group">
                                <button
                                    type="button"
                                    class="btn btn-default float-right"
                                    id="daterange-btn-income"
                                >
                                    Filter Waktu
                                    <i class="fas fa-caret-down"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <canvas id="income-chart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('adminlte/plugins/chart.js/Chart.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/moment/moment.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script src="{{
        asset('adminlte/plugins/daterangepicker/daterangepicker.js')
    }}"></script>

<script>
    const incomeCanvas = document.getElementById("income-chart");
    const customerCanvas = document.getElementById("customer-chart");

    incomeCanvas.height = "80";
    customerCanvas.height = "80";

    $(function () {
        $("#daterange-btn-income").daterangepicker(
            {
                ranges: {
                    // "Delete Filter": [],
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

        $("#daterange-btn-customer").daterangepicker(
            {
                ranges: {
                    "Delete Filter": [],
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

        fetchRequest();
    });

    function resetDateRangePicker() {
        $("#daterange-btn-income").val("");
        $("#daterange-btn-customer").val("");
    }

    $("#daterange-btn-income").on(
        "apply.daterangepicker",
        function (ev, picker) {
            var label = $("#daterange-btn-income").data(
                "daterangepicker"
            ).chosenLabel;

            fetchRequest(
                picker.startDate.format("YYYY-MM-DD"),
                picker.endDate.format("YYYY-MM-DD"),
                "income"
            );

            $("#daterange-btn-income").html(
                label + ' <i class="fas fa-caret-down"></i>'
            );
        }
    );
    $("#daterange-btn-customer").on(
        "apply.daterangepicker",
        function (ev, picker) {
            fetchRequest(
                picker.startDate.format("YYYY-MM-DD"),
                picker.endDate.format("YYYY-MM-DD"),
                "customer"
            );
        }
    );

    fetchRequest = (startDate = "", endDate = "", from = "") => {
        let urlIncome = "{{route('report.income_chart')}}";
        let urlCustomer = "{{route('report.customer_chart')}}";
        let param = {
            start_date: startDate,
            end_date: endDate,
        };
        urlIncome += "?" + $.param(param);

        if (from == "") {
            fetchIncome(urlIncome);
            fetchCustomer(urlCustomer);
        }
        if (from == "income") {
            fetchIncome(urlIncome);
        }
        if (from == "customer") {
            fetchCustomer(urlCustomer);
        }
    };

    fetchIncome = (url) => {
        url = url;
        requestJs(url, "GET").then((res) => {
            if (res.code == 200) {
                const myChart = new Chart(incomeCanvas, {
                    type: "line",

                    data: {
                        labels: res.data.label,
                        datasets: [
                            {
                                data: res.data.data,
                                backgroundColor: [
                                    "rgba(255, 255, 255, 0.0)",
                                    "rgba(255, 255, 255, 0.0)",
                                ],
                                borderColor: [
                                    "rgba(0, 0, 255, 1)",
                                    "rgba(0, 0, 255, 1)",
                                ],
                                borderWidth: 2,
                            },
                        ],
                    },
                    options: {
                        legend: {
                            display: false,
                        },
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                            },
                        },
                    },
                });
            } else {
                alert(res.message);
            }
        });
    };
    fetchCustomer = (url) => {
        requestJs(url, "GET").then((res) => {
            if (res.code == 200) {
                const customerChart = new Chart(customerCanvas, {
                    type: "line",
                    data: {
                        labels: res.data.label,
                        datasets: [
                            {
                                data: res.data.data,
                                backgroundColor: [
                                    "rgba(255, 255, 255, 0.0)",
                                    "rgba(255, 255, 255, 0.0)",
                                ],
                                borderColor: [
                                    "rgba(255, 99, 132, 1)",
                                    "rgba(54, 162, 235, 1)",
                                ],
                                borderWidth: 2,
                            },
                        ],
                    },
                    options: {
                        legend: {
                            display: false,
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                            },
                        },
                    },
                });
            } else {
                alert(res.message);
            }
        });
    };
</script>
@endsection
