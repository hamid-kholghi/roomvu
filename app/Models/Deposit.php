<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Builder
 */
class Deposit extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'user_id',
        'debit',
        'credit',
    ];
}
