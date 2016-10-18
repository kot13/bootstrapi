<?php
namespace App\Common;

use \Neomerx\JsonApi\Encoder\Encoder;
use \Neomerx\JsonApi\Document\Error;

class JsonException extends \Exception
{
    /**
     * @var
     */
    public $statusCode;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $detail;

    /**
     * JsonException constructor.
     *
     * @param string $type
     * @param int    $statusCode
     * @param string $title
     * @param string $detail
     */
    public function __construct($type, $statusCode, $title, $detail)
    {
        $this->statusCode = $statusCode;
        $this->type       = $type;
        $this->title      = $title;
        $this->detail     = $detail;
    }

    /**
     * JsonApi encode error
     *
     * @return string
     */
    public function encodeError()
    {
        $error = new Error(
            $this->type,
            null,
            $this->statusCode,
            $this->statusCode,
            $this->title,
            $this->detail
        );

        return Encoder::instance()->encodeError($error);
    }
}