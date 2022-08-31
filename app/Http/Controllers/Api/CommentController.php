<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Domains\Auth\Models\post;
use App\Domains\Auth\Models\comment;
use App\Domains\Auth\Models\likecomment;
use App\Domains\Auth\Models\postlike;
use Auth;
use Validator;


class CommentController extends Controller
{
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'post_id' => 'required',
            'comment' => 'required'
        ]);
        if ($validate->fails()) {
            return response()->json(['status' => false, 'Messages' => $validate->messages()->all()]);
        }
        $user = Auth::user();
        $comment = new comment;
        $input = $request->all();
        $input['user_id'] = $user->id;

        if (!post::find($request->post_id)) {
            return response()->json(['status' => false, 'Messages' => 'No Post Found']);
        }
        $comment->fill($input)->save();
        return response()->json(['status' => true, 'Messages' => 'Comment success']);
    }

    public function destroy(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'id' => 'required'
        ]);
        if ($validate->fails()) {
            return response()->json(['status' => false, 'Messages' => $validate->messages()->all()]);
        }
        $comment = comment::find($request->id);
        if ($comment) {
            $comment->delete();
            return response()->json(['status' => true, 'Messages' => 'Comment Delete Success']);
        } else {
            return response()->json(['status' => false, 'Messages' => 'No Comment Found ']);
        }
    }

    public function likeComment(Request $request)
    {

        $validate = Validator::make($request->all(), [
            'comment_id' => 'required',
            'value' => 'required|boolean'
        ]);
        if ($validate->fails()) {
            return response()->json(['status' => false, 'messege' => $validate->messages()->all()]);
        }
        $user = Auth::user();
        $likecomment = new likecomment;
        $input = $request->all();
        $input['user_id'] = $user->id;

        if (!comment::find($request->comment_id)) {
            return response()->json(['status' => false, 'messege' => 'No Comment Found']);
        }

        if (likecomment::where(['user_id' => $user->id, 'comment_id' => $request->comment_id])->exists()) {
            if ($request->value == 0) {
                likecomment::where(['user_id' => $user->id, 'comment_id' => $request->comment_id])->delete();
                return response()->json(['status' => true, 'result' => 'Unlike Succes']);
            }
            return response()->json(['status' => true, 'result' => 'You have already like']);
        } else {
            $likecomment->fill($input)->save();
            return response()->json(['status' => true, 'result' => 'like success']);
        }
    }

    public function likeCommentCount(Request $request)
    {

        $validate = Validator::make($request->all(), [
            'comment_id' => 'required',
            // 'value' => 'required|boolean'
        ]);

        if ($validate->fails()) {
            return response()->json(['status' => false, 'messege' => $validate->messages()->all()]);
        }

        $comment = comment::where('id', $request->comment_id)->with(['CommentlikeCount'])->get();

        foreach ($comment as $key) {

            $key->like = count($key->CommentlikeCount);
            // $key->comment = count($key->CommentCount);
        }
        //$alllike = postlike::where(['post_id' => 3, 'value' => 1])->count();
        return response()->json(['status' => true, 'result' => $comment]);
    }
}
