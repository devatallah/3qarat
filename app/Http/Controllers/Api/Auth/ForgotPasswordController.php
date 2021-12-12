<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:api');
    }


    public function sendResetLinkEmail(Request $request)
    {
        $this->validateEmail($request);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        try{
            $response = $this->broker()->sendResetLink(
                $request->only('email')
            );

        } catch (\Exception $e) {
            return mainResponse(false, "We Couldn't Send email", [], []);

        }

        
        return $response == Password::RESET_LINK_SENT
            ? $this->sendResetLinkResponse($response)
            : $this->sendResetLinkFailedResponse($request, $response);
    }

    protected function sendResetLinkResponse($response)
    {
            return mainResponse(true, $response, [], []);
    }

    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
            return mainResponse(false, $response, [], []);
    }

    protected function validateEmail(Request $request)
    {
        $request->validate(['email' => 'required|exists:users|email']);
    }

    public function broker()
    {
        return Password::broker();
    }
}
