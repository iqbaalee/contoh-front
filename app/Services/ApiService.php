<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class ApiService
{
    public function __construct(BaseService $baseService)
    {
        $this->baseService = $baseService;
    }

    public function restApi($method, $url, array $data = [], array $attach = [], $return = "body")
    {
        $method = strtolower($method);
        if ($method == 'get') {
            return $this->baseService->apiGet($url, $data, $return);
        } else if ($method == 'post') {
            return $this->baseService->apiPost($url, $data, $attach);
        } else if ($method == 'put') {
            return $this->baseService->apiPut($url, $data, $attach);
        } else if ($method == 'delete') {
            return $this->baseService->apiDelete($url);
        }
    }

    public function validateInput($data, array $field = [], array $msg = [])
    {
        return Validator::make($data, $field, $msg)->validate();
    }

    public function validateInputManual($data, array $field = [], array $msg = [])
    {
        $validator = Validator::make($data, $field, $msg);
        if ($validator->fails()) {
            return $validator->messages();
        } else {
            return null;
        }
    }
}
