<?php

namespace Kelemen\JsonRpc20\Transform\Transformer;

use Kelemen\JsonRpc20\Response;
use League\Fractal\TransformerAbstract;
use League\Fractal\Resource\Item;

class ResponseTransformer extends TransformerAbstract
{
    /** @var array */
    protected $defaultIncludes = [
        'error'
    ];

    /**
     * Perform transformation
     * @param Response $response
     * @return array
     */
    public function transform(Response $response)
    {
        $data = [
            'jsonprc' => $response->getVersion(),
            'id' => $response->getId()
        ];

        if (!$response->getError()) {
            $data['result'] = $response->getResult();
        }

        return $data;
    }

    /**
     * Perform error transformation if is provided
     * @param Response $response
     * @return Item
     */
    public function includeError(Response $response)
    {
        if ($response->getError()) {
            return $this->item($response->getError(), new ErrorTransformer());
        }
    }
}
