<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    public function index(Request $request)
    {
        $response = json_decode($this->apiService->restApi('get', 'report/most_booking'));

        return view('pages.report.index', ['title' => 'Laporan', 'most_booking' => $response->data]);
    }

    public function incomeChart(Request $request)
    {
        if (!empty($request->start_date) && !empty($request->end_date)) {

            $param = ['start_date' => $request->start_date, 'end_date' => $request->end_date];
            $finance = json_decode($this->apiService->restApi('get', 'report/income', $param));
        } else {
            $finance = json_decode($this->apiService->restApi('get', 'report/income'));
        }

        return response()->json($finance);
    }

    public function customerChart(Request $request)
    {
        if (!empty($request->start_date) && !empty($request->end_date)) {

            $param = ['start_date' => $request->start_date, 'end_date' => $request->end_date];
            $finance = json_decode($this->apiService->restApi('get', 'report/customer', $param));
        } else {
            $finance = json_decode($this->apiService->restApi('get', 'report/customer'));
        }
        return response()->json($finance);
    }
}
