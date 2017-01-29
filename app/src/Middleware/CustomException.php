<?php

namespace App\Middleware;

use App\Common\JsonException;
use App\Common\Renderer;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class CustomException
{
    /**
     * @var Renderer
     */
    private $renderer;

    /**
     * CustomException constructor.
     *
     * @param Renderer $renderer
     */
    public function __construct(Renderer $renderer)
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
            return $this->renderer->jsonApiRender($response, $e->statusCode, $e->encodeError());
        }
    }
}
