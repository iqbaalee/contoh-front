<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }


    public function index()
    {
        $menu = json_decode($this->apiService->restApi('get', 'menu'));

        return view('pages.menu.index', ['title' => 'Menu', 'menu' => $menu->data]);
    }

    public function store(Request $request)
    {
        try {
            $data = $request->all();

            if ($request->getContentType() == 'form') {
                $validator = $this->apiService->validateInputManual($data, [
                    'name' => 'alpha|max:50',
                    'url' => 'max:50',
                ], [
                    'name.alpha' => 'Nama hanya boleh berisi huruf',
                    'name.max' => 'Nama tidak boleh lebih dari 50 karakter',
                    'url.max' => 'URL tidak boleh lebih dari 50 karakter',
                ]);

                if ($validator != null) {

                    return ['code' => 400, 'status' => 'error', 'message' => $validator];
                }
            }

            $response = $this->apiService->restApi('post', 'menu', $data);
            return $response;
        } catch (\Exception $e) {
            return ['code' => 400, 'status' => 'error', 'message' => $validator];
        }
    }

    public function detail($id)
    {
        try {
            $response = $this->apiService->restApi('get', 'menu/' . $id);
            return $response;
        } catch (\Exception $e) {
            return ['code' => 400, 'status' => 'error', 'message' => $e->getMessage()];
        }
    }
}
