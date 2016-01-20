<?php

namespace Kelemen\JsonRpc20;

class Error
{
    const ERROR_PARSE_ERROR = -32700;
    const ERROR_INVALID_REQUEST = -32600;
    const ERROR_METHOD_NOT_FOUND = -32601;
    const ERROR_INVALID_PARAMS = -32602;
    const ERROR_INTERNAL_ERROR = -32603;
    const ERROR_RESERVED_PREFIX = -32000;

    /** @var array */
    private $messages = [
        self::ERROR_PARSE_ERROR => 'Parse error',
        self::ERROR_INVALID_REQUEST => 'Invalid Request',
        self::ERROR_METHOD_NOT_FOUND => 'Method not found',
        self::ERROR_INVALID_PARAMS => 'Invalid params',
        self::ERROR_INTERNAL_ERROR => 'Internal error',
        self::ERROR_RESERVED_PREFIX => 'Method prefix rpc. is reserved'
    ];

    /** @var int */
    private $code;

    /**
     * @param int $code
     */
    public function __construct($code)
    {
        $this->code = $code;
    }

    /**
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return string|bool
     */
    public function getMessage()
    {
        return isset($this->messages[$this->code])
            ? $this->messages[$this->code]
            : false;
    }
}
