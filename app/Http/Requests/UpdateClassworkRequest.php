<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClassworkRequest extends FormRequest
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
        $classwork = $this->route('classwork'); // هذا بيجيب الكلاسورك من قاعدة البيانات اذا كنت مستخدم model binding
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'topic_id' => ['nullable', 'integer', 'exists:topics,id'],
            'published_at' => ['nullable', 'date'],
            'options.grade' => [Rule::requiredIf(fn() => $classwork->type == 'assignment'), 'numeric', 'min:0'],
            'options.due_date' => ['nullable', 'date', 'after:published_at']
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'published_at' => $this->has('published_at') && $this->filled('published_at')
                ? $this->published_at
                : now(),
        ]);
    }
}
