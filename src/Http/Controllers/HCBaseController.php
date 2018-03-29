<?php

declare(strict_types = 1);

namespace HoneyComb\Core\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;

/**
 * Class HCBaseController
 * @package HoneyComb\Core\Http\Controllers
 */
class HCBaseController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    
    /**
     * @var
     */
    protected $service;

    /**
     * Getting allowed actions for admin view
     *
     * @param string $prefix
     * @return array
     */
    protected function getActions(string $prefix): array
    {
        $actions[] = 'search';

        if (auth()->user()->can($prefix . '_create')) {
            $actions[] = 'new';
        }

        if (auth()->user()->can($prefix . '_update')) {
            $actions[] = 'update';
        }

        if (auth()->user()->can($prefix . '_delete')) {
            $actions[] = 'delete';
        }

        if (auth()->user()->can($prefix . '_restore')) {
            $actions[] = 'restore';
        }

        if (auth()->user()->can($prefix . '_force_delete')) {
            $actions[] = 'forceDelete';
        }

        if (auth()->user()->can($prefix . '_merge')) {
            $actions[] = 'merge';
        }

        if (auth()->user()->can($prefix . '_clone')) {
            $actions[] = 'clone';
        }

        return $actions;
    }
}
