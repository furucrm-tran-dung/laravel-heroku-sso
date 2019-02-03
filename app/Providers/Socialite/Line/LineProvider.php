<?php

namespace App\Providers\Socialite\Line;

use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\ProviderInterface;
use Laravel\Socialite\Two\User;

class LineProvider extends AbstractProvider implements ProviderInterface
{
    protected $scopes = ['openid', 'profile', 'email'];
    protected $scopeSeparator = ' ';
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase('https://access.line.me/oauth2/v2.1/authorize', $state);
    }

    protected function getTokenUrl()
    {
        return 'https://api.line.me/oauth2/v2.1/token';
    }

    public function getAccessTokenResponse($code)
    {
        $response = $this->getHttpClient()->post($this->getTokenUrl(), [
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
            'form_params' => [
                'grant_type' => 'authorization_code',
                'code' => $code,
                'redirect_uri' => $this->redirectUrl,
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret
            ],
        ]);
        return json_decode($response->getBody(),true);
    }

    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get('https://api.line.me/v2/profile', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ],
        ]);
        return json_decode($response->getBody(), true);
    }

    protected function mapUserToObject(array $user)
    {
        return (new User())->setRaw($user)->map([
            'id' => $user['userId'],
            'name' => $user['displayName']
        ]);
    }
}