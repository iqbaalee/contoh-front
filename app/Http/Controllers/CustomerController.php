<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    public function index()
    {
        $customer = json_decode($this->apiService->restApi('get', 'customer'));

        return view('pages.customer.index', ['title' => 'Customer', 'customer' => $customer->data]);
    }

    public function store(Request $request)
    {
        try {
            $data = $request->all();
            if ($request->getContentType() == 'form') {
                $validator = $this->apiService->validateInputManual($data, [
                    'name' => 'required|max:50',
                    'email' => 'required|email|max:50',
                    'phone' => 'required|max:14',
                    'address' => 'required|max:255',
                ], [
                    'name.required' => 'Nama tidak boleh kosong',
                    'name.max' => 'Nama tidak boleh lebih dari 50 karakter',
                    'email.required' => 'Email tidak boleh kosong',
                    'email.email' => 'Email tidak valid',
                    'email.max' => 'Email tidak boleh lebih dari 50 karakter',
                    'phone.required' => 'Nomor telepon tidak boleh kosong',
                    'phone.max' => 'Nomor telepon tidak boleh lebih dari 14 karakter',
                    'address.required' => 'Alamat tidak boleh kosong',
                    'address.max' => 'Alamat tidak boleh lebih dari 255 karakter',
                ]);
                if ($validator != null) {
                    return ['code' => 400, 'status' => 'error', 'message' => $validator];
                }

                $response = $this->apiService->restApi('post', 'customer', $data);
                return $response;
            }
        } catch (\Exception $e) {
            return ['code' => 400, 'status' => 'error', 'message' => $e];
        }
    }

    public function detail($id)
    {
        try {
            $response = $this->apiService->restApi('get', 'customer/' . $id);
            return $response;
        } catch (\Exception $e) {
            return ['code' => 400, 'status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function delete(Request $request)
    {
        try {

            $response = $this->apiService->restApi('delete', 'customer/' . $request->id);
            return $response;
        } catch (\Exception $e) {
            return ['code' => 400, 'status' => 'error', 'message' => $e->getMessage()];
        }
    }
}
