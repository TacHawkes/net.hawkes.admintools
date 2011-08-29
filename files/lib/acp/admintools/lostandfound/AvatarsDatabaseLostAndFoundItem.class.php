<?php
/**
 *   This file is part of Admin Tools 2.
 *
 *   Admin Tools 2 is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   Admin Tools 2 is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with Admin Tools 2.  If not, see <http://www.gnu.org/licenses/>.
 *
 * 
 */
require_once(WCF_DIR.'lib/acp/admintools/lostandfound/AbstractLostAndFoundDatabaseItem.class.php');
require_once(WCF_DIR.'lib/data/user/avatar/AvatarEditor.class.php');

/**
 * Avatars Database Item
 *
 * @author	Oliver Kliebisch
 * @copyright	2009 Oliver Kliebisch
 * @license	GNU General Public License <http://www.gnu.org/licenses/>
 * @package	net.hawkes.admintools
 * @subpackage acp.admintools.lostandfound
 * @category WCF 
 */
class AvatarsDatabaseLostAndFoundItem extends AbstractLostAndFoundDatabaseItem {	

	/**
	 * Creates the object by passing the objectID
	 *
	 * @param integer $avatarID
	 */
	public function __construct($avatarID) {
		parent::__construct('avatarsDatabase', $avatarID);
	}
	
	/**
	 * @see AbstractLostAndFounDatabaseItem::delete()	 
	 */
	public function delete() {
		$editor = new AvatarEditor($this->objectID);		
		$editor->delete();
	}
	
	/**
	 * @see AbstractLostAndFounDatabaseItem::deleteAll()	 
	 */
	public static function deleteAll() {
		$itemIDs = self::getMarkedItems('avatarsDatabase');		
		foreach($itemIDs as $itemID) {
			$item = new AvatarsDatabaseLostAndFoundItem($itemID);
			$item->delete();
		}
	}
}
?>