<?php

namespace App\Services\Public;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Throwable;

class ViewCounterService
{
    public function increment(Model $model): void
    {
        try {
            if (! Schema::hasColumn($model->getTable(), 'views')) {
                return;
            }

            $model->increment('views');
        } catch (Throwable) {
            return;
        }
    }
}
