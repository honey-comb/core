<?php

declare(strict_types = 1);

namespace HoneyComb\Core\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;

/**
 * Class HCUuidModel
 * @package HoneyComb\Core\Models
 */
class HCUuidModel extends HCModel
{
    use SoftDeletes;

    /**
     * Soft delete database field.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        /**
         * Attach to the 'creating' Model Event to provide a UUID
         * for the `id` field (provided by $model->getKeyName())
         */
        static::creating(function ($model) {
            if (!isset($model->attributes['id'])) {
                $model->attributes['id'] = Uuid::uuid4()->toString();
            }

            /** @var HCUuidModel $model */
            $model->{$model->getKeyName()} = $model->attributes['id'];
        });
    }
}
