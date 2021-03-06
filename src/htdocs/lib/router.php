<?php
class Router {

    private $routes;

    function __construct($routes) {
        $this->routes = $routes;
    }

    function findRoute($uri) {
        $uri = array_values(array_filter(explode('/', $uri)));
        foreach ($this->routes as $path => $route) {
            $path = explode(' ', $path);
            if ($path[0] !== $_SERVER['REQUEST_METHOD']) {
                continue;
            }
            $arguments = Router::tryParse($path[1], $uri);
            if ($arguments !== null) {
                return array($route, $arguments);
            }
        }
        return array(null, null);
    }

    private static function tryParse($path, $uri) {
        $path = array_values(array_filter(explode('/', $path)));
        $arguments = array();
        $i = 0;
        foreach ($path as $segment) {
            $optional = false;
            if (substr($segment, 0, 1) === '[' && substr($segment, -1, 1) === ']') {
                $optional = true;
                $segment = substr($segment, 1, -1);
            }
            if (substr($segment, 0, 1) === ':') {
                $argName = substr($segment, 1);
                if ($argName === 'device') {
                    list($device, $segmentCount) = Router::tryParseDevice(array_slice($uri, $i));
                    if ($device === null) {
                        if ($optional) {
                            $device = CONFIG['devices'][0];
                            $segmentCount = 0;
                        } else {
                            return null;
                        }
                    }
                    $arguments['device'] = $device;
                    $i += $segmentCount;
                } else {
                    $arguments[$argName] = $uri[$i++];
                }
            } else if ($uri[$i++] === $segment) {
                continue;
            } else {
                return null;
            }
        }
        if ($i == count($uri)) {
            return $arguments;
        } else {
            return null;
        }
    }

    private static function tryParseDevice($uri) {
        foreach (CONFIG['devices'] as $device) {
            $deviceName = array_values(array_filter(explode('/', $device['name'])));
            if (Router::arrayStartsWith($deviceName, $uri)) {
                return array($device, count($deviceName));
            }
        }
        return array(null, 0);
    }

    private static function arrayStartsWith($needle, $haystack) {
        $needle = array_values($needle);
        $haystack = array_values($haystack);
        if (count($needle) > count($haystack)) {
            return false;
        }
        foreach ($needle as $i => $e) {
            if ($haystack[$i] != $e) {
                return false;
            }
        }
        return true;
    }

    function createLink($controller, $action, $device = null, $args = array(), $queryArgs = array()) {
        $isDefaultDevice = CONFIG['devices'][0]['name'] == $device['name'];

        $link = '';
        $path = null;
        foreach ($this->routes as $p => $r) {
            if ($r[0] == $controller && $r[1] == $action) {
                $path = $p;
                break;
            }
        }
        if ($path === null) {
            return $link;
        }
        $path = explode(' ', $path)[1];
        $path = array_values(array_filter(explode('/', $path)));
        foreach ($path as $segment) {
            $optional = false;
            if (substr($segment, 0, 1) === '[' && substr($segment, -1, 1) === ']') {
                $optional = true;
                $segment = substr($segment, 1, -1);
            }
            if (substr($segment, 0, 1) === ':') {
                $argName = substr($segment, 1);
                if ($argName === 'device') {
                    if (!$optional || !$isDefaultDevice) {
                        $link .= '/'.$device['name'];
                    }
                } else {
                    $link .= '/'.$args[$argName];
                }
            } else {
                $link .= '/'.$segment;
            }
        }

        if ($link === '') {
            $link = '/';
        }

        $queryArgsAdded = false;
        foreach ($queryArgs as $k => $v) {
            if ($queryArgsAdded) {
                $link .= '&';
            } else {
                $link .= '?';
                $queryArgsAdded = true;
            }
            $link .= "${k}=${v}";
        }
    
        return $link;    
    }

}

function l($controller, $action, $device = null, $args = array(), $queryArgs = array()) {
    global $currentDevice;
    global $router;

    if ($device === null) {
        $device = $currentDevice;
    }

    return $router->createLink($controller, $action, $device, $args, $queryArgs);
}
?>