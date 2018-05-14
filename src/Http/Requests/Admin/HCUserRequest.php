<?php

declare(strict_types = 1);

namespace HoneyComb\Core\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class HCUserRequest
 * @package HoneyComb\Core\Http\Requests\Admin
 */
class HCUserRequest extends FormRequest
{
    /**
     * Get request inputs
     *
     * @return array
     */
    public function getUserInput(): array
    {
        $data = [
            'email' => $this->input('email'),
            'is_active' => $this->filled('is_active') ? 1 : 0,
        ];

        if ($this->input('password')) {
            array_set($data, 'password', $this->input('password'));
        }

        return $data;
    }

    /**
     * Get ids to delete, force delete or restore
     *
     * @return array
     */
    public function getListIds(): array
    {
        return $this->input('list', []);
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        return $this->input('roles', []);
    }

    /**
     * Get personal info
     *
     * @return array
     */
    public function getPersonalData(): array
    {
        $photo = $this->input('photo_id');

        if (is_array($photo) && ! $photo) {
            $photo = null;
        }

        return [
            'first_name' => $this->input('first_name'),
            'last_name' => $this->input('last_name'),
            'photo_id' => $photo,
            'description' => $this->input('description'),
            'phone' => $this->input('phone'),
            'address' => $this->input('address'),
        ];
    }

    /**
     * @return bool
     */
    public function wantToActivate(): bool
    {
        return $this->filled('is_active');
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
        switch ($this->method()) {
            case 'POST':
                if ($this->segment(4) == 'restore') {
                    return [
                        'list' => 'required|array',
                    ];
                }

                return [
                    'email' => 'required|email|unique:hc_user,email|min:5',
                    'password' => 'required|min:5',
                    'roles' => 'required|exists:hc_acl_role,id',
                ];

            case 'PUT':

                $userId = $this->segment(4);

                return [
                    'email' => 'required|email|min:5|unique:hc_user,email,' . $userId,
                    'roles' => 'required|exists:hc_acl_role,id',
                    'photo_id' => 'nullable|exists:hc_resource,id',
                    'password' => 'nullable|min:5|confirmed',
                    'password_confirmation' => 'required_with:password|nullable|min:5',
                ];

            case 'PATCH':
                return [];

            case 'DELETE':
                return [
                    'list' => 'required',
                ];
        }

        return [];
    }
}
