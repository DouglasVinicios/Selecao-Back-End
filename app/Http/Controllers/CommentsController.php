<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentCollection;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CommentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function comments()
    {
        return new CommentCollection(Comment::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $comment = new Comment();
        $comment->comment = $request->comment;
        $comment->author_id = $request->user()->id;
        $comment->save();
        return response()->json(new CommentResource($comment));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        /** @var User */
        $user = $request->user();
        /** @var Comment */
        $comment = Comment::where(['id' => $request->id])->first();

        if(is_null($comment) || !$comment->isAuthor($user))
            throw new NotFoundHttpException();

        $comment->delete();

        $comment = new Comment();
        $comment->comment = $request->comment;
        $comment->author_id = $request->user()->id;
        $comment->saveOrFail();

        return response()->json(new CommentResource($comment));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function delete(int $id, Request $request)
    {
        /** @var User */
        $user = $request->user();
        /** @var Comment */
        $comment = Comment::where(['id' => $id])->first();

        if(is_null($comment) || !$comment->canDelete($user))
            throw new NotFoundHttpException();

        $comment->delete();
    }

    public function history(Request $request)
    {
        $comments = Comment::withTrashed()
            ->where(['author_id' => $request->user()->id])
            ->orderBy('deleted_at')
            ->get();

        return new CommentCollection($comments);
    }
}
