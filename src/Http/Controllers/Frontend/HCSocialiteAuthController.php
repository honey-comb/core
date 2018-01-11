<?php

declare(strict_types = 1);

namespace HoneyComb\Core\Http\Controllers\Frontend;

use GuzzleHttp\Client;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use HoneyComb\Core\Services\HCUserService;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User;

/**
 * Class HCSocialiteAuthController
 * @package HoneyComb\Core\Http\Controllers\Frontend
 */
class HCSocialiteAuthController extends Controller
{
    /**
     * @var HCUserService
     */
    private $service;

    /**
     * HCFacebookAuthController constructor.
     * @param HCUserService $service
     */
    public function __construct(HCUserService $service)
    {
        $this->service = $service;
    }

    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function redirectToProvider(Request $request): RedirectResponse
    {
        return Socialite::driver($request->segment(3))->redirect();
    }

    /**
     * Obtain the user information from provider.
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws \Illuminate\Container\EntryNotFoundException
     */
    public function handleProviderCallback(Request $request): RedirectResponse
    {
        if (!$request->filled('code')) {
            logger()->info('Facebook: ' . $request->input('error') . ' : ' . $request->input('error_reason'));

            return redirect()->route('auth.login');
        }

        /** @var User $user */
        $user = Socialite::driver($request->segment(3))->user();

        if ($request->segment(3) == 'facebook' && is_null($user->email)) {
            return $this->deAuthorize($user);
        }

        auth()->login(
            $this->service->createOrUpdateUserProvider($user, $request->segment(3))
        );

        auth()->user()->updateLastLogin();

        return redirect(session('url.intended', url('/')));
    }

    /**
     * DeAuthorize user from app
     *
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    private function deAuthorize(User $user): RedirectResponse
    {
        // TODO display error for user

        $client = new Client;

        $client->delete("https://graph.facebook.com/{$user->id}/permissions",
            [
                'headers' => ['Accept' => 'application/json'],
                'form_params' => [
                    'access_token' => $user->token,
                ],
            ]
        );

        return redirect()->route('auth.login');
    }
}
