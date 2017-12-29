<?php

declare(strict_types = 1);

namespace HoneyComb\Core\Contracts;

/**
 * Interface HCFormContract
 * @package HoneyComb\Core\Http\Controllers\Interfaces
 */
interface HCFormContract
{
    /**
     * @param bool $edit
     * @return array
     */
    public function createForm(bool $edit = false): array;

    /**
     * @param string $prefix
     * @return array
     */
    public function getStructureNew(string $prefix): array;

    /**
     * @param string $prefix
     * @return array
     */
    public function getStructureEdit(string $prefix): array;
}
