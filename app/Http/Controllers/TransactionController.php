<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class TransactionController extends Controller
{
    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    public function getTransactionList(Request $request)
    {
        try {
            if ($request->ajax()) {

                if (!empty($request->start_date) && !empty($request->end_date)) {
                    $param = ['start_date' => $request->start_date, 'end_date' => $request->end_date];
                    $order = $this->apiService->restApi('get', 'order', $param, [], 'json');
                } else {
                    $order = $this->apiService->restApi('get', 'order', [], [], 'json');
                }

                return DataTables::of($order['data'])
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->get('search'))) {
                            $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                                if (Str::contains(Str::lower($row['order_number']), Str::lower($request->get('search')))) {
                                    return true;
                                } else if (Str::contains(Str::lower($row['customer']['id']), Str::lower($request->get('search')))) {
                                    return true;
                                }
                            });
                        }
                        if (!empty($request->get('status_payment'))) {
                            $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                                // dd($request->get('status_payment'));
                                // dd(Str::lower($row['status']));
                                if (Str::contains(Str::lower($row['status']), Str::lower($request->get('status_payment')))) {
                                    return true;
                                }
                            });
                        }
                    })
                    ->addColumn('action', function ($order) {
                        return $order['status'] != 'paid' && $order['status'] != 'cancel' ? ' <button
                    onclick="detailOrder(' . $order['order_number'] . ')"
                    class="btn btn-sm btn-primary"
                >
                    <i class="fas fa-eye"></i>
                </button>
                <button
                    onclick="pay(' . $order['order_number'] . ')"
                    class="btn btn-sm btn-danger"
                >
                   Bayar
                </button>' : '<button
                onclick="detailOrder(' . $order['order_number'] . ')"
                class="btn btn-sm btn-primary"
            >
                <i class="fas fa-eye"></i>
            </button>';
                    })
                    ->addColumn('status_payment', function ($order) {
                        return $order['status'] == 'paid' ? '<span class="badge badge-success">Paid</span>' : ($order['status'] == 'down_payment' ? '<span class="badge badge-warning">DP</span>' : ($order['status'] == 'initial' ? '<span class="badge badge-info">Initial</span>' : '<span class="badge badge-danger">Canceled</span>'));
                    })
                    ->setRowId(function ($order) {
                        return $order['order_number'];
                    })
                    ->rawColumns(['action', 'status_payment'])
                    ->make();
            }
        } catch (\Exception $e) {
            return ['code' => 400, 'status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function index()
    {

        $customer = json_decode($this->apiService->restApi('get', 'customer'));
        $table = json_decode($this->apiService->restApi('get', 'table'));
        $order = json_decode($this->apiService->restApi('get', 'order'));

        $meal = json_decode($this->apiService->restApi('get', 'meal'));
        return view('pages.transaction.index', ['title' => 'Transaksi', 'customer' => $customer->data, 'table' => $table->data, 'order' => $order->data, 'meal' => $meal->data]);
    }

    public function detail($id, Request $request)
    {
        try {
            $transaction = json_decode($this->apiService->restApi('get', 'order/' . $id));
            return $transaction;
        } catch (\Exception $e) {
            return ['code' => 400, 'status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function store(Request $request)
    {
        try {

            $request['order_date'] = date('Y-m-d', strtotime($request['order_date']));
            $data =  empty($request->id) ? $request->except('id') : $request->all();
            $data['payment_type'] = 'paid';

            foreach ($data['meals'] as $key => $d) {

                $newProductId = explode('/', $d['product_id']);
                $data['meals'][$key]['product_id'] = $newProductId[0];
            }

            foreach ($data['table_id'] as $key => $t) {
                $newTableId = explode('/', $t);
                $data['tables'][$key]['product_id'] = $newTableId[0];
                $data['tables'][$key]['capacity'] = $newTableId[0];
            }

            if ($request->getContentType() == 'form') {

                if ($data['new_customer'] != null && $data['customer_id'] != null) {
                    return ['code' => 404, 'status' => false, 'message' => 'Customer tidak boleh ada 2 yang di input'];
                }
                if ($data['new_customer'] == null && $data['customer_id'] == null) {
                    return ['code' => 404, 'status' => false, 'message' => 'Customer tidak boleh kosong'];
                }

                $validator = $this->apiService->validateInputManual($data, [
                    'customer_id' => 'nullable',
                    'new_customer' => 'max:50',
                    'table_id' => 'required|array',

                    'order_date' => 'required|date',
                    'meals' => 'required|array',
                    'meals.*.product_id' => 'required',
                    'meals.*.qty' => 'required|integer',
                ], [
                    'table_id.required' => 'Meja tidak boleh kosong',
                    'table_id.array' => 'ID Meja harus berupa array',
                    'new_customer.max' => 'Nama tidak boleh lebih dari 50 karakter',
                    'order_date.required' => 'Tanggal tidak boleh kosong',
                    'order_date.date' => 'Format tanggal tidak sesuai',
                    'meals.required' => 'Pesanan tidak boleh kosong',
                    'meals.array' => 'Pesanan harus berupa array',
                    'meals.*.product_id.required' => 'Produk tidak boleh kosong',
                    'meals.*.qty.required' => 'Jumlah Pesanan tidak boleh kosong',
                ]);

                if ($validator != null) {
                    return ['code' => 400, 'status' => 'error', 'message' => $validator];
                }

                $response = $this->apiService->restApi('post', 'order', $data);
                return $response;
            }
        } catch (\Exception $e) {
            return ['code' => 400, 'status' => 'error', 'message' => $e];
        }
    }

    public function update($number, Request $request)
    {
        $transaction = json_decode($this->apiService->restApi('put', 'order/' . $number));
        return $transaction;
    }

    public function delete(Request $request)
    {
    }
}
