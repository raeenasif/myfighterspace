<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use DB;
use Validator;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Domains\Auth\Models\User;
use App\Notifications\SentOTP;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * @OA\Post(
     * path="/api/login",
     * operationId="authLogin",
     * tags={"Login"},
     * summary="User Login",
     * description="Login User Here",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"email", "password"},
     *               @OA\Property(property="email", type="email"),
     *               @OA\Property(property="password", type="password")
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Login Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Login Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */

    public function login(Request $request)
    {
        $attr = $request->validate([
            'email' => 'required|string|email|',
            'password' => 'required|string|min:6'
        ]);

        if (!Auth::attempt($attr)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid login details'
            ], 401);
        }
        $token = auth()->user()
            ->createToken('auth_token')->accessToken;
        $user = auth()->user();

        $user->token = $token;
        $user->token_type = 'Bearer';
        $user['profileimage'] = asset('/storage/profile') . '/' . $user->profileimage;
        $respon = [
            'success' => true,
            'message' => 'Login successfully',
            'data' => $user

        ];

        return response()->json($respon, 200);
    }




    public function signup(Request $request)
    {


        DB::beginTransaction();
        // try {
        $screen = 'first';
        $request['screen_status'] = $screen;
        $rules = [
            // 'name' => 'required|max:100',
            'password' => 'required|string|min:6|max:20|confirmed',
            //'hear_about_us' => 'required',
            // 'screen_status' => 'required'
        ];



        if ($request->has('id') && $request->id != '') { // check user id
            $user = User::find($request->id);
            if (empty($user)) {
                return response()->json(['success' => false, 'message' => array('wrong id !')]);
            }
            $rules['email']   = ['required', Rule::unique('users')->ignore($request->id)];
        } else {

            $rules['email']    = 'unique:users|required|';
            $user = null;
        }

        $input     = $request->only('email', 'password', 'password_confirmation');

        // if ($request->has('hear_about_us') && count($request->hear_about_us) > 0) {
        //     $input['hear_about_us'] = json_encode($request->hear_about_us);
        // }
        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->messages()->all()]);
        }


        $input['password'] = bcrypt($input['password']);
        // $input['type'] = 'customer';
        // $input['name'] =  $input['name'];
        if ($user != null) {
            $user->fill($input)->save();
        } else {

            $user = User::create($input);
            $token = $user->createToken('Token')->accessToken;
        }

        $data['success'] = true;
        $data['message'] = 'Registration has been successfully, Please check email for verification code, Thank You.';
        $data['data'] = $user;
        $data['token'] = $token;
        $otp = rand(1000, 9999);
        $user->otp = $otp;
        if ($request['type']) {
            $user->type = $request['type'];
        } else {
            $user->type = 'member';
        }

        $user->save();
        $user->notify(new SentOTP);
        // } catch (Exception $e) {
        //     DB::rollBack();

        //     throw new GeneralException($e->message());
        // }

        DB::commit();
        return response()->json($data);
    }



    /**

     * @OA\Post(
     * path="/api/resend/otp",
     * operationId="Register",
     * tags={"Resend OTP"},
     * summary="Resend OTP",
     * description="resend OTP here",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"user_id"},
     *               @OA\Property(property="first_name", type="text"),
     *              
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Register Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Register Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */

    public function reSendOTP(Request $request)
    {


        DB::beginTransaction();
        try {

            $rules = [
                'id' => 'required',

            ];

            //$input     = $request->only('user_id', 'photo', 'zip_code','dob', 'gender', 'type_mom', 'industry_id', 'company_id', 'company_code', 'screen_status');

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'error' => $validator->messages()]);
            }
            // $name = $request->name;
            // $email    = $request->email;
            // $password = $request->password;

            $user = User::find($request->id);

            if (empty($user)) {
                return response()->json(['success' => false, 'error' => array('id' => 'not found data! wrong user id')]);
            }

            $data['success'] = true;
            $data['message'] = array('Sent OTP on your email.');
            $data['data'] = $user;
            $otp = rand(1000, 9999);
            $user->otp = $otp;
            $user->save();
            $user->notify(new SentOTP);
        } catch (Exception $e) {
            DB::rollBack();

            throw new GeneralException($e->message());
        }

        DB::commit();

        return response()->json($data);
    }


    public function verifyOTP(Request $request)
    {

        $rules = [
            'id' => 'required',
            'otp' => 'required'
        ];

        //$input     = $request->only('user_id', 'photo', 'zip_code','dob', 'gender', 'type_mom', 'industry_id', 'company_id', 'company_code', 'screen_status');

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->messages()]);
        }
        // $name = $request->name;
        // $email    = $request->email;
        // $password = $request->password;

        $user = User::find($request->id);

        if (empty($user)) {
            return response()->json(['success' => false, 'error' => array('id' => 'not found data! wrong user id')]);
        }

        if ($user->otp != $request->otp)
            return response()->json(['success' => false, 'error' => array('otp' => 'wrong otp')]);

        $user->email_verified_at = Carbon::now();
        $user->otp = null;
        $user->save();

        return response()->json(['success' => true, 'message' => 'successfully email verified.']);
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        echo "test";
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }




    public function forgot(Request $request)
    {

        //$credentials = request()->validate(['email' => 'required|email']);

        $rules = [

            'email' => 'required|email'
        ];

        //$input     = $request->only('user_id', 'photo', 'zip_code','dob', 'gender', 'type_mom', 'industry_id', 'company_id', 'company_code', 'screen_status');

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->messages()->all()]);
        }


        $user = User::where('email', $request->email)->first();
        if ($user === null) {
            // user doesn't exist
            return response()->json(['success' => false, 'message' => array('this email is not exists')]);
        }



        Password::sendResetLink($request->all());

        $data['success'] = true;
        $data['message'] = array('Reset password link sent on your email id.');
        $data['data'] = array();

        return response()->json($data);
    }




    public function reset(Request $request)
    {


        $rules = [

            'email'    => 'required|email',
            'token' => 'required|string',
            'password' => 'required|string|min:6|max:20|confirmed',
            //'hear_about_us' => 'required',

        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->messages()->all()]);
        }

        // $reset_password_status = Password::reset($request->all(), function ($user, $password) {
        //     dd($user);
        //     $user->password = $password;
        //     $user->save();
        // });

        $response = $this->broker()->reset(
            $this->credentials($request),
            function ($user, $password) {
                $this->resetPassword($user, $password);
            }
        );
        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        return $response == Password::PASSWORD_RESET
            ? $this->sendResetResponse($response)
            : $this->sendResetFailedResponse($request, $response);


        // if ($reset_password_status == Password::INVALID_TOKEN) {
        //     return response()->json(["success" => false ,"message" => array("msg" => "Invalid token provided")], 400);
        // }

        // return response()->json([ "success" => true ,"message" => array("Password has been successfully changed")]);
    }



    protected function credentials(Request $request)
    {
        return $request->only(
            'email',
            'password',
            'password_confirmation',
            'token'
        );
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
     * @param  string  $password
     * @return void
     */
    protected function resetPassword($user, $password)
    {
        $user->password = bcrypt($password);

        $user->setRememberToken(Str::random(60));

        $user->save();

        event(new PasswordReset($user));

        $this->guard()->login($user);
    }
    public function broker()
    {
        return Password::broker();
    }


    protected function guard()
    {
        return Auth::guard();
    }


    protected function sendResetResponse($response)
    {
        return response()->json(["success" => true, "message" => array(trans($response))]);
    }

    protected function sendResetFailedResponse(Request $request, $response)
    {

        return response()->json(["success" => false, "message" => array(trans($response))]);
    }

    /**

     * @OA\Get(
     * path="/api/industry/", 
     * operationId="Industry",
     * tags={"Industry List"},
     * summary="Industry List",
     * description="Industry List",
      
     *      @OA\Response(
     *          response=201,
     *          description="Register Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Register Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */

    public function industry()
    {
        $company = Industry::get();

        $data['success'] = true;
        $data['message'] = 'get list.';
        $data['data'] = $company;

        return response()->json($data);
    }





    public function welcomeScreenStatusUpdate(Request $request)
    {

        $rules = [
            'id' => 'required',
            'welcome_screen_status' => 'required'
        ];

        //$input     = $request->only('user_id', 'photo', 'zip_code','dob', 'gender', 'type_mom', 'industry_id', 'company_id', 'company_code', 'screen_status');

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->messages()]);
        }
        // $name = $request->name;
        // $email    = $request->email;
        // $password = $request->password;

        $user = User::find($request->id);

        if (empty($user)) {
            return response()->json(['success' => false, 'error' => array('id' => 'not found data! wrong user id')]);
        }

        $user->welcome_screen_status = $request->welcome_screen_status;
        $user->save();

        return response()->json(['success' => true, 'message' => 'updated successfully welcome screen status.']);
    }
}
