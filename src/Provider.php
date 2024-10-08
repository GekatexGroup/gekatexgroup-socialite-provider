<?php

namespace Gekatexgroup\GekatexgroupSocialiteProvider;

use Illuminate\Support\Arr;
use Laravel\Socialite\Two\ProviderInterface;
use SocialiteProviders\Manager\OAuth2\AbstractProvider;
use SocialiteProviders\Manager\OAuth2\User;

class Provider extends AbstractProvider implements ProviderInterface
{
    /**
     * Unique Provider Identifier.
     */
    public const IDENTIFIER = 'GEKATEXGROUPSSO';

    /**
     * URL of the Gekatex SSO Server
     */
    public const BASEURL = 'https://login.gekatexgroup.com';

    /**
     * {@inheritdoc}
     */
    protected $scopes = [''];

    /**
     * {@inheritdoc}
     */
    protected $scopeSeparator = ' ';

    /**
     * {@inheritdoc}
     */
    public static function additionalConfigKeys()
    {
        return [
            'host',
            'authorize_uri',
            'token_uri',
            'userinfo_uri',
            'userinfo_key',
            'user_id',
            'user_nickname',
            'user_name',
            'user_email',
            'user_avatar',
        ];
    }

    /**
     * Get the authentication URL for the provider.
     *
     * @param string $state
     *
     * @return string
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase($this->getGekatexGroupUrl('authorize_uri'), $state);
    }

    /**
     * Get the token URL for the provider.
     *
     * @return string
     */
    protected function getTokenUrl()
    {
        return $this->getGekatexGroupUrl('token_uri');
    }

    /**
     * Get the raw user for the given access token.
     *
     * @param string $token
     *
     * @return array
     */
    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get($this->getGekatexGroupUrl('userinfo_uri'), [
            'headers' => [
                'Authorization' => 'Bearer '.$token,
            ],
        ]);

        return (array) json_decode((string) $response->getBody(), true);
    }

    /**
     * Map the raw user array to a Socialite User instance.
     *
     * @param array $user
     *
     * @return \Laravel\Socialite\User
     */
    protected function mapUserToObject(array $user)
    {
        $key = $this->getConfig('userinfo_key', null);
        $data = is_null($key) === true ? $user : Arr::get($user, $key, []);

        return (new User())->setRaw($data)->map([
            'id'       => $this->getUserData($data, 'id'),
            'nickname' => $this->getUserData($data, 'nickname'),
            'name'     => $this->getUserData($data, 'name'),
            'email'    => $this->getUserData($data, 'email'),
            'avatar'   => $this->getUserData($data, 'profile_photo_url'),
        ]);
    }

    /**
     * Get the POST fields for the token request.
     *
     * @param string $code
     *
     * @return array
     */
    protected function getTokenFields($code)
    {
        return array_merge(parent::getTokenFields($code), [
            'grant_type' => 'authorization_code',
        ]);
    }

    protected function getGekatexGroupUrl($type)
    {
        return rtrim(self::BASEURL, '/').'/'.ltrim(($this->getConfig($type, Arr::get([
            'authorize_uri' => 'oauth/authorize',
            'token_uri'     => 'oauth/token',
            'userinfo_uri'  => 'api/user',
        ], $type))), '/');
    }

    protected function getUserData($user, $key)
    {
        return Arr::get($user, $this->getConfig('user_'.$key, $key));
    }
}
