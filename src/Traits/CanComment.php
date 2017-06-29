<?php

namespace Finagin\Comment\Traits;

use Finagin\Comment\Exceptions\NotCommentableException;
use Finagin\Comment\Models\Comment;

trait CanComment
{
    /**
     * @param Commentable|Comment $commentable
     * @param string $commentText
     *
     * @return Comment
     */
    public function comment($commentable, string $commentText)
    {
        $parentId = null;

        if ($commentable instanceof Comment) {
            $parentId = $commentable->id;

            $commentable = $commentable->commentable;
        } elseif (! method_exists(get_class($commentable), 'isCommentable') || ! $commentable->isCommentable()) {
            throw NotCommentableException::create();
        }

        $attributes = [
            /* Parents */
            'commentable_id'   => $commentable->id,
            'commentable_type' => get_class($commentable),
            'comment_id'       => $parentId,

            /* Fields */
            'text'             => $commentText,
            'approved'         => ! $commentable->commentsMustBeApproved(),

            /* Commentator */
            'commentator_id'   => $this->id,
            'commentator_type' => get_class(),
            'commentator_name' => $this->name,
        ];


        $comment = new Comment($attributes);

        $commentable
            ->comments()
            ->save($comment);

        return $comment;
    }
}
