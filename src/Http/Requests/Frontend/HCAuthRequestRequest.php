<?php

declare(strict_types=1);

namespace HoneyComb\Core\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class HCAuthRequest
 * @package HoneyComb\Core\Http\Requests\Frontend
 */
class HCAuthRequest extends FormRequest
{
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
        switch ($this->method()) {
            case 'POST':
                return [
                    'email' => 'required|email|unique:hc_user,email|min:5',
                    'password' => 'required|min:5',
//                    'roles' => 'required|exists:hc_acl_role,id',
                ];
        }

        return [];
    }

    public function getInputData(): array
    {
        $data = [
        'email' => $this->input('email'),
        ];

        if ($this->input('password')) {
            array_set($data, 'password', $this->input('password'));
        }

        return $data;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        return $this->input('roles', []);
    }
}
