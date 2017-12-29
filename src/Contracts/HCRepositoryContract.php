<?php

declare(strict_types = 1);

namespace HoneyComb\Core\Contracts;

use Illuminate\Database\Eloquent\Builder;


/**
 * Interface RepositoryContract
 * @package HoneyComb\Core\Contracts
 */
interface HCRepositoryContract
{
    /**
     * @return string
     */
    public function model(): string;

    /**
     * @return Builder
     */
    public function makeQuery(): Builder;
}
