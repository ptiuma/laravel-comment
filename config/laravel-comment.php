<?php
return [
    'models' => [
        'comment' => [
            'modelClass'    => Finagin\Comment\Models\Comment::class,
            'contractClass' => Finagin\Comment\Contracts\Comment::class,
        ],
    ],
    'table' => [
        'prefix' => '',
        'names' => [
            'comments' => 'comments',
        ],
    ],
];
