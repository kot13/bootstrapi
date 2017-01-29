<?php

namespace App\Common;

use \Neomerx\JsonApi\Encoder\Encoder;
use \Neomerx\JsonApi\Encoder\EncoderOptions;
use \Neomerx\JsonApi\Factories\Factory;
use \Neomerx\JsonApi\Contracts\Document\LinkInterface;
use \Neomerx\JsonApi\Document\Link;
use Slim\Http\Request;
use App\Model\User;

final class JsonApiEncoder
{
    private $settings;

    /**
     * JsonApiEncoder constructor.
     *
     * @param $settings
     */
    public function __construct($settings)
    {
        $this->settings = $settings;
    }

    /**
     * @param Request      $request
     * @param mixed        $entities
     * @param integer|null $pageNumber
     * @param integer|null $pageSize
     *
     * @return string
     */
    public function encode(Request $request, $entities, $pageNumber = null, $pageSize = null)
    {
        $factory        = new Factory();
        $parameters     = $factory->createQueryParametersParser()->parse($request);
        $user           = Auth::getUser();
        $encodeEntities = $this->settings['encoder']['default'];

        if ($user && $user->role_id == User::ROLE_ADMIN) {
            $encodeEntities = $this->settings['encoder']['extended'];
        }

        $encoder = Encoder::instance(
            $encodeEntities,
            new EncoderOptions(
                JSON_PRETTY_PRINT,
                $this->settings['params']['host'].'/api'
            )
        );

        if (isset($pageNumber) && isset($pageSize)) {
            $links = [
                LinkInterface::SELF  => new Link('?page[number]='.$pageNumber.'&page[size]='.$pageSize, null, false),
                LinkInterface::FIRST => new Link('?page[number]=1&page[size]='.$pageSize, null, false),
                LinkInterface::LAST  => new Link('?page[number]='.$entities->lastPage().'&page[size]='.$pageSize, null, false),
            ];

            $meta = [
                'total' => $entities->total(),
                'count' => $entities->count(),
            ];

            if (($entities->lastPage() - ($pageNumber + 1)) >= 0) {
                $links[LinkInterface::NEXT] = new Link('?page[number]='.($pageNumber + 1).'&page[size]='.$pageSize, null, false);
            }
            if (($pageNumber - 1) > 0) {
                $links[LinkInterface::PREV] = new Link('?page[number]='.($pageNumber - 1).'&page[size]='.$pageSize, null, false);
            }
        }

        if (isset($links)) {
            $encoder->withLinks($links);
        }

        if (isset($meta)) {
            $encoder->withMeta($meta);
        }

        return $encoder->encodeData($entities, $parameters);
    }
}
