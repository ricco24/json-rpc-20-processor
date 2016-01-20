<?php

namespace Kelemen\JsonRpc20;

use Kelemen\JsonRpc20\Exception\JsonRpc2Exception;
use ReflectionMethod;

class Processor
{
    const VERSION = "2.0";

    /** @var array  */
    private $handlers = [];

    /**
     * @param string $methodName
     * @param array $handler            [$objectInstance, $methodName]
     */
    public function registerHandler($methodName, array $handler)
    {
        $this->handlers[$methodName] = $handler;
    }

    /**
     * Process given json and return response object
     * @param string $json
     * @return array|Response
     */
    public function process($json)
    {
        $response = new Response(self::VERSION);

        $data = json_decode($json);
        if ($data === null) {
            $response->setError(Error::ERROR_PARSE_ERROR);
            return $response;
        }

        // Process bulk request
        if (is_array($data)) {
            if (count($data) === 0) {
                $response->setError(Error::ERROR_INVALID_REQUEST);
                return $response;
            }

            $response = [];
            foreach ($data as $requestData) {
                $response[] = $this->processRequest(new Request($requestData, self::VERSION));
            }
            return $response;
        }

        // Process normal request
        return $this->processRequest(new Request($data, self::VERSION));
    }

    /**
     * Process single request
     * @param Request $request
     * @return Response
     */
    private function processRequest(Request $request)
    {
        $response = new Response(self::VERSION);

        // Set response id if request has some id
        if ($request->getId()) {
            $response->setId($request->getId());
        }

        if (!$request->isValid()) {
            $response->setError(Error::ERROR_INVALID_REQUEST);
            return $response;
        }

        if (substr($request->getMethod(), 0, 4) === 'rpc.') {
            $response->setError(Error::ERROR_RESERVED_PREFIX);
            return $response;
        }

        if (!isset($this->handlers[$request->getMethod()])) {
            $response->setError(Error::ERROR_METHOD_NOT_FOUND);
            return $response;
        }

        try {
            // Call object method
            $result = $this->callMethod($this->handlers[$request->getMethod()], $request->getParams());
            $response->setResult($result);

        } catch (JsonRpc2Exception $e) {
            $response->setError(Error::ERROR_INVALID_PARAMS);
            return $response;
        }

        return $response;
    }

    /**
     * @param array $function
     * @param mixed $params
     * @return mixed
     * @throws JsonRpc2Exception
     */
    private function callMethod(array $function, $params)
    {
        $realParams = [];
        $reflect = new ReflectionMethod($function[0], $function[1]);

        // If params is object, we have named parameters
        if (is_object($params)) {
            foreach ($reflect->getParameters() as $i => $param) {
                $paramName = $param->getName();

                if (property_exists($params, $paramName)) {
                    $realParams[] = $params->$paramName;

                } else if ($param->isDefaultValueAvailable()) {
                    $realParams[] = $param->getDefaultValue();

                } else {
                    throw new JsonRpc2Exception('Required parameter is missing');
                }
            }
        }

        // Normal (no named) parameters from array
        if (is_array($params)) {
            $realParams = $params;
        }

        // Check if we have all required parameters to process method
        if ($reflect->getNumberOfRequiredParameters() > count($realParams)) {
            throw new JsonRpc2Exception('Required parameter is missing');
        }

        return $reflect->invokeArgs($function[0], $realParams);
    }
}
