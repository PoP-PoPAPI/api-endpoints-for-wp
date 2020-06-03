<?php

declare(strict_types=1);

namespace PoP\APIEndpointsForWP;

use PoP\APIEndpoints\EndpointUtils;
use PoP\ComponentModel\ComponentConfiguration\EnvironmentValueHelpers;
use PoP\ComponentModel\ComponentConfiguration\ComponentConfigurationTrait;

class ComponentConfiguration
{
    use ComponentConfigurationTrait;

    private static $isGraphQLAPIEndpointDisabled;
    private static $getGraphQLAPIEndpoint;
    private static $isRESTAPIEndpointDisabled;
    private static $getRESTAPIEndpoint;
    private static $isNativeAPIEndpointDisabled;
    private static $getNativeAPIEndpoint;

    public static function isGraphQLAPIEndpointDisabled(): bool
    {
        // Define properties
        $envVariable = Environment::DISABLE_GRAPHQL_API_ENDPOINT;
        $selfProperty = &self::$isGraphQLAPIEndpointDisabled;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }

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

    public static function isRESTAPIEndpointDisabled(): bool
    {
        // Define properties
        $envVariable = Environment::DISABLE_REST_API_ENDPOINT;
        $selfProperty = &self::$isRESTAPIEndpointDisabled;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

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

    public static function isNativeAPIEndpointDisabled(): bool
    {
        // Define properties
        $envVariable = Environment::DISABLE_NATIVE_API_ENDPOINT;
        $selfProperty = &self::$isNativeAPIEndpointDisabled;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

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
