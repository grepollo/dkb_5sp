<?php namespace Transformers;

use League\Fractal\TransformerAbstract;

class ItemCommentTransformer extends TransformerAbstract
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
            'comment'   => isset($item['comment']) ? $item['comment'] : '',
            'person_id' => isset($item['person_id']) ? my_encode($item['person_id']) : '',
            'item_id'   => isset($item['item_id']) ? my_encode($item['item_id']) : '',
            'created'   => isset($item['created']) ? $item['created'] : '',
            'updated'   => isset($item['updated']) ? $item['updated'] : ''
        ];
    }
}




