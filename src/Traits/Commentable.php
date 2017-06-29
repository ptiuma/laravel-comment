<?php

namespace Finagin\Comment\Traits;

use Finagin\Comment\Models\Comment;

trait Commentable
{
    /**
     * Must comments be approved.
     *
     * @var bool
     */
    protected $commentsMustBeApproved = false;

    /**
     * Can comments be rated.
     *
     * @var bool
     */
    protected $commentsCanBeRated = false;

    /**
     * @return bool
     */
    public function commentsCanBeRated()
    {
        return boolval((isset($this->commentsCanBeRated)) ? $this->commentsCanBeRated : false);
    }

    /**
     * @return bool
     */
    public function commentsMustBeApproved()
    {
        return boolval((isset($this->commentsMustBeApproved)) ? $this->commentsMustBeApproved : false);
    }

    /**
     * @return bool
     */
    public function isCommentable()
    {
        return true;
    }

    /**
     * @param bool $all All comments with not approved
     *
     * @return int
     */
    public function commentCount($all = false)
    {
        return $this->comments($all)->count();
    }

    /**
     * @param bool $all All comments with not approved
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function comments($all = false)
    {
        $comments = $this->morphMany(Comment::class, 'commentable');

        if (! $all) {
            $comments = $comments->where('approved', true);
        }

        return $comments;
    }
}
