<?php

namespace TMCms\Modules\Wishlist;

use TMCms\Modules\IModule;
use TMCms\Modules\Wishlist\Entity\WishlistRelationEntity;
use TMCms\Modules\Wishlist\Entity\WishlistRelationEntityRepository;
use TMCms\Orm\Entity;
use TMCms\Strings\Converter;
use TMCms\Traits\singletonInstanceTrait;

defined('INC') or exit;

class ModuleWishlist implements IModule
{
	use singletonInstanceTrait;

	public static $tables = array(
		'wishlist' => 'm_wishlist'
	);

	/**
     * @param Entity $item
	 * @param int $client_id e.g. who favors $client_id
	 * @return int $id of created entry
	 */
	public static function addWish(Entity $item, $client_id) {
		$wish = self::getWish($item, $client_id);

		if (!$wish) {
			$wish = new WishlistRelationEntity();
			$wish->loadDataFromArray([
				'item_type' => Converter::classWithNamespaceToUnqualifiedShort($item),
				'item_id' => $item->getId(),
				'client_id' => $client_id,
			]);
			$wish->save();
		}

		return $wish->getId();
	}

	/**
     * @param Entity $item
	 * @param int $client_id
	 */
	public static function deleteWish(Entity $item, $client_id) {
		$wishes = new WishlistRelationEntityRepository();
		$wishes->setWhereItemType(Converter::classWithNamespaceToUnqualifiedShort($item));
		$wishes->setWhereItemId($item->getId());
		$wishes->setWhereClientId($client_id);

		$wishes->deleteObjectCollection();
	}

    /**
     * @param Entity $item
     * @param int $client_id
     * @return bool
     */
	public static function getWish(Entity $item, $client_id) {
		return WishlistRelationEntityRepository::findOneEntityByCriteria([
				'item_type' => Converter::classWithNamespaceToUnqualifiedShort($item),
				'item_id' => $item->getId(),
				'client_id' => $client_id,
		]);
	}

    /**
     * @param Entity $item
     * @param int $client_id
     * @return array
     */
	public static function getWishList(Entity $item, $client_id) {
		$wishes = new WishlistRelationEntityRepository();
		$wishes->setWhereItemType(Converter::classWithNamespaceToUnqualifiedShort($item));
		$wishes->setWhereClientId($client_id);

		return $wishes->getPairs(['item_id']);
	}
}