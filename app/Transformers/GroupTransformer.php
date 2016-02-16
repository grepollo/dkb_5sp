<?php namespace Transformers;

use League\Fractal\TransformerAbstract;

class GroupTransformer extends TransformerAbstract
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
            'report_id' => isset($item['report_id']) ? my_encode($item['report_id']) : '',
            'person_id' => isset($item['person_id']) ? my_encode($item['person_id']) : '',
            'person_name'   => isset($item['person_name']) ? my_encode($item['person_name']) : '',
            'created'   => isset($item['created']) ? $item['created'] : '',
            'updated'   => isset($item['updated']) ? $item['updated'] : ''
        ];
    }
}



