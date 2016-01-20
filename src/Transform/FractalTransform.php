<?php

namespace Kelemen\JsonRpc20\Transform;

use Kelemen\JsonRpc20\Transform\Transformer\ResponseTransformer;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use League\Fractal\Serializer\ArraySerializer;

class FractalTransform
{
    /**
     * Transform data to array
     * @param mixed $data
     * @return array
     */
    public function toArray($data)
    {
        $fractal = new Manager();
        $fractal->setSerializer(new ArraySerializer());

        if (is_array($data)) {
            $response = [];
            foreach ($data as $row) {
                $item = new Item($row, new ResponseTransformer());
                $response[] = $fractal->createData($item)->toArray();
            }
            return $response;

        }

        $item = new Item($data, new ResponseTransformer());
        return $fractal->createData($item)->toArray();
    }

    /**
     * Transform data to json
     * @param mixed $data
     * @return string
     */
    public function toJson($data)
    {
        return json_encode($this->toArray($data));
    }
}
