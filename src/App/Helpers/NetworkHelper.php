<?php
/**
 * User: shl
 * Date: 16.06.2018
 * Time: 0:02
 */

namespace App\Helpers;


class NetworkHelper
{
    /**
     * @var \Slim\App
     */
    static $slimApp;

    /**
     * @param array $serverParams $_SERVER array
     * @return mixed|string
     */
    static function getClientIp(Array $serverParams)
    {
        $ipaddress = '';
        if ($serverParams['HTTP_CLIENT_IP'] ?? '') {
            $ipaddress = $serverParams['HTTP_CLIENT_IP'];
        } else {
            if ($serverParams['HTTP_X_FORWARDED_FOR'] ?? '') {
                $ipaddress = $serverParams['HTTP_X_FORWARDED_FOR'];
            } else {
                if ($serverParams['HTTP_X_FORWARDED'] ?? '') {
                    $ipaddress = $serverParams['HTTP_X_FORWARDED'];
                } else {
                    if ($serverParams['HTTP_FORWARDED_FOR'] ?? '') {
                        $ipaddress = $serverParams['HTTP_FORWARDED_FOR'];
                    } else {
                        if ($serverParams['HTTP_FORWARDED'] ?? '') {
                            $ipaddress = $serverParams['HTTP_FORWARDED'];
                        } else {
                            if ($serverParams['REMOTE_ADDR'] ?? '') {
                                $ipaddress = $serverParams['REMOTE_ADDR'];
                            } else {
                                $ipaddress = false;
                            }
                        }
                    }
                }
            }
        }
        return $ipaddress;
    }

    static function path_for_route($routeName, $routeParams, $queryParams)
    {
        return self::$slimApp->getRouter()->pathFor($routeName, $routeParams, $queryParams);
    }

}