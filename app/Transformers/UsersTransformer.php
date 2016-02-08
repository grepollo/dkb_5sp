<?php namespace Transformers;

use League\Fractal\TransformerAbstract;

class UsersTransformer extends TransformerAbstract
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
            'id'           => my_encode($item['id']),
            'username'     => $item['username'],
            'first_name'   => isset($item['first_name']) ? $item['first_name'] : '',
            'last_name'    => isset($item['last_name']) ? $item['last_name'] : '',
            'gender'       => isset($item['gender']) ? $item['gender'] : '',
            'email'        => isset($item['email']) ? $item['email'] : '',
            'userimage'    => isset($item['userimage']) ? $item['userimage'] : '',
            'country'      => isset($item['country']) ? $item['country'] : '',
            'occupation'   => isset($item['occupation']) ? $item['occupation'] : '',
            'role'         => isset($item['role']) ? $item['role'] : '',
            'created'      => isset($item['created']) ? $item['created'] : '',
            'totalIReport' => isset($item['totalIReport']) ? $item['totalIReport'] : 0,
            'totalGReport' => isset($item['totalGReport']) ? $item['totalGReport'] : 0,
        ];
    }
}



