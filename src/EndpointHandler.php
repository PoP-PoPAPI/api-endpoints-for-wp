<?php
namespace PoP\APIEndpointsForWP;

use PoP\APIEndpointsForWP\EndpointUtils;
use PoP\APIEndpointsForWP\ComponentConfiguration;

class EndpointHandler
{
    /**
     * GraphQL endpoint
     *
     * @var string
     */
    protected $graphQLAPIEndpoint;
    /**
     * REST endpoint
     *
     * @var string
     */
    protected $restAPIEndpoint;
    /**
     * Native API endpoint
     *
     * @var string
     */
    protected $nativeAPIEndpoint;

    /**
     * Initialize the endpoints
     *
     * @return void
     */
    public function init(): void
    {
        if ($this->isGraphQLAPIEnabled()) {
            $this->graphQLAPIEndpoint = ComponentConfiguration::getGraphQLAPIEndpoint();
        }
        if ($this->isRESTAPIEnabled()) {
            $this->restAPIEndpoint = ComponentConfiguration::getRESTAPIEndpoint();
        }
        if ($this->isNativeAPIEnabled()) {
            $this->nativeAPIEndpoint = ComponentConfiguration::getNativeAPIEndpoint();
        }

        /**
         * Register the endpoints
         */
        add_action(
            'init',
            [$this, 'addRewriteEndpoints']
        );
        add_filter(
            'query_vars',
            [$this, 'addQueryVar'],
            10,
            1
        );

        /**
         * Process the request to find out if it is any of the endpoints
         */
        add_action(
            'parse_request',
            [$this, 'parseRequest']
        );
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
     * Check if REST has been enabled
     *
     * @return boolean
     */
    protected function isRESTAPIEnabled(): bool
    {
        return class_exists('\PoP\RESTAPI\Component') && \PoP\RESTAPI\Component::isEnabled();
    }

    /**
     * Check if the PoP API has been enabled
     *
     * @return boolean
     */
    protected function isNativeAPIEnabled(): bool
    {
        return class_exists('\PoP\API\Component') && \PoP\API\Component::isEnabled();
    }

    /**
     * Indicate this is an API request
     *
     * @return void
     */
    protected function setDoingAPI(): void
    {
        // Set the params on the request, to emulate that they were added by the user
        $_REQUEST[\GD_URLPARAM_SCHEME] = \POP_SCHEME_API;
    }
    /**
     * Indicate this is a GraphQL request
     *
     * @return void
     */
    private function setDoingGraphQL(): void
    {
        // Set the params on the request, to emulate that they were added by the user
        $this->setDoingAPI();
        // Include qualified namespace here (instead of `use`) since we do didn't know if component is installed
        $_REQUEST[\GD_URLPARAM_DATASTRUCTURE] = \PoP\GraphQLAPI\DataStructureFormatters\GraphQLDataStructureFormatter::getName();
    }
    /**
     * Indicate this is a REST request
     *
     * @return void
     */
    private function setDoingREST(): void
    {
        // Set the params on the request, to emulate that they were added by the user
        $this->setDoingAPI();
        // Include qualified namespace here (instead of `use`) since we do didn't know if component is installed
        $_REQUEST[\GD_URLPARAM_DATASTRUCTURE] = \PoP\RESTAPI\DataStructureFormatters\RESTDataStructureFormatter::getName();
    }

    /**
     * Process the request to find out if it is any of the endpoints
     *
     * @return void
     */
    public function parseRequest(): void
    {
        // Check if the URL ends with either /api/graphql/ or /api/rest/ or /api/
        $uri = EndpointUtils::getSlashedURI();
        if (!empty($this->graphQLAPIEndpoint) && $this->doesEndpointEndWith($uri, $this->graphQLAPIEndpoint)) {
            $this->setDoingGraphQL();
        } elseif (!empty($this->restAPIEndpoint) && $this->doesEndpointEndWith($uri, $this->restAPIEndpoint)) {
            $this->setDoingREST();
        } elseif (!empty($this->nativeAPIEndpoint) && $this->doesEndpointEndWith($uri, $this->nativeAPIEndpoint)) {
            $this->setDoingAPI();
        }
    }

    /**
     * Add the endpoints to WordPress
     *
     * @return void
     */
    public function addRewriteEndpoints()
    {
        if (!empty($this->graphQLAPIEndpoint)) {
            add_rewrite_endpoint($this->graphQLAPIEndpoint, EP_ALL);
        }
        if (!empty($this->restAPIEndpoint)) {
            add_rewrite_endpoint($this->restAPIEndpoint, EP_ALL);
        }
        if (!empty($this->nativeAPIEndpoint)) {
            add_rewrite_endpoint($this->nativeAPIEndpoint, EP_ALL);
        }
    }

    /**
     * Add the endpoint query vars
     *
     * @param array $query_vars
     * @return void
     */
    public function addQueryVar($query_vars)
    {
        if (!empty($this->graphQLAPIEndpoint)) {
            $query_vars[] = $this->graphQLAPIEndpoint;
        }
        if (!empty($this->restAPIEndpoint)) {
            $query_vars[] = $this->restAPIEndpoint;
        }
        if (!empty($this->nativeAPIEndpoint)) {
            $query_vars[] = $this->nativeAPIEndpoint;
        }
        return $query_vars;
    }

    /**
     * Indicate if the URI ends with the given endpoint
     *
     * @param string $uri
     * @param string $endpointURI
     * @return boolean
     */
    private function doesEndpointEndWith(string $uri, string $endpointURI): bool
    {
        $endpointSuffix = '/' . trim($endpointURI, '/') . '/';
        return substr($uri, -1 * strlen($endpointSuffix)) == $endpointSuffix;
    }
}
