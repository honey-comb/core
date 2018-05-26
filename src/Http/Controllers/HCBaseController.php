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
     * @param array $except
     * @return array
     */
    protected function getActions(string $prefix, array $except = []): array
    {
        $actions[] = 'search';

        if (!in_array('_create', $except) && auth()->user()->can($prefix . '_create')) {
            $actions[] = 'new';
        }

        if (!in_array('_update', $except) && auth()->user()->can($prefix . '_update')) {
            $actions[] = 'update';
        }

        if (!in_array('_delete', $except) && auth()->user()->can($prefix . '_delete')) {
            $actions[] = 'delete';
        }

        if (!in_array('_restore', $except) && auth()->user()->can($prefix . '_restore')) {
            $actions[] = 'restore';
        }

        if (!in_array('_force_delete', $except) && auth()->user()->can($prefix . '_force_delete')) {
            $actions[] = 'forceDelete';
        }

        if (!in_array('_merge', $except) && auth()->user()->can($prefix . '_merge')) {
            $actions[] = 'merge';
        }

        if (!in_array('_clone', $except) && auth()->user()->can($prefix . '_clone')) {
            $actions[] = 'clone';
        }

        return $actions;
    }
}
