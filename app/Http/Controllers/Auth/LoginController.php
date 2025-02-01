<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\DateHelper;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\LoginDashboardRequest;

class LoginController extends Controller
{
    public function login(LoginDashboardRequest $request)
    {
        $credential = $request->validated();

        // check auth
        $user = User::where(function ($query) use ($credential) {
            $query->where('email', $credential['email'])
                ->orWhere('username', $credential['email']);
        })->first();

        if (!$user || !Hash::check($credential['password'], $user->password)) {
            Log::info("| Auth | - Login failed for email: {$credential['email']}, Invalid credentials");
            return response()->json([
                'status' => Response::HTTP_UNAUTHORIZED,
                'message' => 'Password atau username/email yang anda tidak valid, silahkan periksa kembali dan pastikan akun anda sudah terdaftar',
            ], Response::HTTP_UNAUTHORIZED);
        }

        // cek user aktif
        if (in_array($user->account_status, [1, 3])) {
            Auth::logout();
            Log::info("| Auth | - Login failed for email: {$credential['email']}, User is not active since {$user->deactivate_at}");
            if ($user->account_status == 1) {
                $message = "Kami mendeteksi bahwa akun anda belum diaktifkan sejak " . DateHelper::formatTanggalIndonesia($user->created_at, 1) . ", silahkan hubungi admin untuk melakukan aktivasi";
            } else if ($user->account_status == 3) {
                $message = "Kami mendeteksi bahwa akun anda telah dinonaktifkan sejak " . DateHelper::formatTanggalIndonesia($user->deactivate_at, 1) . ", silahkan hubungi admin untuk melakukan aktivasi kembali";
            }
            return response()->json([
                'status' => Response::HTTP_UNAUTHORIZED,
                'message' => $message,
            ], Response::HTTP_UNAUTHORIZED);
        }

        $user->last_login = now('Asia/Jakarta');
        $user->save();

        // login success
        Log::info("| Auth | - Login success for email: {$credential['email']}, at {$user->last_login}");

        $token = $user->createToken('create_token_' . Str::uuid())->plainTextToken;
        $filteredUser = $user->makeHidden(['password', 'remember_token', 'roles']);
        $roles = $user->roles->first();
        $filteredRoles = $roles ? $roles->makeHidden(['permissions']) : null;

        return response()->json([
            'status' => Response::HTTP_OK,
            'message' => 'Login success! Selamat datang, ' . $user->name,
            'data' => [
                'user' => $filteredUser,
                'roles' => $filteredRoles,
                'permissions' => $filteredRoles ? $roles->permissions->pluck('id') : [],
                'token' => $token
            ],
        ], Response::HTTP_OK,);
    }

    public function getUserInfo()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'status' => Response::HTTP_UNAUTHORIZED,
                'message' => 'Maaf akun pengguna terkait tidak ditemukan',
            ], Response::HTTP_UNAUTHORIZED);
        }
        $filteredUser = $user->makeHidden(['password', 'remember_token', 'roles']);
        $roles = $user->roles->first();
        $filteredRoles = $roles ? $roles->makeHidden(['permissions']) : null;
        return response()->json([
            'status' => Response::HTTP_OK,
            'message' => 'Login success! Selamat datang, ' . $user->name,
            'data' => [
                'user' => $filteredUser,
                'roles' => $filteredRoles,
                'permissions' => $filteredRoles ? $roles->permissions->pluck('id') : []
            ],
        ], Response::HTTP_OK,);
    }

    public function logout()
    {
        if (method_exists(Auth::user()->currentAccessToken(), 'tokens')) {
            Auth::user()->currentAccessToken()->delete();
        }

        Auth::guard('web')->logout();

        Log::info("| Auth | - Logout success for email: " . Auth::user()->email);

        return response()->json([
            'status' => Response::HTTP_OK,
            'message' => 'Anda berhasil melakukan logout',
        ], Response::HTTP_OK);
    }
}
