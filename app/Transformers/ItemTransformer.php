<?php namespace Transformers;

use League\Fractal\TransformerAbstract;

class ItemTransformer extends TransformerAbstract
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
            'id'          => my_encode($item['id']),
            'title'       => isset($item['title']) ? $item['title'] : '',
            'comment'     => isset($item['comment']) ? $item['comment'] : '',
            'description' => isset($item['description']) ? $item['description'] : '',
            'person_id'   => isset($item['person_id']) ? my_encode($item['person_id']) : '',
            'report_id'   => isset($item['report_id']) ? my_encode($item['report_id']) : '',
            'is_archive'  => isset($item['is_archive']) ? $item['is_archive'] : '',
            'created'     => isset($item['created']) ? $item['created'] : '',
            'updated'     => isset($item['updated']) ? $item['updated'] : ''
        ];
    }
}




