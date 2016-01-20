<?php

namespace Kelemen\JsonRpc20;

class Response
{
    /** @var string */
    private $version;

    /** @var string */
    private $id;

    /** @var mixed */
    private $result;

    /** @var bool|Error */
    private $error = false;

    /**
     * @param string $version
     */
    public function __construct($version)
    {
        $this->version = $version;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param mixed $result
     */
    public function setResult($result)
    {
        $this->result = $result;
    }

    /**
     * @param int $code
     */
    public function setError($code)
    {
        $this->error = new Error($code);
    }

    /**
     * @return string|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @return bool|Error
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }
}
