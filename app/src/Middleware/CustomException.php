<?php

namespace App\Middleware;

use App\Common\JsonException;
use App\Common\ApiRenderer;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class CustomException
{
    /**
     * @var ApiRenderer
     */
    private $renderer;

    /**
     * CustomException constructor.
     *
     * @param ApiRenderer $renderer
     */
    public function __construct(ApiRenderer $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param callable               $next
     *
     * @return mixed
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        try {
            return $next($request, $response);
        } catch (JsonException $e) {
            return $this->renderer->jsonResponse($response, $e->statusCode, $e->encodeError());
        }
    }
}
