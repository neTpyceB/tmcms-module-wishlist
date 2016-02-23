<?php

namespace TMCms\Modules\Wishlist\Entity;

use TMCms\Orm\EntityRepository;

class WishlistRelationEntityRepository extends EntityRepository
{
    protected $db_table = 'm_wishlist';

    protected $table_structure = [
        'fields' => [
            'client_id' => [
                'type' => 'index',
            ],
            'item_id' => [
                'type' => 'index',
            ],
            'item_type' => [
                'type' => 'varchar',
            ],
        ],
        'indexes' => [
            'item_type' => [
                'type' => 'key',
            ],
        ],
    ];
}