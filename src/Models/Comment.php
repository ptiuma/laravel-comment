<?php

namespace Finagin\Comment\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Finagin\Comment\Contracts\Comment as CommentContracts;

class Comment extends Model implements CommentContracts
{
    use SoftDeletes;

    /**
     * {@inheritdoc}
     */
    protected $fillable = [
        /* Parents */
        'commentable_id',
        'commentable_type',
        'comment_id',

        /* Commentator */
        'commentator_id',
        'commentator_type',
        'commentator_name',

        /* Fields */
        'text',
        'approved',

        /* Rating */
        'rate_type',
        'rate',
    ];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'comment'  => 'string',
        'rate'     => 'double',
        'approved' => 'boolean',
    ];

    /**
     * {@inheritdoc}
     */
    protected $dates = ['deleted_at'];

    /**
     * {@inheritdoc}
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $tablePrefix = config('laravel-comment.table.prefix');
        $tablePrefix = $tablePrefix ? $tablePrefix.'_' : '';

        $names = config('laravel-comment.table.names');

        $table = $tablePrefix.$names[$this->table];

        $this->setTable($table);
    }

    /**
     * {@inheritdoc}
     */
    public function commentable()
    {
        return $this->morphTo();
    }

    /**
     * {@inheritdoc}
     */
    public function commentator()
    {
        $commentator = $this->commentator_type::firstOrNew(
            ['id' => $this->commentator_id],
            ['name' => $this->commentator_name]
        );

        return $commentator;
    }

    public function children()
    {
        return self::where(
            [
                'commentable_id'   => $this->commentable_id,
                'commentable_type' => $this->commentable_type,

                'comment_id' => $this->id,
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function approve()
    {
        $this->approved = true;
        $this->save();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function rateUp()
    {
        $this->rate += 1;
        $this->save();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function rateDown()
    {
        $this->rate -= 1;
        $this->save();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setRate(int $value)
    {
        $this->rate = $value;
        $this->save();

        return $this;
    }
}
