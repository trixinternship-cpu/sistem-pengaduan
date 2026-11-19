<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HandlingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'it';
    }

    public function rules(): array
    {
        return [
            'analisis_penyebab' => ['required', 'string', 'min:5'],
            'tindakan' => ['required', 'string', 'min:5'],
            'solusi' => ['required', 'string', 'min:5'],
            'hasil_penanganan' => ['required', 'in:berhasil,tidak_berhasil'],
            'catatan_teknisi' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'analisis_penyebab.required' => 'Analisis penyebab wajib diisi.',
            'tindakan.required' => 'Tindakan wajib diisi.',
            'solusi.required' => 'Solusi wajib diisi.',
            'hasil_penanganan.required' => 'Hasil penanganan wajib dipilih.',
        ];
    }
}