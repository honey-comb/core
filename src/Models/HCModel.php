<?php

declare(strict_types = 1);

namespace HoneyComb\Core\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class HCModel
 * @package HoneyComb\Core\Models
 */
class HCModel extends Model
{
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Function which gets table name
     *
     * @return mixed
     */
    public static function getTableName(): string
    {
        return with(new static)->getTable();
    }

    /**
     * Function which gets fillable fields array
     *
     * @return array
     */
    public static function getFillableFields(): array
    {
        return with(new static)->getFillable();
    }
}
