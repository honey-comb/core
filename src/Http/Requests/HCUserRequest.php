<?php

declare(strict_types = 1);

namespace HoneyComb\Core\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
//            'is_active' => $this->filled('is_active'),
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
        return [
            'first_name' => $this->input('first_name'),
            'last_name' => $this->input('last_name'),
            'photo_id' => $this->input('photo'),
            'description' => $this->input('description'),
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
                    'email' => 'required|email|unique:hc_users,email|min:5',
                    'password' => 'required|min:5',
                ];
                break;

            case 'PUT':

                $userId = $this->segment(4);

                return [
                    'email' => 'required|email|min:5|unique:hc_users,email,' . $userId,
                    'roles' => 'required',
                    'password' => 'nullable|min:5|confirmed',
                    'password_confirmation' => 'required_with:password|nullable|min:5',
                ];
                break;

            case 'PATCH':
                return [];
                break;

            case 'DELETE':
                return [
                    'list' => 'required',
                ];
                break;
        }

        return [];
    }
}
