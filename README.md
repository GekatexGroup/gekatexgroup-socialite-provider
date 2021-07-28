# Gekatex Group Socialite Provider

This is a OAuth socialite provider to enable Gekatex Group login support via Laravel Socialite.

You will need access to the Gekatex Group Developers pages to setup a client before this will connect.

## Installation

Add the repo to your composer.json:
```json
"repositories": [
    {
        "type": "vcs",
        "url": "git@github.com:gekatexgroup/gekatexgroup-socialite-provider.git"
    }
]
```

Now run `composer require gekatexgroup/gekatexgroup-socialite-provider` to install the package.
`

## Setup

Please see the [Socialite providers base installation guide](https://socialiteproviders.com/usage/) then follow the additional steps below.

### Add configuration to `config/services.php`

```php
'gekatexgroupsso' => [    
  'client_id' => env('GEKATEXGROUPSSO_CLIENT_ID'),  
  'client_secret' => env('GEKATEXGROUPSSO_CLIENT_SECRET'),  
  'redirect' => env('GEKATEXGROUPSSO_REDIRECT_URI') 
],
```
Add the corresponding values to your .env file, with client properties from the SSO app.
```dotenv
GEKATEXGROUPSSO_CLIENT_ID=
GEKATEXGROUPSSO_CLIENT_SECRET=
GEKATEXGROUPSSO_REDIRECT_URI=
```

### Add provider event listener

Configure the package's listener to listen for `SocialiteWasCalled` events.

Add the event to your `listen[]` array in `app/Providers/EventServiceProvider`. See the [Base Installation Guide](https://socialiteproviders.com/usage/) for detailed instructions.

```php
protected $listen = [
    \SocialiteProviders\Manager\SocialiteWasCalled::class => [
        // ... other providers
        'Gekatexgroup\\GekatexgroupSocialiteProvider\\GekatexGroupSSOExtendSocialite@handle',
    ],
];
```

### Usage

You should now be able to use the provider like you would regularly use Socialite (assuming you have the facade installed):

```php
return Socialite::driver('gekatexgroupsso')->redirect();
```

### Returned User fields

- ``id``
- ``nickname``
- ``name``
- ``email``
- ``avatar``
