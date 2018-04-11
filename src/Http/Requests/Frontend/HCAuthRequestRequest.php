<?php

declare(strict_types=1);

namespace HoneyComb\Core\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class HCAuthRequestRequest
 * @package HoneyComb\Core\Http\Requests\Frontend
 */
class HCAuthRequestRequest extends FormRequest
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
<<<<<<< HEAD
            'email' => $this->input('email'),
=======
        'email' => $this->input('email'),
>>>>>>> de3c28cc8af6e7f47212b3fe79bb89bec4a2ff76
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
