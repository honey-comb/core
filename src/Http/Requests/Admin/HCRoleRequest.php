<?php

declare(strict_types = 1);

namespace HoneyComb\Core\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class HCRoleRequest
 * @package HoneyComb\Core\Http\Requests\Admin
 */
class HCRoleRequest extends FormRequest
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
        return [
            'role_id' => 'required|exists:hc_acl_role,id',
            'permission_id' => 'required|exists:hc_acl_permission,id',
        ];
    }
}
