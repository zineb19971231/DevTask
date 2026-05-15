<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('updateStatus', $this->route('task'));
    }

    public function rules(): array
    {
        return [
            // Status transition is handled in controller logic, 
            // but we could validate it here if needed.
        ];
    }
}
