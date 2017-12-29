<?php

declare(strict_types = 1);

namespace HoneyComb\Core\Http\Controllers\Admin;

use Rap2hpoutre\LaravelLogViewer\LogViewerController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class HCLogViewerController
 * @package HoneyComb\Core\Http\Controllers
 */
class HCLogViewerController extends LogViewerController
{
    /**
     * Add checking for log route permissions
     * @return array
     * @throws \Exception
     */
    public function index()
    {
        if (auth()->check() && auth()->user()->isNotSuperAdmin()) {
            throw new NotFoundHttpException('Access denied');
        }

        return parent::index();
    }
}
