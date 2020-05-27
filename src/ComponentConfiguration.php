<?php

declare(strict_types=1);

namespace PoP\APIEndpointsForWP;

use PoP\ComponentModel\ComponentConfiguration\ComponentConfigurationTrait;
use PoP\APIEndpoints\EndpointUtils;

class ComponentConfiguration
{
    use ComponentConfigurationTrait;

    private static $getGraphQLAPIEndpoint;
    private static $getRESTAPIEndpoint;
    private static $getNativeAPIEndpoint;

    public static function getGraphQLAPIEndpoint(): string
    {
        // Define properties
        $envVariable = Environment::GRAPHQL_API_ENDPOINT;
        $selfProperty = &self::$getGraphQLAPIEndpoint;
        $defaultValue = '/api/graphql/';
        $callback = [EndpointUtils::class, 'slashURI'];

        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }

    public static function getRESTAPIEndpoint(): string
    {
        // Define properties
        $envVariable = Environment::REST_API_ENDPOINT;
        $selfProperty = &self::$getRESTAPIEndpoint;
        $defaultValue = '/api/rest/';
        $callback = [EndpointUtils::class, 'slashURI'];

        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }

    public static function getNativeAPIEndpoint(): string
    {
        // Define properties
        $envVariable = Environment::NATIVE_API_ENDPOINT;
        $selfProperty = &self::$getNativeAPIEndpoint;
        $defaultValue = '/api/';
        $callback = [EndpointUtils::class, 'slashURI'];

        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }
}
