<?php
namespace PoP\APIEndpointsForWP;

class Environment
{
    public const GRAPHQL_API_ENDPOINT = 'GRAPHQL_API_ENDPOINT';
    public const REST_API_ENDPOINT = 'REST_API_ENDPOINT';
    public const NATIVE_API_ENDPOINT = 'NATIVE_API_ENDPOINT';

    public static function getGraphQLAPIEndpoint(): string
    {
        return isset($_ENV[self::GRAPHQL_API_ENDPOINT]) ? $_ENV[self::GRAPHQL_API_ENDPOINT] : 'api/graphql';
    }

    public static function getRESTAPIEndpoint(): string
    {
        return isset($_ENV[self::REST_API_ENDPOINT]) ? $_ENV[self::REST_API_ENDPOINT] : 'api/rest';
    }

    public static function getNativeAPIEndpoint(): string
    {
        return isset($_ENV[self::NATIVE_API_ENDPOINT]) ? $_ENV[self::NATIVE_API_ENDPOINT] : 'api';
    }
}
