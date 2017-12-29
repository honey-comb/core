<?php

declare(strict_types = 1);

namespace HoneyComb\Core\Http\Controllers\Admin;

use Illuminate\View\View;
use HoneyComb\Core\Http\Controllers\HCBaseController;

/**
 * Class HCAdminController
 * @package HoneyComb\Core\Http\Controllers\Admin
 */
class HCAdminController extends HCBaseController
{
    /**
     * Admin dashboard
     *
     * @return View
     */
    public function index(): View
    {
        return view('HCCore::admin.dashboard');
    }
}
