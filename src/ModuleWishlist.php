<?php
declare(strict_types=1);

namespace TMCms\Modules\Wishlist;

use TMCms\Modules\IModule;
use TMCms\Modules\Wishlist\Entity\WishlistRelationEntity;
use TMCms\Modules\Wishlist\Entity\WishlistRelationEntityRepository;
use TMCms\Orm\Entity;
use TMCms\Strings\Converter;
use TMCms\Traits\singletonInstanceTrait;

\defined('INC') or exit;

/**
 * Class ModuleWishlist
 * @package TMCms\Modules\Wishlist
 */
class ModuleWishlist implements IModule
{
    use singletonInstanceTrait;

    /**
     * @param Entity $item
     * @param Entity $client
     *
     * @return WishlistRelationEntity
     */
    public static function addWish(Entity $item, Entity $client): WishlistRelationEntity
    {
        $wish = self::getExistingWish($item, $client);

        if (!$wish) {
            $wish = new WishlistRelationEntity();
            $wish->loadDataFromArray([
                'item_type' => $item->getUnqualifiedShortClassName(),
                'item_id' => $item->getId(),
                'client_id' => $client->getId(),
            ]);
            $wish->save();
        }

        return $wish;
    }

    /**
     * @param Entity $item
     * @param Entity $client
     *
     * @return Entity|bool
     */
    public static function getExistingWish(Entity $item, Entity $client) {
        return WishlistRelationEntityRepository::findOneEntityByCriteria([
            'item_type' => $item->getUnqualifiedShortClassName(),
            'item_id' => $item->getId(),
            'client_id' => $client->getId(),
        ]);
    }

    /**
     * @param Entity $item
     * @param int $client_id
     * @return array
     */
    public static function getWishList(Entity $item, $client_id): array
    {
        $wishes = new WishlistRelationEntityRepository();
        $wishes->setWhereItemType(Converter::classWithNamespaceToUnqualifiedShort($item));
        $wishes->setWhereClientId($client_id);

        return $wishes->getPairs('item_id');
    }

    /**
     * @param Entity $item
     * @param Entity $client
     */
    public static function deleteAllEntriesForItem(Entity $item, Entity $client = null)
    {
        $rating_collection = new WishlistRelationEntityRepository;
        $rating_collection->setWhereItemId($item->getId());
        $rating_collection->setWhereItemType($item->getUnqualifiedShortClassName());
        if ($client) {
            $rating_collection->setWhereClientId($client->getId());
        }

        $rating_collection->deleteObjectCollection();
    }
}
