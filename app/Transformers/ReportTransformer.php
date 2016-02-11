<?php namespace Transformers;

use League\Fractal\TransformerAbstract;

class ReportTransformer extends TransformerAbstract
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
            'name'        => $item['name'],
            'description' => isset($item['description']) ? $item['description'] : '',
            'created'     => isset($item['created']) ? $item['created'] : '',
            'person_id'   => isset($item['person_id']) ? my_encode($item['person_id']) : '',
            'author'      => isset($item['author']) ? $item['author'] : '',
            'is_archive'  => isset($item['is_archive']) ? $item['is_archive'] : '',
            'report_type' => isset($item['report_type']) ? $item['report_type'] : ''
        ];
    }
}



