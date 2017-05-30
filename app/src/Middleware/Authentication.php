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
            return $this->failNotAuthorized();
        }

        // HTTP Authorization header available
        // Authorization: Bearer XXXXXXXXXXXXXXXXXXX
        // fetch XXXXXXXXXXXXX part
        $token = @explode(' ', @$request->getHeader('Authorization')[0]);
        $token = is_array($token) && (count($token) == 2) ? $token[1] : null;
        if (empty($token)) {
            return $this->failNotAuthorized();
        }

        // provided token must be valid
        if (!AccessToken::validateToken($token, $this->settings['accessToken'])) {
            return $this->failNotAuthorized();
        }

        // find user by token
        $user = AccessToken::getUserByToken($token);
        if (empty($user)) {
            return $this->failNotAuthorized();
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
                // access is not allowed
                return $this->failNotAllowed();
            }
        }

        // access allowed, move to next middleware
        return $next($request, $response);
    }

    /**
     * Produce HTTP 401 Not authorized
     *
     * @throws JsonException
     */
    protected function failNotAuthorized()
    {
        throw new JsonException(null, 401, 'Not authorized', 'The user must be authorized');
    }

    /**
     * Produce HTTP 403 Not allowed
     *
     * @throws JsonException
     */
    protected function failNotAllowed()
    {
        throw new JsonException(null, 403, 'Not allowed', ' Access to this location is not allowed');
    }
}
