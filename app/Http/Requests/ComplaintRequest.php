<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ComplaintRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'pelapor';
    }

    public function rules(): array
    {
        return [
            'pc_id' => ['required', 'exists:pcs,id'],
            'category_id' => ['required', 'exists:categories,id'],
            'jenis_kendala' => ['required', 'string', 'max:255'],
            'what' => ['required', 'string', 'min:5'],
            'who' => ['required', 'string', 'max:255'],
            'when' => ['required', 'date'],
            'where' => ['required', 'string', 'max:255'],
            'why' => ['nullable', 'string'],
            'how' => ['required', 'string', 'min:10'],
            'deskripsi' => ['required', 'string', 'min:10'],
            'attachments' => ['nullable', 'array', 'max:3'],
            'attachments.*' => ['file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'pc_id.required' => 'PC wajib dipilih.',
            'category_id.required' => 'Kategori kendala wajib dipilih.',
            'jenis_kendala.required' => 'Jenis kendala wajib diisi.',
            'what.required' => 'Apa kendalanya wajib diisi.',
            'who.required' => 'Siapa yang mengalami wajib diisi.',
            'when.required' => 'Kapan kejadian wajib diisi.',
            'where.required' => 'Lokasi wajib diisi.',
            'how.required' => 'Kronologi wajib diisi.',
            'deskripsi.required' => 'Deskripsi kendala wajib diisi.',
            'attachments.*.max' => 'Ukuran file maksimal 2MB.',
            'attachments.*.mimes' => 'Format file harus jpg, jpeg, png, atau pdf.',
        ];
    }
}