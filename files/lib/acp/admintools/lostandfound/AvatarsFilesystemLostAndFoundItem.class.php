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
require_once(WCF_DIR.'lib/acp/admintools/lostandfound/AbstractLostAndFoundFileSystemItem.class.php');
require_once(WCF_DIR.'lib/data/user/avatar/AvatarEditor.class.php');

/**
 * Avatars Filesystem Item
 *
 * @author	Oliver Kliebisch
 * @copyright	2009 Oliver Kliebisch
 * @license	GNU General Public License <http://www.gnu.org/licenses/>
 * @package	net.hawkes.admintools
 * @subpackage acp.admintools.lostandfound
 * @category WCF 
 */
class AvatarsFilesystemLostAndFoundItem extends AbstractLostAndFoundFileSystemItem {
	
	/**
	 * Creates the object by passing the objectID
	 * 
	 * @param integer $avatarID
	 */ 
	public function __construct($avatarID) {
		parent::__construct('avatarsFilesystem', $avatarID);
	}

	/**
	 * @see AbstractLostAndFoundFileSystemItem::createVirtualIDSpace() 
	 */
	public static function createVirtualIDSpace() {
		$theAvatars = array();
		chdir(WCF_DIR.'images/avatars');
		$dh=opendir(WCF_DIR.'images/avatars');
		$avatarIDs = array();
		$avatars = array();
		while($file = readdir ($dh)) {
			if(preg_match("/^(avatar).*/",$file) && $file != '.' && $file != '..' && $file != '.htaccess' && !preg_match("/^.*\.php$/",$file)) {
				$avatarID = (int) preg_replace("/.*\-(\d+).*/", "$1", $file);
				$avatars[$avatarID] = preg_replace("/.*\-(\d+)(.*)/", "$2", $file);
				if($avatarID > 0) {
					$avatarIDs[] = $avatarID;
				}
			}
		}
		if (count($avatarIDs)) {
			$sql = "SELECT avatarID, avatarExtension FROM wcf".WCF_N."_avatar WHERE avatarID IN (".implode(',', $avatarIDs).")";
			$result = WCF::getDB()->sendQuery($sql);
			$physicalAvatars = array_flip($avatarIDs);
			while($row = WCF::getDB()->fetchArray($result)) {
				unset($physicalAvatars[$row['avatarID']]);
			}
			$physicalAvatars = array_keys($physicalAvatars);
			foreach($physicalAvatars as $avatarID) {				
				$file = WCF_DIR.'images/avatars/avatar-'.$avatarID.$avatars[$avatarID];
				$theAvatars[] = $file;
			}
		}
		closedir($dh);
		self::$virtualFileIDs['avatarsFilesystem'] = $theAvatars;
		WCF::getSession()->register('virtualLostAndFoundIDs', self::$virtualFileIDs);
	}
	
	/**
	 * @see AbstractLostAndFoundDatabaseItem::delete()	 
	 */
	public function delete() {
		if (isset(self::$virtualFileIDs['avatarsFilesystem'][$this->objectID])) {
			$file = self::$virtualFileIDs['avatarsFilesystem'][$this->objectID];			
			@unlink($file);
		}
	}

	/**
	 * @see AbstractLostAndFounDatabaseItem::deleteAll()	 
	 */
	public static function deleteAll() {
		$itemIDs = self::getMarkedItems('avatarsFilesystem');
		foreach($itemIDs as $itemID) {
			$item = new AvatarsFilesystemLostAndFoundItem($itemID);
			$item->delete();
		}
	}
}
?>