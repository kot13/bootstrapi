<?php

namespace App\Middleware;

use App\Common\Acl;
use App\Common\Auth;
use App\Common\JsonException;
use App\Controller\TokenController as Token;
use App\Model\AccessToken;
use App\Model\User;

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
        // If request has the correct token, passed it to action
        if ($request->getHeader('Authorization')) {
            $token = explode(' ', $request->getHeader('Authorization')[0]);
            $token = $token[count($token) - 1];

            if (Token::validateToken($token, $this->settings['params']['allowHosts'])) {
                $user = AccessToken::getUserByAccessToken($token);
                if ($user) {
                    Auth::setUser($user);

                    $isAllowed = false;
                    $route     = $request->getAttribute('route');

                    if ($route) {
                        if ($this->acl->hasResource('route'.$route->getPattern())) {
                            $isAllowed = $isAllowed || $this->acl->isAllowed($user->role->name, 'route'.$route->getPattern(), strtolower($request->getMethod()));
                        }
                        if ($this->acl->hasResource('callable/'.$route->getCallable())) {
                            $isAllowed = $isAllowed || $this->acl->isAllowed($user->role->name, 'callable/'.$route->getCallable());
                        }
                        if (!$isAllowed) {
                            throw new JsonException(null, 403, 'Not allowed', $user->role->name.' is not allowed access to this location.');
                        }
                    }

                    return $next($request, $response);
                }
            }
        }

        throw new JsonException(null, 401, 'Not authorized', 'The user must be authorized');
    }
}
