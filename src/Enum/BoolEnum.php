<?php

declare(strict_types = 1);

namespace HoneyComb\Core\Enum;

/**
 * Class BoolEnum
 * @package HoneyComb\Core\Enum
 */
class BoolEnum extends Enumerable
{
    /**
     * @return BoolEnum|Enumerable
     */
    final public static function no(): BoolEnum
    {
        return self::make(0, trans('HCCore::enum.bool.no'));
    }

    /**
     * @return BoolEnum|Enumerable
     */
    final public static function yes(): BoolEnum
    {
        return self::make(1, trans('HCCore::enum.bool.yes'));
    }
}
