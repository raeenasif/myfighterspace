<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Domains\Auth\Models\follow;
use App\Domains\Auth\Models\User;
use Auth;

use Validator;

class UserFollowController extends Controller
{
    public function followRequest(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'to_user' => 'required',
        ]);
        if ($validate->fails()) {
            return response()->json(['status' => false, 'messege' => $validate->messages()->all()]);
        }
        $user = Auth::user();
        $followrequest = new follow;
        $input = $request->all();
        $input['from_user'] = $user->id;

        if (!User::find($request->to_user)) {
            return response()->json(['status' => false, 'messege' => 'No User Found']);
        }

        if (follow::where(['from_user' => $user->id, 'to_user' => $request->to_user])->exists()) {
            if ($request->value == '0') {
                follow::where(['from_user' => $user->id, 'to_user' => $request->to_user])->delete();
                return response()->json(['status' => true, 'result' => 'Unfollow Successfully']);
            }
            return response()->json(['status' => true, 'result' => 'you already send request']);
        } else {
            $followrequest->fill($input)->save();
            return response()->json(['status' => true, 'result' => 'Follow Request Successfully ']);
        }
    }

    public function getFollowRequest(Request $request)
    {
        $user = Auth::user();
        $getFollowRequest = follow::where(['to_user' => $user->id, 'value' => 1, 'request' => 'pending'])->with('getUser')->get();
        return response()->json(['status' => true, 'result' => $getFollowRequest]);
    }

    public function requestStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'from_user' => 'required',
            'request' => 'required '
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'messege' => $validator->messages()->all()]);
        }
        $input = $request->all();
        $user = Auth::user();
        $follow = follow::where(['from_user' => $request->from_user, 'to_user' => $user->id])->first();
        if (!follow::where(['from_user' => $request->from_user, 'to_user' => $user->id])) {
            return response()->json(['status' => false, 'messeges' => 'No Data Found']);
        }
        if ($follow->fill($input)->save()) {
            return response()->json(['status' => true, 'result' => 'Request Change Success']);
        } else {
            return response()->json(['status' => true, 'messeges' => 'Some Error']);
        }
    }

    public function userFollowers(Request $request)
    {
        $user = Auth::user();
        $followers = follow::where(['to_user' => $user->id, 'value' => 1, 'request' => 'pending'])->with('getUser')->get();
        return response()->json(['status' => true, 'result' => $followers]);
    }

    public function userFollowing(Request $request)
    {
        $user = Auth::user();
        $followers = follow::where(['from_user' => $user->id, 'value' => 1, 'request' => 'pending'])->with('getUserFollowing')->get();
        return response()->json(['status' => true, 'result' => $followers]);
    }
}
