<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Domains\Auth\Models\post;
use App\Domains\Auth\Models\postlike;

use Auth;
use Validator;

class PostController extends Controller
{
    public function index()
    {
        $post = post::with(['likeCount', 'CommentCount'])->get();

        foreach ($post as $key) {

            $key->like = count($key->likeCount);
            $key->comment = count($key->CommentCount);
        }
        //$alllike = postlike::where(['post_id' => 3, 'value' => 1])->count();
        return response()->json(['status' => true, 'result' => $post]);
    }

    public function upload(Request $request)
    {

        $input = $request->all();
        $validate = Validator::make($request->all(), [
            'title' => 'max:200',
            'post' => 'mimes:jpeg,png,jpg,gif,mp4,mov,ogg|required',
        ]);
        if ($validate->fails()) {
            return response()->json(['status' => false, 'messege' => $validate->messages()->all()]);
        }
        $user = Auth::user();
        $posts = new post;
        if ($request->has('post')) {
            $post = $request->post->store('public/post');
            $input['post'] = basename($post);
        }
        $input['user_id'] = $user->id;
        $posts->fill($input)->save();


        return response()->json(['status' => true, 'result' => 'Post Upload Success']);
    }

    public function delete(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validate->fails()) {
            return response()->json(['status' => false, 'messege' => $validate->messages()->all()]);
        }
        if (!post::find($request->id)) {
            return response()->json(['status' => false, 'messege' => 'No Record Found']);
        }
        $user = Auth::user();
        $delete = post::where('id', $request->id)->delete();

        return response()->json(['status' => true, 'messege' => 'Post Delete Success']);
    }

    public function userpost(Request $request)
    {

        $user = Auth::user();

        $post = post::where('user_id', $user->id)->get();
        // $post->like = 3;
        return response()->json(['status' => true, 'result' => $post]);
    }



    // like post 

    public function likepost(Request $request)
    {

        $validate = Validator::make($request->all(), [
            'post_id' => 'required',
            'value' => 'required|boolean'
        ]);
        if ($validate->fails()) {
            return response()->json(['status' => false, 'messege' => $validate->messages()->all()]);
        }
        $user = Auth::user();
        $postlike = new postlike;
        $input = $request->all();
        $input['user_id'] = $user->id;

        if (!post::find($request->post_id)) {
            return response()->json(['status' => false, 'messege' => 'No Post Found']);
        }

        if (postlike::where(['user_id' => $user->id, 'post_id' => $request->post_id])->exists()) {
            if ($request->value == 0) {
                postlike::where(['user_id' =>  $user->id, 'post_id' => $request->post_id])->delete();
                return response()->json(['status' => true, 'result' => 'Unlike Succes']);
            }
            return response()->json(['status' => true, 'result' => 'like have already like']);
        } else {
            $postlike->fill($input)->save();
            return response()->json(['status' => true, 'result' => 'like success']);
        }
    }

    // end like post
}
