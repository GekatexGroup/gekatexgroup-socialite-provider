<?php

namespace Gekatexgroup\GekatexgroupSocialiteProvider;

use SocialiteProviders\Manager\SocialiteWasCalled;

class GekatexGroupSSOExtendSocialite
{
    /**
     * Register the provider.
     *
     * @param \SocialiteProviders\Manager\SocialiteWasCalled $socialiteWasCalled
     */
    public function handle(SocialiteWasCalled $socialiteWasCalled)
    {
        $socialiteWasCalled->extendSocialite('gekatexgroupsso', Provider::class);
    }
}
