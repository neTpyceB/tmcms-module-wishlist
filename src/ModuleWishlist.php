<?php

namespace TMCms\Modules\Wishlist;

use TMCms\Modules\IModule;
use TMCms\Modules\Wishlist\Entity\WishlistRelationEntity;
use TMCms\Modules\Wishlist\Entity\WishlistRelationEntityRepository;
use TMCms\Traits\singletonInstanceTrait;

defined('INC') or exit;

class ModuleWishlist implements IModule
{
	use singletonInstanceTrait;

	public static $tables = array(
		'wishlist' => 'm_wishlist'
	);

	/**
	 * @param string $item_type e.g. "book"
	 * @param int $item_id e.g. favored $book_id
	 * @param int $client_id e.g. who favors $client_id
	 * @return int $id of created entry
	 */
	public static function addWish($item_type, $item_id, $client_id) {
		$wish = self::getWish($item_type, $item_id, $client_id);

		if (!$wish) {
			$wish = new WishlistRelationEntity();
			$wish->loadDataFromArray([
				'item_type' => $item_type,
				'item_id' => $item_id,
				'client_id' => $client_id,
			]);
			$wish->save();
		}

		return $wish->getId();
	}

	/**
	 * @param string $item_type
	 * @param int $item_id
	 * @param int $client_id
	 */
	public static function deleteWish($item_type, $item_id, $client_id) {
		$wishes = new WishlistRelationEntityRepository();
		$wishes->setWhereItemType($item_type);
		$wishes->setWhereItemId($item_id);
		$wishes->setWhereClientId($client_id);

		$wishes->deleteObjectCollection();
	}

	/**
	 * @param string $item_type
	 * @param int $item_id
	 * @param int $client_id
	 * @return bool
	 */
	public static function getWish($item_type, $item_id, $client_id) {
		return WishlistRelationEntityRepository::findOneEntityByCriteria([
				'item_type' => $item_type,
				'item_id' => $item_id,
				'client_id' => $client_id,
		]);
	}

	/**
	 * @param string $item_type
	 * @param int $client_id
	 * @return array
	 */
	public static function getWishList($item_type, $client_id) {
		$wishes = new WishlistRelationEntityRepository();
		$wishes->setWhereItemType($item_type);
		$wishes->setWhereClientId($client_id);

		return $wishes->getPairs(['item_id']);
	}
}