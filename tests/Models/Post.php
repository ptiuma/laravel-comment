<?php

namespace Finagin\Comment\Tests\Models;

use Finagin\Comment\Traits\Commentable;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use Commentable;

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
    protected $table = 'posts';

    /**
     * @param bool $commentsMustBeApproved
     *
     * @return $this
     */
    public function setCommentsMustBeApproved(bool $commentsMustBeApproved)
    {
        $this->commentsMustBeApproved = $commentsMustBeApproved;

        return $this;
    }

    /**
     * @param bool $commentsCanBeRated
     *
     * @return $this
     */
    public function setCommentsCanBeRated(bool $commentsCanBeRated)
    {
        $this->commentsCanBeRated = $commentsCanBeRated;

        return $this;
    }
}
