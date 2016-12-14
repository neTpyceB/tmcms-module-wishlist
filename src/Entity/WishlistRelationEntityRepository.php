<?php

namespace TMCms\Modules\Wishlist\Entity;

use TMCms\Orm\EntityRepository;

/**
 * Class WishlistRelationEntityRepository
 * @package TMCms\Modules\Wishlist\Entity
 *
 * @method $this setWhereClientId(int $client_id)
 * @method $this setWhereItemId(int $item_id)
 * @method $this setWhereItemType(string $type)
 */
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