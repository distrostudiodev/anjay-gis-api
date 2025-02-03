<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\OTP_Code;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\ResetPasswordRequest;

class ResetPasswordController extends Controller
{
    public function resetPassword(ResetPasswordRequest $request)
    {
        $credentials = $request->validated();

		$user = User::where('email', $credentials['email'])->first();
		if (!$user) {
			return response()->json([
				'status' => Response::HTTP_NOT_FOUND,
				'message' => 'Pengguna dengan email ' . $credentials['email'] . ' tidak ditemukan, pastikan email anda sudah terdaftar.',
			], Response::HTTP_NOT_FOUND);
		}

        $otpRecord = OTP_Code::where('user_id', $user->id)->latest()->first();
        if (!$otpRecord) {
			return response()->json([
				'status' => Response::HTTP_BAD_REQUEST,
				'message' => 'Kode OTP tidak ditemukan. Silakan kirim ulang OTP.',
			], Response::HTTP_BAD_REQUEST);
		}

        if (!Hash::check($credentials['otp_code'], $otpRecord->otp)) {
			return response()->json([
				'status' => Response::HTTP_UNAUTHORIZED,
				'message' => 'Kode OTP yang anda masukkan tidak ditemukan. Pastikan email anda sudah terdaftar dan silakan kirim ulang OTP.',
			], Response::HTTP_UNAUTHORIZED);
		}

        $user->password = Hash::make($credentials['password']);
        $user->save();
        OTP_Code::where('user_id', $user->id)->delete();

        Log::info('| Auth | - Reset Password success for email: ' . $user->email);

        return response()->json([
            'status' => Response::HTTP_OK,
            'message' => 'Password berhasil diubah. Silahkan login menggunakan password baru anda.',
        ], Response::HTTP_OK);
    }
}
