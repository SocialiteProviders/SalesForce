<?php

namespace SocialiteProviders\SalesForce;

use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\ProviderInterface;
use Laravel\Socialite\Two\User;

class Provider extends AbstractProvider implements ProviderInterface
{
    /**
     * The separating character for the requested scopes.
     *
     * @var string
     */
    protected $scopeSeparator = ' ';

    /**
     * {@inheritdoc}
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase(
            'https://login.salesforce.com/services/oauth2/authorize', $state
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenUrl()
    {
        return 'https://login.salesforce.com/services/oauth2/token';
    }

    /**
     * {@inheritdoc}
     */
    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get(
            'https://login.salesforce.com/services/oauth2/userinfo', [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.$token,
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * {@inheritdoc}
     */
    protected function mapUserToObject(array $user)
    {
        return (new User())->setRaw($user)->map([
            'salesforce_id' => $user['user_id'],
            'salesforce_link' => $user['sub'],
            'salesforce_username' => $user['preferred_username'],
            'salesforce_organization_id' => $user['organization_id'],
            'salesforce_nickname' => $user['nickname'],
            'name' => $user['name'],
            'first_name' => $user['given_name'],
            'last_name' => $user['family_name'],
            'email' => isset($user['email']) ? $user['email'] : null,
            'email_verified' => $user['email_verified'],
            'avatar' => $user['picture'],
            'salesforce_urls' => $user['urls'],
            'salesforce_address' => $user['address'],
            'salesforce_active' => $user['active'],
            'salesforce_user_type' => $user['user_type'],
            'salesforce_language' => $user['salesforce_language'],
            'salesforce_utc_offset' => $user['utcOffset'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenFields($code)
    {
        return array_merge(parent::getTokenFields($code), [
            'grant_type' => 'authorization_code',
        ]);
    }
}
