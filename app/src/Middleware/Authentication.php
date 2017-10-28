<?php

namespace App\Middleware;

use App\Common\Acl;
use App\Common\Auth;
use App\Common\JsonException;
use App\Model\AccessToken;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class Authentication
{
    /**
     * @var Acl
     */
    private $acl;

    /**
     * @var
     */
    private $settings;

    /**
     * Authentication constructor.
     *
     * @param Acl $acl
     * @param     $settings
     */
    public function __construct(Acl $acl, $settings)
    {
        $this->acl      = $acl;
        $this->settings = $settings;
    }

    /**
     * Execute the middleware.
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param callable               $next
     *
     * @return ResponseInterface
     * @throws JsonException
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        // Request has to have Authorization header
        if (!$request->getHeader('Authorization')) {
            throw new JsonException(null, 401, 'Not authorized', 'The user must be authorized');
        }

        // HTTP Authorization header available
        // Authorization: Bearer XXXXXXXXXXXXXXXXXXX
        // fetch XXXXXXXXXXXXX part
        $token = @explode(' ', @$request->getHeader('Authorization')[0]);
        $token = is_array($token) && (count($token) == 2) ? $token[1] : null;
        if (empty($token)) {
            throw new JsonException(null, 401, 'Not authorized', 'The user must be authorized');
        }

        // provided token must be valid
        if (!AccessToken::validateToken($token, $this->settings['accessToken'])) {
            throw new JsonException(null, 401, 'Not authorized', 'The user must be authorized');
        }

        // find user by token
        $user = AccessToken::getUserByToken($token);
        if (empty($user)) {
            throw new JsonException(null, 401, 'Not authorized', 'The user must be authorized');
        }

        Auth::setUser($user);

        $isAllowed = false;
        $route     = $request->getAttribute('route');

        if ($route) {
            // check access for the route
            $resource = Acl::buildResourceName(Acl::GUARD_TYPE_ROUTE, $route->getPattern());
            $privilege = Acl::getPrivilegeByHTTPMethod($request->getMethod());
            if ($this->acl->hasResource($resource)) {
                $isAllowed = $isAllowed || $this->acl->isAllowed($user->role->name, $resource, $privilege);
            }

            // check access for the callable
            $resource = Acl::buildResourceName(Acl::GUARD_TYPE_CALLABLE, $route->getCallable());
            $privilege = null;
            if ($this->acl->hasResource($resource)) {
                $isAllowed = $isAllowed || $this->acl->isAllowed($user->role->name, $resource, $privilege);
            }

            if (!$isAllowed) {
                throw new JsonException(null, 403, 'Not allowed', 'Access to this location is not allowed');
            }
        }

        // access allowed, move to next middleware
        return $next($request, $response);
    }
}
