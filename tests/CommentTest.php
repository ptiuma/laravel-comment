<?php

namespace Finagin\Comment\Tests;

use Faker\Generator;
use Faker\Provider\Lorem;
use Finagin\Comment\Exceptions\NotCommentableException;
use Finagin\Comment\Models\Comment;
use Finagin\Comment\Tests\Models\Post;
use Finagin\Comment\Tests\Models\User;

class CommentTest extends TestCase
{
    private $faker;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->faker = new Generator();
        $this->faker->addProvider(Lorem::class);
    }

    /**
     * @test
     */
    public function testCanCreateCommentAndSubComment()
    {
        $user = $this->getUser();
        $post = $this->getPost();

        $commentText    = $this->faker->paragraph;
        $subCommentText = $this->faker->paragraph;
        $anonUserName   = $this->faker->word;


        $comment = $user->comment($post, $commentText);

        $this->assertInstanceOf(Comment::class, $comment);
        $this->assertEquals($comment->text, $commentText);


        $subComment = (new User(['name' => $anonUserName]))->comment($comment, $subCommentText);

        $this->assertInstanceOf(Comment::class, $subComment);
        $this->assertEquals($subComment->text, $subCommentText);
        $this->assertEquals($subComment->commentator()->name, $anonUserName);
    }

    /**
     * @return User
     */
    protected function getUser()
    {
        return User::firstOrCreate(['name' => 'Tester',]);
    }

    /**
     * @return Post
     */
    protected function getPost()
    {
        return Post::firstOrCreate(['name' => 'Article',]);
    }

    /**
     * @expectedException NotCommentableException
     */
    public function testNotCommentableException()
    {
        $this->expectException(NotCommentableException::class);
        (new User(['name' => $this->faker->word]))->comment(null, $this->faker->paragraph);
    }

    /**
     * @test
     */
    public function testCommentableHelpers()
    {
        $post = $this->getPost();

        $this->assertFalse($post->commentsCanBeRated());
        $post->setCommentsCanBeRated(true);
        $this->assertTrue($post->commentsCanBeRated());


        $this->assertFalse($post->commentsMustBeApproved());
        $post->setCommentsMustBeApproved(true);
        $this->assertTrue($post->commentsMustBeApproved());
    }

    /**
     * @test
     */
    public function testCommentChildren()
    {
        $user  = $this->getUser();
        $post  = $this->getPost();
        $count = $this->faker->numberBetween(10, 100);

        $comment = $user->comment($post, $this->faker->paragraph);

        $this->assertEquals(0, $comment->children()->count());

        for ($i = 0; $i < $count; $i++) {
            $user->comment($comment, $this->faker->paragraph);
        }
        $this->assertEquals($count, $comment->children()->count());
    }

    /**
     * @test
     */
    public function testCommentableCommentCount()
    {
        $user = $this->getUser();
        $post = $this->getPost();

        $this->assertEquals(0, $post->commentCount());
        $this->assertEquals(0, $post->commentCount(true));


        $user->comment($post, $this->faker->paragraph);

        $this->assertEquals(1, $post->commentCount());
        $this->assertEquals(1, $post->commentCount(true));


        $post->setCommentsMustBeApproved(true);

        $notApprovedComment = $user->comment($post, $this->faker->paragraph);

        $this->assertEquals(1, $post->commentCount());
        $this->assertEquals(2, $post->commentCount(true));


        $notApprovedComment->approve();

        $this->assertEquals(2, $post->commentCount());
        $this->assertEquals(2, $post->commentCount(true));
    }

    /**
     * @test
     */
    public function testRate()
    {
        $user = $this->getUser();
        $post = $this->getPost();

        $this->assertFalse($post->commentsCanBeRated());

        $post->setCommentsCanBeRated(true);

        $this->assertTrue($post->commentsCanBeRated());

        $comment = $user->comment($post, $this->faker->paragraph);

        $comment->setRate(10);
        $this->assertEquals($comment->rate, 10);

        $comment->rateDown();
        $this->assertEquals($comment->rate, 9);

        $comment->rateUp();
        $comment->rateUp();
        $this->assertEquals($comment->rate, 11);
    }

    /**
     * @return Post
     */
    protected function createPost()
    {
        $post = Post::create(
            ['name' => $this->faker->word,]
        );

        return $post;
    }

    /**
     * @return User
     */
    protected function createUser()
    {
        $user = User::create(
            ['name' => $this->faker->word,]
        );

        return $user;
    }
}
