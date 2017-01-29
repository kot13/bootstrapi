<?php
namespace App\Common;

use Psr\Http\Message\ResponseInterface as Response;

final class Renderer
{
    /**
     * @var
     */
    private $config;

    /**
     * Renderer constructor.
     *
     * @param $config
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * @param Response $response
     * @param int      $statusCode
     * @param string   $data
     *
     * @return Response
     */
    public function jsonApiRender(Response $response, $statusCode = 200, $data = '')
    {
        $jsonApiResponse = $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Methods', 'GET, PUT, PATCH, POST, DELETE, OPTIONS')
            ->withHeader('Access-Control-Allow-Credentials', 'true')
            ->withHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With')
            ->withHeader('Content-Type', 'application/vnd.api+json')
            ->withStatus($statusCode);

        $jsonApiResponse->getBody()->write($data);

        return $jsonApiResponse;
    }
}
