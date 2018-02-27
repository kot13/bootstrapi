<?php

namespace App\Controller;

use App\Model\MediaFile;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Common\JsonException;

class UploadController extends BaseController
{
    /**
     * @api {post} /upload Upload media
     * @apiName PostUpload
     * @apiGroup Upload
     *
     * @apiPermission user
     * @apiHeader {String} Authorization Bearer TOKEN
     * @apiUse UnauthorizedError
     *
     * @apiDescription Метод для загрузки фотографий. Загружать form-data, где ключ - "image", а значение - файл.
     *
     * @apiParam {File} image Загружаемый файл
     *
     * @apiParamExample {json} Example request:
     *    {
     *      "image": "/path/to/image.jpg"
     *    }
     *
     * @apiSuccessExample {json} Success (200)
     *     HTTP/1.1 200 OK
     *     {
     *       "data": {
     *         "type": "media-file",
     *         "id": "2",
     *         "attributes": {
     *           "file": "bUinMEAa0UBNeJBOVDcUHZckHpor3a74.jpg",
     *           "file_info": {
     *             "mime": "image/jpeg",
     *             "size": 133807
     *           }
     *         },
     *         "links": {
     *           "self": "http://london.dev/api/media-file/2"
     *         }
     *       }
     *     }
     *
     * @apiUse StandardErrors
     */
    /**
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return \Psr\Http\Message\ResponseInterface
     * @throws JsonException
     */
    public function upload(Request $request, Response $response, $args)
    {
        $files = $request->getUploadedFiles();
        if (!isset($files['image'])) {
            throw new JsonException('media-file', 400, 'Bad request', 'Not found file');
        }

        /** @var \Slim\Http\UploadedFile $file */
        $file = $files['image'];
        if ($file->getError() !== UPLOAD_ERR_OK) {
            throw new JsonException('media-file', 500, 'Internal server error', 'Internal server error');
        }

        try {
            $mediaFile = MediaFile::create($file, $this->settings['params']['uploadDir']);
        } catch (\Exception $e) {
            throw new JsonException('media-file', 500, 'Internal server error', $e->getMessage());
        }

        $result = $this->encoder->encode($request, $mediaFile);

        return $this->apiRenderer->jsonResponse($response, 200, $result);
    }
}
