<?php
use \Slim\Http\Request;
use \Slim\Http\Response;
use App\Controller\TokenController as Token;

use \Neomerx\JsonApi\Encoder\Encoder;
use \Neomerx\JsonApi\Document\Error;

use App\Model\User;

/*
 * Auth && ACL Middleware
 */
$app->add(function (Request $request, Response $response, $next) {
    // If path is "/api/token" or "/api/user/change-password" or "/api/user/request-password-reset" or "/api/user/reset-password" no need authorization process
    $path = $request->getUri()->getPath();
    if (in_array($path, ['/api/token', '/api/user/request-password-reset', '/api/user/reset-password'])) {
        return $next($request, $response);
    }
    // If method OPTIONS no need authorization process
    $method = $request->getMethod();
    if ($method == 'OPTIONS') {
        return $next($request, $response);
    }
    // If request has the correct token, passed it to action
    if ($request->getHeader('Authorization')) {
        $token = explode(' ', $request->getHeader('Authorization')[0])[1];
        if (Token::validateToken($token)) {
            $user = User::findUserByAccessToken($token);
            if ($user) {
                $_SESSION['user'] = $user;

                $isAllowed = false;

                $route = $request->getAttribute('route');

                if ($route) {
                    if ($this->acl->hasResource('route' . $route->getPattern())) {
                        $isAllowed = $isAllowed || $this->acl->isAllowed($user->role->name, 'route' . $route->getPattern(), strtolower($request->getMethod()));
                    }
                    if ($this->acl->hasResource('callable/' . $route->getCallable())) {
                        $isAllowed = $isAllowed || $this->acl->isAllowed($user->role->name, 'callable/' . $route->getCallable());
                    }
                    if (!$isAllowed) {
                        $error = new Error(
                            null,
                            null,
                            '403',
                            '403',
                            'Not allowed',
                            $user->role->name . ' is not allowed access to this location.'
                        );

                        $result = Encoder::instance()->encodeError($error);

                        return $this->renderer->jsonApiRender($response, 403, $result);
                    }
                }

                return $next($request, $response);
            }
        }
    }

    $error = new Error(
        null,
        null,
        '401',
        '401',
        'Not authorized',
        'The user must be authorized'
    );

    $result = Encoder::instance()->encodeError($error);

    return $this->renderer->jsonApiRender($response, 401, $result);
});

/*
 * Logger
 */
$app->add(function (Request $request, Response $response, $next) {
    $logger = $this->logger;
    $response = $next($request, $response);
    $uri = $request->getUri()->getPath();
    $statusCode = $response->getStatusCode();
    switch ($response->getStatusCode()) {
        case 500:
            $logger->addCritical('Oops!!! the server got 500 error', [
                'ip' => $request->getAttribute('ip_address'),
                'uri' => $uri,
                'status' => $statusCode,
            ]);
            break;
        case 404:
            $logger->addWarning('Someone calling un-existing API action', [
                'ip' => $request->getAttribute('ip_address'),
                'uri' => $uri,
                'status' => $statusCode,
            ]);
            break;
        case 401:
            $logger->addWarning('Someone calling API action without access', [
                'ip' => $request->getAttribute('ip_address'),
                'uri' => $uri,
                'status' => $statusCode,
            ]);
            break;
        default:
            $logger->addInfo('Someone calling existing API action', [
                'ip' => $request->getAttribute('ip_address'),
                'uri' => $uri,
                'status' => $statusCode,
            ]);
            break;
    }
    return $response;
});