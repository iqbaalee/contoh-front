<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;


class AuthController extends Controller
{
    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }
    public function register()
    {
        return view('auth.register');
    }

    public function login()
    {

        return view('auth.login', ['title' => 'Login']);
    }

    public function login_action(Request $request)
    {
        try {
            $data = $request->all();

            $this->apiService->validateInput($data, [
                'email' => 'required|email',
                'password' => 'required',
            ], [
                'email.required' => 'Email tidak boleh kosong',
                'email.email' => 'Gunakan email yang valid',
                'password.required' => 'Password tidak boleh kosong',
            ]);

            $response = json_decode($this->apiService->restApi('post', 'login', $data));

            if ($response->code == 200 && $response->status) {
                $data = to_route('auth.get_profile')->withCookie(cookie('X-FOOTSAL', $response->data->token));

                return $data;
            } else {
                return redirect()->route('auth.login')->with('error', $response->msg);
            }
        } catch (\Exception $e) {
            return redirect()->route('auth.login')->with('error', $e->getMessage());
        }
    }

    public function profile()
    {
        $profile = base64_decode(Cookie::get('X-PERSONAL'));
        $profile = json_decode($profile);
        $role = json_decode($this->apiService->restApi('get', 'role/'));

        return view('auth.profile', ['title' => 'Profil', 'profile' => $profile, 'role' => $role->data]);
    }
    public function changePassword()
    {
        $profile = base64_decode(Cookie::get('X-PERSONAL'));
        $profile = json_decode($profile);
        $role = json_decode($this->apiService->restApi('get', 'role/'));

        return view('auth.change-password', ['title' => 'Ubah Password', 'profile' => $profile, 'role' => $role->data]);
    }

    public function getProfile()
    {
        try {
            $response = json_decode($this->apiService->restApi('get', 'profile'));

            return redirect()->route('dashboard.index')->withCookie(cookie('X-PERSONAL', base64_encode(json_encode($response->data))));
        } catch (\Throwable $th) {
            return redirect()->route('auth.login')->with('error', $th->getMessage());
        }
    }

    public function updateProfile(Request $request)
    {
        try {
            $data = $request->all();

            $this->apiService->validateInput($data, [
                'name' => 'required|alpha|max:50',
                'email' => 'required|email',
                'role_id' => 'required',
            ], [
                'name.required' => 'Nama tidak boleh kosong',
                'name.alpha' => 'Nama hanya boleh berisi huruf',
                'name.max' => 'Nama tidak boleh lebih dari 50 karakter',
                'email.required' => 'Email tidak boleh kosong',
                'email.email' => 'Gunakan email yang valid',
                'role_id.required' => 'Hak Akses tidak boleh kosong',

            ]);

            $response = json_decode($this->apiService->restApi('put', 'profile', $data));
            if ($response->code == 200 && $response->status) {
                $updateProfile = json_decode($this->apiService->restApi('get', 'profile'));
                return redirect()->back()->withCookie(cookie('X-PERSONAL', base64_encode(json_encode($updateProfile->data))));
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function updatePassword(Request $request)
    {
        try {
            $data = $request->all();

            $this->apiService->validateInput($data, [
                'old_password' => 'required',
                'new_password' => 'required',
                'conf_password' => 'required|same:new_password',
            ], [
                'old_password.required' => 'Password lama tidak boleh kosong',
                'new_password.required' => 'Password baru tidak boleh kosong',
                'conf_password.required' => 'Konfirmasi password tidak boleh kosong',
                'conf_password.matches' => 'Konfirmasi password tidak sama dengan password baru',
            ]);
            $data = $request->only('old_password', 'new_password');
            $response = json_decode($this->apiService->restApi('put', 'profile/password', $data));

            if ($response->code == 200 && $response->status) {
                $route = to_route('auth.get_profile')->withCookie(cookie('X-FOOTSAL', $response->data->token));
                return $route;
            } else {
                return redirect()->back()->with('error', $response->msg);
            }
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function logout()
    {
        try {
            $response = json_decode($this->apiService->restApi('post', 'logout'));

            if ($response->code == 200) {

                return redirect()->route('auth.login')->withCookie(Cookie::forget('X-FOOTSAL'))->withCookie(Cookie::forget('X-PERSONAL'));
            }
        } catch (\Throwable $th) {
            return redirect()->route('auth.login')->with('error', $th->getMessage());
        }
    }
}
