<?php namespace Transformers;

use League\Fractal\TransformerAbstract;

class ItemDataTransformer extends TransformerAbstract
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
            'id'        => my_encode($item['id']),
            'media'     => isset($item['media']) ? $item['media'] : '',
            'location'  => isset($item['location']) ? $item['location'] : '',
            'item_id'   => isset($item['item_id']) ? my_encode($item['item_id']) : '',
            'person_id' => isset($item['person_id']) ? my_encode($item['person_id']) : '',
            'created'   => isset($item['created']) ? $item['created'] : '',
            'updated'   => isset($item['updated']) ? $item['updated'] : ''
        ];
    }
}




