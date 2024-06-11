<?php
namespace app\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Access\AuthorizationException;


class VerifyEmailController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */
    protected $redirectTo = '/login';

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    public function show(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json('Email Verified');
        }
        else {
            return response()->json('Email not verified');
        }
    }
    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
   public function verify(Request $request)
    {
        auth()->loginUsingId($request->route('id'));

        if ($request->route('id') != $request->user()->getKey()) {
            throw new AuthorizationException;
         return redirect($this->redirectPath());

        }
        if ($request->user()->hasVerifiedEmail()) {
            return response(['message'=>'Already verified']);
            // return redirect($this->redirectPath());
        }
        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }
        return response(['message'=>'Successfully verified']);
    }

    /**
     * Resend the email verification notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json('User already have verified email!', 422);
//            return redirect($this->redirectPath());
        }
        $request->user()->sendEmailVerificationNotification();
        return response()->json('The notification has been resubmitted');
       return back()->with('resent', true);
    }
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }
}