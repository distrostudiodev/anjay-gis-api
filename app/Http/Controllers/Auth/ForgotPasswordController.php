<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\SendOTPRequest;
use App\Http\Requests\Auth\VerifyOTPRequest;
use App\Mail\Auth\SendingOTPMail;
use App\Models\OTP_Code;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
	public function sendOTP(SendOTPRequest $request)
	{
		$credentials = $request->validated();

		$user = User::where('email', $credentials['email'])->first();
		if (!$user) {
			return response()->json([
				'status' => Response::HTTP_NOT_FOUND,
				'message' => 'Pengguna dengan email ' . $credentials['email'] . ' tidak ditemukan, pastikan email anda sudah terdaftar.',
			], Response::HTTP_NOT_FOUND);
		}

		$otp = rand(100000, 999999); // Generate OTP 6 digit
		$expiresAt = Carbon::now('Asia/Jakarta')->addMinutes(5); // OTP berlaku 10 menit

		OTP_Code::updateorcreate([
			'user_id' => $user->id,
			'otp' => Hash::make($otp), // Hash OTP untuk keamanan,
			'expires_at' => $expiresAt,
		]);

		Mail::to($user->email)->send(new SendingOTPMail($user->name, $otp));
		Log::info('| Auth | - Send OTP success for email: ' . $user->email);

		return response()->json([
			'status' => Response::HTTP_OK,
			'message' => 'Kode OTP berhasil dikirim, silahkan cek inbox atau spam di email anda.',
		], Response::HTTP_OK);
	}

	public function verifyOTP(VerifyOTPRequest $request)
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
			Log::info('| Auth | - Verify OTP failed: OTP not found for email: ' . $user->email);
			return response()->json([
				'status' => Response::HTTP_BAD_REQUEST,
				'message' => 'Kode OTP tidak ditemukan. Silakan kirim ulang OTP.',
			], Response::HTTP_BAD_REQUEST);
		}

		if (!Hash::check($credentials['otp_code'], $otpRecord->otp)) {
			Log::info('| Auth | - Verify OTP failed: Incorrect OTP for email: ' . $user->email);
			return response()->json([
				'status' => Response::HTTP_UNAUTHORIZED,
				'message' => 'Kode OTP yang anda masukkan tidak ditemukan. Pastikan email anda sudah terdaftar dan silakan kirim ulang OTP.',
			], Response::HTTP_UNAUTHORIZED);
		}

		if (!$otpRecord || Carbon::now('Asia/Jakarta')->greaterThan($otpRecord->expires_at)) {
			Log::info('| Auth | - Verify OTP failed: OTP expired for email: ' . $user->email);
			return response()->json([
				'status' => Response::HTTP_UNAUTHORIZED,
				'message' => 'Kode OTP yang anda masukkan sudah kadaluarsa. Silakan kirim ulang kode OTP.',
			], Response::HTTP_UNAUTHORIZED);
		}

		Log::info('| Auth | - Verify OTP success for email: ' . $user->email);

		return response()->json([
			'status' => Response::HTTP_OK,
			'message' => 'Kode OTP berhasil diverifikasi. Silahkan lakukan reset password anda.',
		], Response::HTTP_OK);
	}
}
