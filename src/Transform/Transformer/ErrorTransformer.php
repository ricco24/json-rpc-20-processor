<?php

namespace Kelemen\JsonRpc20\Transform\Transformer;

use Kelemen\JsonRpc20\Error;
use League\Fractal\TransformerAbstract;

class ErrorTransformer extends TransformerAbstract
{
    /**
     * Perform transformation
     * @param Error $error
     * @return array
     */
    public function transform(Error $error)
    {
        return [
            'code' => $error->getCode(),
            'message' => $error->getMessage()
        ];
    }
}
