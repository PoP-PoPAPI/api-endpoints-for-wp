<?php
namespace PoP\APIEndpointsForWP;

class EndpointUtils
{
    /**
     * Indicate if the URI ends with the given endpoint
     *
     * @param string $uri
     * @param string $endpointURI
     * @return boolean
     */
    public static function getSlashedURI(): string
    {
        $uri = $_SERVER['REQUEST_URI'];

        // Remove everything after "?" and "#"
        $symbols = ['?', '#'];
        foreach ($symbols as $symbol) {
            $symbolPos = strpos($uri, $symbol);
            if ($symbolPos !== false) {
                $uri = substr($uri, 0, $symbolPos);
            }
        }
        return trailingslashit($uri);
    }
}
