<?php namespace Transformers;

use League\Fractal\TransformerAbstract;

class ItemTagTransformer extends TransformerAbstract
{
    /**
     * Turn this item object into a generic array
     *
     * @param array $item
     *
     * @return array
     */
    public function transform($item)
    {
        return [
            'id'      => my_encode($item['id']),
            'tag'     => isset($item['tag']) ? $item['tag'] : '',
            'item_id' => isset($item['item_id']) ? my_encode($item['item_id']) : '',
            'created' => isset($item['created']) ? $item['created'] : '',
            'updated' => isset($item['updated']) ? $item['updated'] : ''
        ];
    }
}




