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

/**
 * Backup Filesystem Item
 *
 * @author	Oliver Kliebisch
 * @copyright	2009 Oliver Kliebisch
 * @license	GNU General Public License <http://www.gnu.org/licenses/>
 * @package	net.hawkes.admintools
 * @subpackage acp.admintools.lostandfound
 * @category WCF 
 */
class BackupFilesystemLostAndFoundItem extends AbstractLostAndFoundFileSystemItem {	
	
	/**
	 * Creates the object by passing the objectID
	 *
	 * @param integer $backupID
	 */
	public function __construct($backupID) {
		parent::__construct('backupFilesystem', $backupID);	
	}
	
	/**
	 * @see AbstractLostAndFoundFileSystemItem::createVirtualIDSpace()	 
	 */
	public static function createVirtualIDSpace() {
		$backups = array();
		chdir(WCF_DIR.'acp/backup');
		$dh = opendir(WCF_DIR.'acp/backup');				
		while($file = readdir ($dh)) {
			if($file != '.' && $file != '..' && $file != '.htaccess' && !is_dir($file)) {				
				$backups[] = $file;				
			}
		}
		closedir($dh);
		self::$virtualFileIDs['backupFilesystem'] = $backups;
		WCF::getSession()->register('virtualLostAndFoundIDs', self::$virtualFileIDs);
	}
	
	/**
	 * @see AbstractLostAndFounDatabaseItem::delete()	 
	 */
	public function delete() {		
		if (isset(self::$virtualFileIDs['backupFilesystem'][$this->objectID])) {
			@unlink(WCF_DIR.'acp/backup/'.self::$virtualFileIDs['backupFilesystem'][$this->objectID]);
		}
	}

	/**
	 * @see AbstractLostAndFounDatabaseItem::deleteAll()	 
	 */
	public static function deleteAll() {
		$itemIDs = self::getMarkedItems('backupFilesystem');		
		foreach($itemIDs as $itemID) {
			$item = new BackupFilesystemLostAndFoundItem($itemID);
			$item->delete();
		}
	}
}
?>