<?php

namespace Finagin\Comment\Exceptions;

use InvalidArgumentException;

class NotCommentableException extends InvalidArgumentException
{
    public static function create()
    {
        return new static('Commentable model must use `Commentable` trait.');
    }
}
