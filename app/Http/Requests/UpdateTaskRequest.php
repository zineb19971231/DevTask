<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('task'));
    }

    public function rules(): array
    {
        // For lead: all fields. For developer: only status.
        // We can handle this logic here or in the controller.
        // But the requirement says "Je ne peux pas modifier autre chose" for developers.
        
        if ($this->user()->role === 'lead' || is_null($this->user()->role)) {
            return [
                'titre' => 'sometimes|string|max:255',
                'description' => 'nullable|string',
                'deadline' => 'nullable|date',
                'priorite' => 'sometimes|in:low,medium,high',
                'user_id' => 'sometimes|exists:users,id',
                'statut' => 'sometimes|in:todo,in_progress,done',
            ];
        }

        return [
            'statut' => 'required|in:todo,in_progress,done',
        ];
    }
}
