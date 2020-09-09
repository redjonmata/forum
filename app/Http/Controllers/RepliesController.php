<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostRequest;
use App\Reply;
use App\Thread;
use Illuminate\Support\Facades\Gate;

class RepliesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth',['except' => 'index']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param $channelId
     * @param Thread $thread
     */
    public function index($channelId, Thread $thread)
    {
        return $thread->replies()->paginate(20);
    }

    /**
     * Store a newly created resource in storage
     * @param $channelId
     * @param Thread $thread
     * @param CreatePostRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Database\Eloquent\Model|\Illuminate\Http\Response
     */
    public function store($channelId, Thread $thread, CreatePostRequest $request)
    {
        return $thread->addReply([
            'body' => request('body'),
            'user_id' => auth()->id()
        ])->load('owner');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Reply $reply
     * @return void
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);

        try {
            $this->validate(request(), ['body' => 'required|spamfree']);

            $reply->update(request(['body']));

        } catch (\Exception $e) {
            return response(
                'Sorry, your reply could not be saved at this time',422
            );
        }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Reply $reply
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply);

        $reply->delete();

        if (request()->expectsJson()) {
            return response(['status' => 'Reply deleted!']);
        }

        return back();
    }
}
