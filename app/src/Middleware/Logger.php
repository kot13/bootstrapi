<?php

namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class Logger
{
    /**
     * @var \Monolog\Logger
     */
    private $logger;

    /**
     * Logger constructor.
     * @param \Monolog\Logger $logger
     */
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
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        $logger     = $this->logger;
        $response   = $next($request, $response);
        $statusCode = $response->getStatusCode();
        $log        = [
            'ip'     => $request->getAttribute('ip_address'),
            'uri'    => $request->getUri()->getPath(),
            'status' => $statusCode,
        ];

        switch ($statusCode) {
            case 500:
                $logger->addCritical('Oops!!! the server got 500 error', $log);
                break;
            case 404:
                $logger->addWarning('Someone calling un-existing API action', $log);
                break;
            case 401:
                $logger->addWarning('Someone calling API action without access', $log);
                break;
            default:
                $logger->addInfo('Someone calling existing API action', $log);
                break;
        }
        return $response;
    }
}
