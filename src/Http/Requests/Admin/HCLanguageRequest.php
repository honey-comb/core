<?php

declare(strict_types = 1);

namespace HoneyComb\Core\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class HCLanguageRequest
 * @package HoneyComb\Core\Http\Requests
 */
class HCLanguageRequest extends FormRequest
{
    /**
     * List of available keys for strict update
     *
     * @var array
     */
    protected $strictUpdateKeys = ['content', 'front_end', 'back_end'];

    /**
     * Get only available to update fields
     *
     * @return array
     */
    public function getStrictUpdateValues(): array
    {
        return $this->only($this->strictUpdateKeys);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'front_end' => 'boolean',
            'back_end' => 'boolean',
            'content' => 'boolean',
        ];
    }
}
