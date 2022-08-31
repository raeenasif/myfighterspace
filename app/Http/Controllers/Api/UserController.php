<?php

namespace App\Http\Controllers\Api;

use App\Domains\Auth\Models\comment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use DB;
use Validator;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Domains\Auth\Models\User;
use App\Domains\Auth\Models\post;
use App\Domains\Auth\Models\postlike;
use App\Domains\Auth\Models\follow;

use App\Notifications\SentOTP;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;

class UserController extends Controller
{
    public function logout(Request $request)
    {
        $accessToken = auth()->user()->token();
        $token = $request->user()->tokens->find($accessToken);
        $token->revoke();
        return response(['message' => 'You have been successfully logged out.'], 200);
    }

    public function user(Request $request)
    {
        $user = Auth::user();

        $response = [
            'status' => true,
            'data' => $user,
            'Messages' => 'User Profile Data'
        ];
        $user['profileimage'] = asset('/storage/profile') . '/' . $user->profileimage;
        return response()->json($response, 200);
    }


    public function edit(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'max:200|required',
            'profileimage' => 'image|mimes:jpeg,png,jpg,gif',
            'age' => 'max:100|integer',
            'weight' => 'max:100|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'Messages' => $validator->messages()->all()]);
        }

        $user = Auth::user();
        $input = $request->all();
        if ($request->has('profileimage')) {
            $profile = $request->profileimage->store('public/profile');
            $input['profileimage'] = basename($profile);
        }
        $user->fill($input)->save();

        $user['profileimage'] = asset('/storage/profile') . '/' . $user->profileimage;

        return response()->json(['status' => true, 'Messages' => 'User Profile Update Success', 'data' => $user]);
    }

    public function alluser()
    {
        $user = User::where('type', 'member')->orWhere('type', 'fighter')->orWhere('type', 'trainer')->get();
        return response()->json(['status' => true, 'result' => $user]);
    }

    public function singleuser(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'id' => 'required'
        ]);
        if ($validate->fails()) {
            return response()->json(['status' => false, 'Messages' => $validate->messages()->all()]);
        }
        $comment = User::find($request->id);
        if ($comment) {
            $comment->get();
            $post = post::where('user_id', $request->id)->with(['likeCount', 'CommentCount'])->get();
            foreach ($post as $key) {

                $key->like = count($key->likeCount);
                $key->comment = count($key->CommentCount);
            }
            // $follow = follow::where('to_user', $request->id)->with('followersCount')->get();
            // foreach ($follow as $key) {

            //     $key->followers = count($key->followersCount);
            //     // $key->comment = count($key->CommentCount);
            // }
            return response()->json(['status' => true, 'result' => $comment, 'post' => $post]);
        } else {
            return response()->json(['status' => false, 'Messages' => 'No User Found ']);
        }
    }

    public function likePostUser(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'id' => 'required'
        ]);
        if ($validate->fails()) {
            return response()->json(['status' => false, 'Messages' => $validate->messages()->all()]);
        }

        $user = postlike::where('post_id', $request->id)->with('getUser')->get();
        return response()->json(['status' => true, 'result' => $user]);
    }

    public function likeCommentUser(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'id' => 'required'
        ]);
        if ($validate->fails()) {
            return response()->json(['status' => false, 'Messages' => $validate->messages()->all()]);
        }

        $user = comment::where('post_id', $request->id)->with('getUser')->get();
        return response()->json(['status' => true, 'result' => $user]);
    }
}
