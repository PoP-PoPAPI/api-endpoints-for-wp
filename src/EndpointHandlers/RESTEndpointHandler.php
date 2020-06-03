<?php

declare(strict_types=1);

namespace PoP\APIEndpointsForWP\EndpointHandlers;

use PoP\APIEndpointsForWP\EndpointHandlers\AbstractEndpointHandler;
use PoP\APIEndpointsForWP\ComponentConfiguration;

class RESTEndpointHandler extends AbstractEndpointHandler
{
    /**
     * Initialize the endpoints
     *
     * @return void
     */
    public function initialize(): void
    {
        if ($this->isRESTAPIEnabled()) {
            parent::initialize();
        }
    }

    /**
     * Provide the endpoint
     *
     * @var string
     */
    protected function getEndpoint(): string
    {
        return ComponentConfiguration::getRESTAPIEndpoint();
    }

    /**
     * Check if REST has been enabled
     *
     * @return boolean
     */
    protected function isRESTAPIEnabled(): bool
    {
        return
            class_exists('\PoP\RESTAPI\Component')
            && \PoP\RESTAPI\Component::isEnabled()
            && !ComponentConfiguration::isRESTAPIEndpointDisabled();
    }

    /**
     * Indicate this is a REST request
     *
     * @return void
     */
    protected function executeEndpoint(): void
    {
        // Set the params on the request, to emulate that they were added by the user
        $_REQUEST[\GD_URLPARAM_SCHEME] = \POP_SCHEME_API;
        // Include qualified namespace here (instead of `use`) since we do didn't know if component is installed
        $_REQUEST[\GD_URLPARAM_DATASTRUCTURE] = \PoP\RESTAPI\DataStructureFormatters\RESTDataStructureFormatter::getName();
        // Enable hooks
        \do_action('EndpointHandler:setDoingREST');
    }
}
