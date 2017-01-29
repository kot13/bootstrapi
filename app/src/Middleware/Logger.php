<?php

namespace App\Middleware;

use App\Common\JsonException;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class Logger
{
    /**
     * @var \Monolog\Logger
     */
    private $logger;

    public function __construct(\Monolog\Logger $logger)
    {
        $this->logger = $logger;
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
        $logger     = $this->logger;
        $response   = $next($request, $response);
        $uri        = $request->getUri()->getPath();
        $statusCode = $response->getStatusCode();

        switch ($statusCode) {
            case 500:
                $logger->addCritical('Oops!!! the server got 500 error', [
                    'ip'     => $request->getAttribute('ip_address'),
                    'uri'    => $uri,
                    'status' => $statusCode,
                ]);
                break;
            case 404:
                $logger->addWarning('Someone calling un-existing API action', [
                    'ip'     => $request->getAttribute('ip_address'),
                    'uri'    => $uri,
                    'status' => $statusCode,
                ]);
                break;
            case 401:
                $logger->addWarning('Someone calling API action without access', [
                    'ip'     => $request->getAttribute('ip_address'),
                    'uri'    => $uri,
                    'status' => $statusCode,
                ]);
                break;
            default:
                $logger->addInfo('Someone calling existing API action', [
                    'ip'     => $request->getAttribute('ip_address'),
                    'uri'    => $uri,
                    'status' => $statusCode,
                ]);
                break;
        }
        return $response;
    }
}
