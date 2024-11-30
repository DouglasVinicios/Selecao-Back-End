<?php

namespace Tests\Unit;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testCommentsListing()
    {
        $comments = Comment::factory()->count(5)->create();

        $response = $this->getJson('/api/comments');

        $response->assertStatus(200);
        $response->assertJsonStructure(['data' => [['id', 'comment', 'author_id']]]);
        $this->assertCount(5, $response->json('data'));
    }

    public function testCreateComment()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/create/comments', [
            'comment' => 'This is a test comment',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['id', 'comment', 'author_id']);
        $this->assertDatabaseHas('comments', [
            'comment' => 'This is a test comment',
            'author_id' => $user->id,
        ]);
    }

    public function testUpdateComment()
    {
        $user = User::factory()->create();
        $comment = Comment::factory()->create([
            'author_id' => $user->id,
            'comment' => 'Original comment',
        ]);

        $this->actingAs($user);

        $response = $this->putJson("/api/update/comments", [
            'id' => $comment->id,
            'comment' => 'Updated comment',
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('comments', [
            'comment' => 'Updated comment',
            'author_id' => $user->id,
        ]);

        $this->assertSoftDeleted('comments', [
            'id' => $comment->id,
        ]);
    }

    public function testDeleteComment()
    {
        $user = User::factory()->create();
        $comment = Comment::factory()->create(['author_id' => $user->id]);

        $response = $this->actingAs($user)->deleteJson("/api/delete/comment/{$comment->id}");

        $response->assertStatus(200);
        $this->assertSoftDeleted('comments', ['id' => $comment->id]);
    }

    public function testDeleteCommentFailure()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => 'password',
            'isAdmin' => false
        ]);

        $author = User::factory()->create();
        $comment = Comment::factory()->create(['author_id' => $author->id]);

        $response = $this->actingAs($user)->deleteJson("/api/delete/comment/{$comment->id}");

        $response->assertStatus(404);
    }

    public function testHistoryListing()
    {
        $user = User::factory()->create();
        $comments = Comment::factory()->count(3)->create(['author_id' => $user->id]);
        $deletedComment = Comment::factory()->create(['author_id' => $user->id]);
        $deletedComment->delete();

        
        $response = $this->actingAs($user)->getJson('/api/history/comments');

        $response->assertStatus(200);
        $response->assertJsonStructure(['data' => [['id', 'comment', 'author_id']]]);
        $this->assertCount(4, $response->json('data'));
    }
}
