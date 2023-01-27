<?php

namespace App\Actions\Fortify;

use App\Helper\Reply;
use App\Models\Setting;
use Illuminate\Auth\Events\Failed;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\LoginRateLimiter;
use GuzzleHttp\Client;

class AttemptToAuthenticate
{
    /**
     * The guard implementation.
     *
     * @var \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected $guard;

    /**
     * The login rate limiter instance.
     *
     * @var \Laravel\Fortify\LoginRateLimiter
     */
    protected $limiter;

    /**
     * Create a new controller instance.
     *
     * @param  \Illuminate\Contracts\Auth\StatefulGuard  $guard
     * @param  \Laravel\Fortify\LoginRateLimiter  $limiter
     * @return void
     */
    public function __construct(StatefulGuard $guard, LoginRateLimiter $limiter)
    {
        $this->guard = $guard;
        $this->limiter = $limiter;
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  callable  $next
     * @return mixed
     */

    public function handle($request, $next)
    {
        $setting = Setting::first();

        if ($setting->google_recaptcha_status == 'active')
        {
            $gRecaptchaResponseInput = 'g-recaptcha-response';
            $gRecaptchaResponse = $request->{$gRecaptchaResponseInput};

            $gRecaptchaResponse = $setting->google_recaptcha_v2_status == 'active' ? $gRecaptchaResponse : $request->g_recaptcha;

            if (is_null($gRecaptchaResponse) ) {
                return $this->googleRecaptchaMessage();
            }

            $secret = $setting->google_recaptcha_v2_status == 'active' ? $setting->google_recaptcha_v2_secret_key : Setting::first()->google_recaptcha_v3_secret_key;

            $validateRecaptcha = $this->validateGoogleRecaptcha($gRecaptchaResponse, $secret);

            if (!$validateRecaptcha) {
                return $this->googleRecaptchaMessage();
            }
        }

        if (Fortify::$authenticateUsingCallback) {
            return $this->handleUsingCustomCallback($request, $next);
        }

        if ($this->guard->attempt(
            $request->only(Fortify::username(), 'password'),
            $request->filled('remember'))
        ) {
            return $next($request);
        }

        $this->throwFailedAuthenticationException($request);
    }

    /**
     * Attempt to authenticate using a custom callback.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  callable  $next
     * @return mixed
     */
    protected function handleUsingCustomCallback($request, $next)
    {
        $user = call_user_func(Fortify::$authenticateUsingCallback, $request);

        if (! $user) {
            $this->fireFailedEvent($request);

            return $this->throwFailedAuthenticationException($request); /* @phpstan-ignore-line */
        }

        $this->guard->login($user, $request->filled('remember'));

        return $next($request);
    }

    /**
     * Throw a failed authentication validation exception.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function throwFailedAuthenticationException($request)
    {
        $this->limiter->increment($request);

        throw ValidationException::withMessages([
            Fortify::username() => [trans('auth.failed')],
        ]);
    }

    /**
     * Fire the failed authentication attempt event with the given arguments.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function fireFailedEvent($request)
    {
        event(new Failed(config('fortify.guard'), null, [
            Fortify::username() => $request->{Fortify::username()},
            'password' => $request->password,
        ]));
    }

    public function validateGoogleRecaptcha($googleRecaptchaResponse, $secret)
    {
        $client = new Client();

        $googleRecaptchaResponse = is_null($googleRecaptchaResponse) ? '' : $googleRecaptchaResponse;

        $response = $client->post('https://www.google.com/recaptcha/api/siteverify',
            [
                'form_params' => [
                    'secret' => $secret,
                    'response' => $googleRecaptchaResponse
                ]
            ]
        );
        $body = json_decode((string)$response->getBody());

        return $body->success;
    }

    public function googleRecaptchaMessage()
    {
        return Reply::error(__('auth.recaptchaFailed'));
    }

}
