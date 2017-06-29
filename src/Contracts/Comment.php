<?php

namespace Finagin\Comment\Contracts;

interface Comment
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function commentable();

    /**
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function commentator();

    /**
     * @return mixed
     */
    public function children();

    /**
     * @return Comment
     */
    public function approve();

    /**
     * @return Comment
     */
    public function rateUp();

    /**
     * @return Comment
     */
    public function rateDown();

    /**
     * @param int $value
     *
     * @return Comment
     */
    public function setRate(int $value);
}
