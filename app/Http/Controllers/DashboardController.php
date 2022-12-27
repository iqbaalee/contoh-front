<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    public function index(Request $request)
    {

        $resOrder = json_decode($this->apiService->restApi('get', 'order/count'))->data;
        return view('pages.dashboard', ['title' => 'Dashboard', 'count' => $resOrder]);
    }

    public function getChartOrder(Request $request)
    {
        $res = json_decode($this->apiService->restApi('get', 'report/order'));
        return $res;
    }

    public function getChartIncome(Request $request)
    {
        $res = json_decode($this->apiService->restApi('get', 'report/income'));
        return $res;
    }

    public function getChartCustomer(Request $request)
    {
        $res = json_decode($this->apiService->restApi('get', 'report/customer'));
        return $res;
    }

    public function getChartTransaction()
    {
        $dataChart = $this->apiService->restApi('get', 'dashboard/chart');

        return $dataChart;
    }
}
