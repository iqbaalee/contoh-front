<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ApiService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TableController extends Controller
{
    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    public function ajaxGetTable(Request $request)
    {

        try {

            if (!empty($request->day) || !empty($request->month) || !empty($request->year)) {

                if (!empty($request->day) && !empty($request->month) && !empty($request->year)) {
                    $filter = $request->year . '-' . $request->month . '-' . $request->day;
                } else if (!empty($request->day) && !empty($request->month)) {
                    $filter = $request->month . '-' . $request->day;
                } else if (!empty($request->month) && !empty($request->year)) {
                    $filter = $request->year . '-' . $request->month;
                } else if (!empty($request->day) && !empty($request->year)) {
                    return redirect()->route('table.index')->with('error', 'Bulan jangan dikosongkan');
                } else if (!empty($request->day)) {
                    $filter = date('Y') . '-' . date('m') . '-' . $request->day;
                } else if (!empty($request->month)) {
                    $filter = $request->month;
                } else if (!empty($request->year)) {
                    $filter = $request->year;
                }

                $listHour = json_decode($this->apiService->restApi('get', 'table', [
                    'filter' => $filter
                ]));
            } else {
                $listHour = json_decode($this->apiService->restApi('get', 'table'));
            }

            return $listHour;
        } catch (\Exception $e) {
            return ['code' => 400, 'status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function index()
    {

        $listMonth = [];
        for ($i = 1; $i <= 12; $i++) {
            $listMonth[] = Carbon::createFromDate(null, $i, null)->format('F');
        }

        $listYear = [];
        for ($i = Carbon::now()->year; $i <= Carbon::now()->year + 10; $i++) {
            $listYear[] = $i;
        }

        $carbon = Carbon::now()->format('m');
        $dateNow = Carbon::now()->month($carbon)->daysInMonth;

        return view('pages.table.index', ['title' => 'Meja', 'dateNow' => $dateNow, 'listYear' => $listYear, 'listMonth' => $listMonth]);
    }

    public function store(Request $request)
    {
        try {
            $data = $request->only('name', 'time', 'description', 'price');
            $validator = $this->apiService->validateInputManual($data, [
                'name' => 'required|max:50',
                'description' => 'max:255',
                'time' => 'numeric|required',
                'price' => 'numeric|required|not_in:0',
            ], [
                'name.required' => 'Judul harus diisi',
                'name.max' => 'Judul tidak boleh lebih dari 50 karakter',
                'description.max' => 'Deskripsi tidak boleh lebih dari 255 karakter',
                'time.numeric' => 'Waktu harus berupa angka',
                'time.required' => 'Waktu harus diisi',
                'price.numeric' => 'Harga harus berupa angka',
                'price.required' => 'Harga harus diisi',
                'price.not_in' => 'Harga tidak boleh 0',
            ]);
            if ($validator != null) {
                return ['code' => 400, 'status' => 'error', 'message' => $validator];
            }
            $response = $this->apiService->restApi('post', 'product', $data);
            return $response;
        } catch (\Exception $e) {
            return ['code' => 400, 'status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function detail($id, Request $request)
    {
        try {
            //carbon time asia jakarta

            if (!empty($request->order_id)) {
                $response = $this->apiService->restApi('get', 'table/' . $id, ['order_id' => $request->order_id]);
            } else {
                $response = $this->apiService->restApi('get', 'table/' . $id);
            }
            return $response;
        } catch (\Exception $e) {
            return ['code' => 400, 'status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function update(Request $request)
    {
        try {
            $data = $request->all();
            $validator = $this->apiService->validateInputManual($data, [
                'name' => 'required|max:50',
                'description' => 'max:255',
                'time' => 'numeric|required',
                'price' => 'numeric|required|not_in:0',
            ], [
                'name.required' => 'Judul harus diisi',
                'name.max' => 'Judul tidak boleh lebih dari 50 karakter',
                'description.max' => 'Deskripsi tidak boleh lebih dari 255 karakter',
                'time.numeric' => 'Waktu harus berupa angka',
                'time.required' => 'Waktu harus diisi',
                'price.numeric' => 'Harga harus berupa angka',
                'price.required' => 'Harga harus diisi',
                'price.not_in' => 'Harga tidak boleh 0',
            ]);
            if ($validator != null) {
                return ['code' => 400, 'status' => 'error', 'message' => $validator];
            }
            $response = $this->apiService->restApi('put', 'product/' . $request->id, $data);
            return $response;
        } catch (\Exception $e) {
            return ['code' => 400, 'status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function delete(Request $request)
    {
        try {
            dd($request->all());
            $response = $this->apiService->restApi('delete', 'product/' . $request->id);
            return $response;
        } catch (\Exception $e) {
            return ['code' => 400, 'status' => 'error', 'message' => $e->getMessage()];
        }
    }
}
