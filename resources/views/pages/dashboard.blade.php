@extends('layout.default') @section('content')

<div class="col-md-12">
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3 class="mb-0">{{ $count->order }}</h3>
                    <h4>Order</h4>
                </div>
                <div class="icon">
                    <i class="fas fa-shopping-bag"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3 class="mb-0">{{$count->order}}</h3>
                    <h4>Pelanggan</h4>
                </div>
                <div class="icon">
                    <i class="fas fa-user"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-orange">
                <div class="inner">
                    <h3 class="mb-0 text-white">{{$count->meal}}</h3>
                    <h4 class="text-white">Hidangan Terjual</h4>
                </div>
                <div class="icon">
                    <i class="fas fa-bacon"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="col">
                        <div class="row d-flex justify-content-between">
                            <h5>Grafik Order</h5>
                            <h5>Per : Hari Ini</h5>
                        </div>
                        <canvas id="orderChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="col">
                        <div class="row d-flex justify-content-between">
                            <h5>Grafik Transaksi</h5>
                            <h5>Per : Hari Ini</h5>
                        </div>
                        <canvas id="transactionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="col">
                        <div class="row d-flex justify-content-between">
                            <h5>Grafik Customer</h5>
                            <h5>Per : Hari Ini</h5>
                        </div>
                        <canvas id="customerChart"> </canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('adminlte/plugins/chart.js/Chart.min.js') }}"></script>
<script>
    const orderChartCanvas = document.getElementById("orderChart");
    const transactionChartCanvas = document.getElementById("transactionChart");
    const customerChartCanvas = document.getElementById("customerChart");
    orderChartCanvas.height = "70";
    transactionChartCanvas.height = "70";
    customerChartCanvas.height = "70";

    window.addEventListener("DOMContentLoaded", () => {
        const urlOrder = "{{ route('dashboard.get_chart_order') }}";
        const urlIncome = "{{ route('dashboard.get_chart_income') }}";
        const urlCustomer = "{{ route('dashboard.get_chart_customer') }}";
        getOrderChart(urlOrder);
        getTransactionChart(urlIncome);
        getCustomerChart(urlCustomer);
    });

    const changePage = (page) => {
        const param = page.split("=")[1];

        const url = "{{ route('dashboard.index') }}" + "?page=" + param;
        window.location.href = url;
    };

    const getOrderChart = (url) => {
        requestJs(url, "GET").then((res) => {
            const label = res.data.label;
            const data = res.data.data;

            if (res.code == 200) {
                const order = new Chart(orderChartCanvas, {
                    type: "line",
                    data: {
                        labels: label,
                        datasets: [
                            {
                                data: data,
                                backgroundColor: [
                                    "rgba(255, 99, 132, 0.2)",
                                    "rgba(54, 162, 235, 0.2)",
                                    "rgba(255, 206, 86, 0.2)",
                                    "rgba(75, 192, 192, 0.2)",
                                    "rgba(153, 102, 255, 0.2)",
                                    "rgba(255, 159, 64, 0.2)",
                                ],
                                borderColor: [
                                    "rgba(255, 99, 132, 1)",
                                    "rgba(54, 162, 235, 1)",
                                    "rgba(255, 206, 86, 1)",
                                    "rgba(75, 192, 192, 1)",
                                    "rgba(153, 102, 255, 1)",
                                    "rgba(255, 159, 64, 1)",
                                ],
                                borderWidth: 1,
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
                ntfToast(res.message, "error");
            }
        });
    };

    const getTransactionChart = (url) => {
        requestJs(url, "GET").then((res) => {
            const label = res.data.label;
            const data = res.data.data;

            if (res.code == 200) {
                const transaction = new Chart(transactionChartCanvas, {
                    type: "line",
                    data: {
                        labels: label,
                        datasets: [
                            {
                                data: data,
                                backgroundColor: [
                                    "rgba(255, 99, 132, 0.2)",
                                    "rgba(54, 162, 235, 0.2)",
                                    "rgba(255, 206, 86, 0.2)",
                                    "rgba(75, 192, 192, 0.2)",
                                    "rgba(153, 102, 255, 0.2)",
                                    "rgba(255, 159, 64, 0.2)",
                                ],
                                borderColor: [
                                    "rgba(255, 99, 132, 1)",
                                    "rgba(54, 162, 235, 1)",
                                    "rgba(255, 206, 86, 1)",
                                    "rgba(75, 192, 192, 1)",
                                    "rgba(153, 102, 255, 1)",
                                    "rgba(255, 159, 64, 1)",
                                ],
                                borderWidth: 1,
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
                ntfToast(res.message, "error");
            }
        });
    };

    const getCustomerChart = (url) => {
        requestJs(url, "GET").then((res) => {
            const label = res.data.label;
            const data = res.data.data;

            if (res.code == 200) {
                const customer = new Chart(customerChartCanvas, {
                    type: "line",
                    data: {
                        labels: label,
                        datasets: [
                            {
                                data: data,
                                backgroundColor: [
                                    "rgba(255, 99, 132, 0.2)",
                                    "rgba(54, 162, 235, 0.2)",
                                    "rgba(255, 206, 86, 0.2)",
                                    "rgba(75, 192, 192, 0.2)",
                                    "rgba(153, 102, 255, 0.2)",
                                    "rgba(255, 159, 64, 0.2)",
                                ],
                                borderColor: [
                                    "rgba(255, 99, 132, 1)",
                                    "rgba(54, 162, 235, 1)",
                                    "rgba(255, 206, 86, 1)",
                                    "rgba(75, 192, 192, 1)",
                                    "rgba(153, 102, 255, 1)",
                                    "rgba(255, 159, 64, 1)",
                                ],
                                borderWidth: 1,
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
                ntfToast(res.message, "error");
            }
        });
    };
</script>
@endsection
