<?php

namespace SocialiteProviders\SalesForce;

use SocialiteProviders\Manager\SocialiteWasCalled;

class SalesForceExtendSocialite
{
    /**
     * Register the provider.
     *
     * @param \SocialiteProviders\Manager\SocialiteWasCalled $socialiteWasCalled
     */
    public function handle(SocialiteWasCalled $socialiteWasCalled)
    {
        $socialiteWasCalled->extendSocialite(
            'salesforce', __NAMESPACE__.'\Provider'
        );
    }
}
