<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use PragmaRX\Google2FA\Google2FA;

use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

use Endroid\QrCode\QrCode;

class TwoFactorRegister extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function showQRCodeForm()
    {
        $user = auth()->user();
        $google2fa = new Google2FA();

        $user->google_secret_key = $google2fa->generateSecretKey();
        $user->save();
        

        $qrcode_image = $google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $user->google_secret_key
        );

        

        return view('auth.register-2fa', compact('qrcode_image'));
    }

    public function verifyTwoFactorAuth(\Illuminate\Http\Request $request)
    {
        
        $user = auth()->user();
        $google2fa = new Google2FA();

        $valid = $google2fa->verifyKey(
            $user->google_secret_key,
            $request->code,
            4
        );

        if ($valid) {
            return redirect($this->redirectPath());
        }

        return redirect()->back()->withErrors(['code' => 'Invalid 2FA code']);
    }

    public function showTwoFactorForm()
    {
        return view('auth.verify-2fa');
    }

    /**
     * Verify the two-factor authentication code.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verifyTwoFactor(Request $request)
    {
        $user = $request->user();
        $google2fa = new Google2FA();

        $valid = $google2fa->verifyKey(
            $user->google_secret_key,
            $request->input('code')
        );

        if ($valid) {
            return redirect()->intended($this->redirectPath());
        }

        return redirect()->back()->withErrors(['code' => 'Invalid 2FA code']);
    }
}
