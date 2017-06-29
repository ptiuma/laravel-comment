<?php

namespace Finagin\Comment\Tests\Models;

use Finagin\Comment\Traits\CanComment;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use CanComment;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name',];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';
}
