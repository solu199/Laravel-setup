<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    /**
     * 指定されたプロバイダーへリダイレクト
     */
    public function redirect(string $provider): RedirectResponse
    {
        // サポートされているプロバイダーを確認
        $this->validateProvider($provider);

        return Socialite::driver($provider)->redirect();
    }

    /**
     * プロバイダーからのコールバックを処理
     */
    public function callback(string $provider): RedirectResponse
    {
        $this->validateProvider($provider);

        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', '認証に失敗しました。');
        }

        // ユーザーを検索または作成
        $user = User::updateOrCreate(
            [
                'email' => $socialUser->getEmail(),
            ],
            [
                'name' => $socialUser->getName(),
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
                'avatar' => $socialUser->getAvatar(),
                'password' => bcrypt(str()->random(24)),
            ]
        );

        Auth::login($user, remember: true);

        return redirect()->intended('/dashboard');
    }

    /**
     * プロバイダーが有効か確認
     */
    protected function validateProvider(string $provider): void
    {
        $allowedProviders = ['google'];

        if (!in_array($provider, $allowedProviders)) {
            abort(404, 'サポートされていない認証プロバイダーです。');
        }
    }
}
