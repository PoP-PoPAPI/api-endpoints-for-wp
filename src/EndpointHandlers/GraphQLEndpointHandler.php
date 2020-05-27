<?php

declare(strict_types=1);

namespace PoP\APIEndpointsForWP\EndpointHandlers;

use PoP\APIEndpointsForWP\EndpointHandlers\AbstractEndpointHandler;
use PoP\APIEndpointsForWP\ComponentConfiguration;

class GraphQLEndpointHandler extends AbstractEndpointHandler
{
    /**
     * Initialize the endpoints
     *
     * @return void
     */
    public function initialize(): void
    {
        if ($this->isGraphQLAPIEnabled()) {
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
        return ComponentConfiguration::getGraphQLAPIEndpoint();
    }

    /**
     * Check if GrahQL has been enabled
     *
     * @return boolean
     */
    protected function isGraphQLAPIEnabled(): bool
    {
        return class_exists('\PoP\GraphQLAPI\Component') && \PoP\GraphQLAPI\Component::isEnabled();
    }

    /**
     * Indicate this is a GraphQL request
     *
     * @return void
     */
    protected function executeEndpoint(): void
    {
        // Set the params on the request, to emulate that they were added by the user
        $_REQUEST[\GD_URLPARAM_SCHEME] = \POP_SCHEME_API;
        // Include qualified namespace here (instead of `use`) since we do didn't know if component is installed
        $_REQUEST[\GD_URLPARAM_DATASTRUCTURE] = \PoP\GraphQLAPI\DataStructureFormatters\GraphQLDataStructureFormatter::getName();
        // Enable hooks
        \do_action('EndpointHandler:setDoingGraphQL');
    }
}
