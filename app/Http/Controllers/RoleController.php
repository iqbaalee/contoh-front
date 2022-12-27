<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }


    public function index()
    {
        $menu = json_decode($this->apiService->restApi('get', 'menu'));
        $roles = json_decode($this->apiService->restApi('get', 'role'));

        return view('pages.role.index', ['title' => 'Hak Akses', 'menu' => $menu->data, 'roles' => $roles->data]);
    }

    public function store(Request $request)
    {
        try {
            $data = $request->all();

            if ($request->getContentType() == 'form') {
                $validator = $this->apiService->validateInputManual($data, [
                    'name' => 'alpha|max:50',
                    'permissions' => 'array|required',
                ], [
                    'name.alpha' => 'Nama hanya boleh berisi huruf',
                    'name.max' => 'Nama tidak boleh lebih dari 50 karakter',
                    'permissions.required' => 'Permission tidak boleh kosong',
                    'permissions.array' => 'Permission harus berupa array',
                ]);

                if ($validator != null) {

                    return ['code' => 400, 'status' => 'error', 'message' => $validator];
                }
            }

            if ($request->id != null) {
                $response = $this->apiService->restApi('put', 'role/' . $request->id, $data);
                return $response;
            } else {
                $response = $this->apiService->restApi('post', 'role', $data);
                return $response;
            }
        } catch (\Exception $e) {
            return ['code' => 400, 'status' => 'error', 'message' => $e];
        }
    }

    public function detail($id)
    {
        try {
            $response = $this->apiService->restApi('get', 'role/' . $id);
            return $response;
        } catch (\Exception $e) {
            return ['code' => 400, 'status' => 'error', 'message' => $e->getMessage()];
        }
    }
}
