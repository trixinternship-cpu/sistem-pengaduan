<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PcRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'it';
    }

    public function rules(): array
    {
        $pcId = $this->route('pc')?->id;

        return [
            'kode_pc' => ['required', 'string', 'max:50', Rule::unique('pcs', 'kode_pc')->ignore($pcId)],
            'nama_pc' => ['required', 'string', 'max:100'],
            'nomor_aset' => ['nullable', 'string', 'max:100'],
            'departemen' => ['required', 'string', 'max:100'],
            'lokasi' => ['required', 'string', 'max:150'],
            'user_id' => ['nullable', 'exists:users,id'],
            'status' => ['required', 'in:aktif,rusak,maintenance'],
            'keterangan' => ['nullable', 'string'],
        ];
    }
}