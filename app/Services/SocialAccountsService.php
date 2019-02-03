<?php

namespace App\Services;

use App\User;
use App\LinkedSocialAccount;
use Laravel\Socialite\Contracts\User as ProviderUser;

class SocialAccountsService
{
    public function find(ProviderUser $providerUser, $provider) {
        $account = LinkedSocialAccount::where('provider_name', $provider)
            ->where('provider_id', $providerUser->getId())
            ->first();
        return $account ? $account->user : null;
    }

    public function link(User $user, ProviderUser $providerUser, $provider) {
        if (LinkedSocialAccount::where('provider_name', $provider)->where('user_id', $user->id)->exists()) {
            return null;
        } else {
            $user->accounts()->create([
                'provider_id' => $providerUser->getId(),
                'provider_name' => $provider,
            ]);
            return $user;
        }
    }
}
