<?php
namespace PoP\APIEndpointsForWP;

use PoP\Root\Component\AbstractComponent;
use PoP\APIEndpointsForWP\EndpointHandler;

/**
 * Initialize component
 */
class Component extends AbstractComponent
{
    // const VERSION = '0.1.0';

    /**
     * Boot component
     *
     * @return void
     */
    public static function beforeBoot()
    {
        parent::beforeBoot();

        // Initialize services
        (new EndpointHandler())->init();
    }
}
