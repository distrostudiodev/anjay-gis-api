<?php

namespace App\Http\Requests\Auth;

use Illuminate\Http\Response;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class VerifyOTPRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'otp_code' => ['required', 'numeric', 'digits:6'],
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Silahkan masukkan email anda terlebih dahulu.',
            'email.email' => 'Silahkan masukkan email yang valid, bisa berupa @gmail.com atau yang lain.',
            'otp_code.required' => 'Silahkan masukkan kode OTP yang telah dikirim di email anda.',
            'otp_code.numeric' => 'Kode OTP yang valid harus berupa angka.',
            'otp_code.digits' => 'Kode OTP harus terdiri dari 6 digit.',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        $messages = implode(' ', $validator->errors()->all());
        $response = [
            'status' => Response::HTTP_BAD_REQUEST,
            'message' => $messages,
        ];

        throw new HttpResponseException(response()->json($response, Response::HTTP_BAD_REQUEST));
    }
}
