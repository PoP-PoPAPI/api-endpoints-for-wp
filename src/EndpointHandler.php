<?php
namespace PoP\APIEndpointsForWP;

use PoP\APIEndpointsForWP\EndpointUtils;
use PoP\RESTAPI\DataStructureFormatters\RESTDataStructureFormatter;
use PoP\GraphQLAPI\DataStructureFormatters\GraphQLDataStructureFormatter;

class EndpointHandler {

    /**
     * GraphQL endpoint
     *
     * @var string
     */
    public $GRAPHQL_API_ENDPOINT;
    /**
     * REST endpoint
     *
     * @var string
     */
    public $REST_API_ENDPOINT;
    /**
     * Native API endpoint
     *
     * @var string
     */
    public $NATIVE_API_ENDPOINT;

    /**
     * Hook to set the GraphQL endpoint
     */
    public const HOOK_GRAPHQL_API_ENDPOINT = __CLASS__.':graphql_api_endpoint';
    /**
     * Hook to set the REST endpoint
     */
    public const HOOK_REST_API_ENDPOINT = __CLASS__.':rest_api_endpoint';
    /**
     * Hook to set the API endpoint
     */
    public const HOOK_NATIVE_API_ENDPOINT = __CLASS__.':native_api_endpoint';


    /**
     * Initialize the endpoints
     *
     * @return void
     */
    public function init(): void
    {
        /**
         * Let the admin override the default endpoints through hooks
         */
        if ($this->isGraphQLAPIEnabled()) {
            $this->GRAPHQL_API_ENDPOINT = apply_filters(
                self::HOOK_GRAPHQL_API_ENDPOINT,
                'api/graphql'
            );
        }
        if ($this->isRESTAPIEnabled()) {
            $this->REST_API_ENDPOINT = apply_filters(
                self::HOOK_REST_API_ENDPOINT,
                'api/rest'
            );
        }
        if ($this->isNativeAPIEnabled()) {
            $this->NATIVE_API_ENDPOINT = apply_filters(
                self::HOOK_NATIVE_API_ENDPOINT,
                'api'
            );
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
    protected function setDoingGraphQL(): void
    {
        // Set the params on the request, to emulate that they were added by the user
        $this->setDoingAPI();
        $_REQUEST[\GD_URLPARAM_DATASTRUCTURE] = GraphQLDataStructureFormatter::getName();
    }
    /**
     * Indicate this is a REST request
     *
     * @return void
     */
    protected function setDoingREST(): void
    {
        // Set the params on the request, to emulate that they were added by the user
        $this->setDoingAPI();
        $_REQUEST[\GD_URLPARAM_DATASTRUCTURE] = RESTDataStructureFormatter::getName();
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
     * Check if GrahQL has been enabled
     *
     * @return boolean
     */
    protected function isRESTAPIEnabled(): bool
    {
        return class_exists('\PoP\RESTAPI\Component') && \PoP\RESTAPI\Component::isEnabled();
    }

    /**
     * Check if the API has been enabled
     *
     * @return boolean
     */
    protected function isNativeAPIEnabled(): bool
    {
        return class_exists('\PoP\API\Component') && \PoP\API\Component::isEnabled();
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
        if ($this->isGraphQLAPIEnabled() && $this->doesEndpointEndWith($uri, $this->GRAPHQL_API_ENDPOINT)) {
            $this->setDoingGraphQL();
        } elseif ($this->isRESTAPIEnabled() && $this->doesEndpointEndWith($uri, $this->REST_API_ENDPOINT)) {
            $this->setDoingREST();
        } elseif ($this->isNativeAPIEnabled() && $this->doesEndpointEndWith($uri, $this->NATIVE_API_ENDPOINT)) {
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
        if ($this->isGraphQLAPIEnabled()) {
            add_rewrite_endpoint($this->GRAPHQL_API_ENDPOINT, EP_ALL);
        }
        if ($this->isRESTAPIEnabled()) {
            add_rewrite_endpoint($this->REST_API_ENDPOINT, EP_ALL);
        }
        if ($this->isNativeAPIEnabled()) {
            add_rewrite_endpoint($this->NATIVE_API_ENDPOINT, EP_ALL);
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
        if ($this->isGraphQLAPIEnabled()) {
            $query_vars[] = $this->GRAPHQL_API_ENDPOINT;
        }
        if ($this->isRESTAPIEnabled()) {
            $query_vars[] = $this->REST_API_ENDPOINT;
        }
        if ($this->isNativeAPIEnabled()) {
            $query_vars[] = $this->NATIVE_API_ENDPOINT;
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
        $endpointSuffix = '/'.trim($endpointURI, '/').'/';
        return substr($uri, -1*strlen($endpointSuffix)) == $endpointSuffix;
    }
}
