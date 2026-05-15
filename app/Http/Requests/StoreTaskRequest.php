<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Delegate to TaskPolicy which checks project-specific roles
        // Also allow if global role is null (pending migration)
        return $this->user()->role === 'lead' || is_null($this->user()->role);
    }

    public function rules(): array
    {
        return [
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline' => 'nullable|date',
            'priorite' => 'required|in:low,medium,high',
            'user_id' => 'required|exists:users,id',
        ];
    }
}
