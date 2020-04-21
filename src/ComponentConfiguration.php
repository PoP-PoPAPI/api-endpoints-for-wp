<?php

declare(strict_types=1);

namespace PoP\APIEndpointsForWP;

use PoP\ComponentModel\ComponentConfiguration\AbstractComponentConfiguration;

class ComponentConfiguration extends AbstractComponentConfiguration
{
    private static $getGraphQLAPIEndpoint;
    private static $getRESTAPIEndpoint;
    private static $getNativeAPIEndpoint;

    public static function getGraphQLAPIEndpoint(): string
    {
        // Define properties
        $envVariable = Environment::GRAPHQL_API_ENDPOINT;
        $selfProperty = &self::$getGraphQLAPIEndpoint;
        $callback = [Environment::class, 'getGraphQLAPIEndpoint'];

        // Initialize property from the environment/hook
        self::maybeInitEnvironmentVariable(
            $envVariable,
            $selfProperty,
            $callback
        );
        return $selfProperty;
    }

    public static function getRESTAPIEndpoint(): string
    {
        // Define properties
        $envVariable = Environment::REST_API_ENDPOINT;
        $selfProperty = &self::$getRESTAPIEndpoint;
        $callback = [Environment::class, 'getRESTAPIEndpoint'];

        // Initialize property from the environment/hook
        self::maybeInitEnvironmentVariable(
            $envVariable,
            $selfProperty,
            $callback
        );
        return $selfProperty;
    }

    public static function getNativeAPIEndpoint(): string
    {
        // Define properties
        $envVariable = Environment::NATIVE_API_ENDPOINT;
        $selfProperty = &self::$getNativeAPIEndpoint;
        $callback = [Environment::class, 'getNativeAPIEndpoint'];

        // Initialize property from the environment/hook
        self::maybeInitEnvironmentVariable(
            $envVariable,
            $selfProperty,
            $callback
        );
        return $selfProperty;
    }
}
