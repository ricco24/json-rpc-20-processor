<?php

namespace Kelemen\JsonRpc20;

use stdClass;

class Request
{
    /** @var string|Null */
    private $id;

    /** @var string */
    private $jsonrpc;

    /** @var string */
    private $method;

    /** @var mixed */
    private $params;

    /** @var bool */
    private $isValid = true;

    /**
     * @param stdClass $data
     * @param string $version
     */
    public function __construct(stdClass $data, $version)
    {
        $this->setId($data);
        $this->setJsonRpc($data, $version);
        $this->setMethod($data);
        $this->setParams($data);
    }

    /**
     * @return string|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getJsonRpc()
    {
        return $this->jsonrpc;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return mixed
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        return $this->isValid;
    }

    /**
     * @param stdClass $data
     */
    private function setId(stdClass $data)
    {
        if (!isset($data->id)) {
            return;
        }

        if (!is_string($data->id) && !is_numeric($data->id)) {
            $this->isValid = false;
            return;
        }

        $this->id = $data->id;
    }

    /**
     * @param stdClass $data
     * @param string $version
     */
    private function setJsonRpc(stdClass $data, $version)
    {
        if (!isset($data->jsonrpc) || $data->jsonrpc != $version) {
            $this->isValid = false;
            return;
        }

        $this->jsonrpc = $data->jsonrpc;
    }

    /**
     * @param stdClass $data
     */
    private function setMethod(stdClass $data)
    {
        if (!isset($data->method)) {
            $this->isValid = false;
            return;
        }

        $this->method = $data->method;
    }

    /**
     * @param stdClass $data
     */
    private function setParams(stdClass $data)
    {
        if (!isset($data->params)) {
            return;
        }

        if (isset($data->params) && !is_array($data->params) && !is_object($data->params)) {
            $this->isValid = false;
            return;
        }

        $this->params = $data->params;
    }
}
