<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class MealController extends Controller
{

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }
    public function ajaxGetMeal(Request $request)
    {

        try {

            $listMeal = json_decode($this->apiService->restApi('get', 'meal', [
                'page' => $request->page ?? 1,
            ]));

            return $listMeal;
        } catch (\Exception $e) {
            return ['code' => 400, 'status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function index()
    {

        return view('pages.meal.index', ['title' => 'Hidangan']);
    }

    public function store(Request $request)
    {

        try {
            $data = $request->only('name', 'price', 'description', 'stock', 'photo');
            $validator = $this->apiService->validateInputManual($data, [
                'name' => 'required|max:50',
                'description' => 'max:255',
                'stock' => 'required|numeric',
                'price' => 'required|not_in:0',
                'photo' => 'required|string',
            ], [
                'name.required' => 'Judul harus diisi',
                'name.max' => 'Judul tidak boleh lebih dari 50 karakter',
                'description.max' => 'Deskripsi tidak boleh lebih dari 255 karakter',
                'price.required' => 'Harga harus diisi',
                'price.not_in' => 'Harga tidak boleh 0',
                'photo.required' => 'Gambar harus diisi',
                'photo.string' => 'Gambar harus berupa string',
                'stock.required' => 'Stok harus diisi',
                'stock.numeric' => 'Stok harus berupa angka',
            ]);
            if ($validator != null) {
                return ['code' => 400, 'status' => 'error', 'message' => $validator];
            }
            $response = $this->apiService->restApi('post', 'meal', $data);
            return $response;
        } catch (\Exception $e) {
            return ['code' => 400, 'status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function detail($id)
    {
        try {

            $response = $this->apiService->restApi('get', 'meal/' . $id);
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
                'stock' => 'required|numeric',
                'price' => 'numeric|required|not_in:0',
                'photo' => 'string',
            ], [
                'name.required' => 'Judul harus diisi',
                'name.max' => 'Judul tidak boleh lebih dari 50 karakter',
                'description.max' => 'Deskripsi tidak boleh lebih dari 255 karakter',
                'time.numeric' => 'Waktu harus berupa angka',
                'time.required' => 'Waktu harus diisi',
                'price.numeric' => 'Harga harus berupa angka',
                'price.required' => 'Harga harus diisi',
                'price.not_in' => 'Harga tidak boleh 0',
                'photo.string' => 'Gambar harus berupa string',
            ]);
            if ($validator != null) {
                return ['code' => 400, 'status' => 'error', 'message' => $validator];
            }
            $response = $this->apiService->restApi('put', 'meal/' . $request->id, $data);

            return $response;
        } catch (\Exception $e) {
            return ['code' => 400, 'status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function delete(Request $request)
    {

        try {
            $response = $this->apiService->restApi('delete', 'meal/' . $request->id);
            return $response;
        } catch (\Exception $e) {
            return ['code' => 400, 'status' => 'error', 'message' => $e->getMessage()];
        }
    }
}
