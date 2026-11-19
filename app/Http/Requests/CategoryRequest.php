<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'it';
    }

    public function rules(): array
    {
        return [
            'nama_kategori' => ['required', 'string', 'max:100'],
            'deskripsi' => ['nullable', 'string'],
        ];
    }
}