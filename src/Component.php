<?php

declare(strict_types=1);

namespace PoP\APIEndpointsForWP;

use PoP\Root\Component\AbstractComponent;
use PoP\APIEndpointsForWP\EndpointHandler;

/**
 * Initialize component
 */
class Component extends AbstractComponent
{
    // const VERSION = '0.1.0';

    public static function getDependedComponentClasses(): array
    {
        return [
            \PoP\API\Component::class,
        ];
    }

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
