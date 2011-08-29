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

/**
 * Extends the database item by methods for a virtual ID space and secure AJAX access on filesystem objects
 *
 * @author	Oliver Kliebisch
 * @copyright	2009 Oliver Kliebisch
 * @license	GNU General Public License <http://www.gnu.org/licenses/>
 * @package	net.hawkes.admintools
 * @subpackage acp.admintools.lostandfound
 * @category WCF 
 */
abstract class AbstractLostAndFoundFileSystemItem extends AbstractLostAndFoundDatabaseItem {
	
	/**
	 * A multi-dimensional array containing the virtual IDs
	 *
	 * @var array<mixed>
	 */
	protected static $virtualFileIDs  = array();		
	
	/**
	 * A boolean to check that the session cache is loaded only once
	 *
	 * @var boolean
	 */
	public static $sessionCacheUsed = false;
	
	/**
	 * The last modification time of the file ressource
	 *
	 * @var integer
	 */	
	public $fileLastModTime = 0;	
	
	/**
	 * @see AbstractLostAndFounDatabaseItem::__construct($itemName, $objectID)	 
	 */
	public function __construct($itemName, $objectID) {
		if(!self::$sessionCacheUsed) {
			self::getVirtualIDsFromSession();
			self::$sessionCacheUsed = true;
		}
		parent::__construct($itemName, $objectID);
	}
	
	/**
	 * Saves the virtual ID filespace so IDs won't change over a session	 
	 */
	public function __destruct() {
		WCF::getSession()->register('virtualLostAndFoundIDs', self::$virtualFileIDs);
	}
	
	/**
	 * Creates the virtual ID filespace. This has to be implemented by subclasses	 
	 */
	public static function createVirtualIDSpace() { }
	
	/**
	 * Returns all virtualIDs of a certain ressource type
	 *
	 * @param string $type
	 * @return array<mixed>
	 */
	public static function getVirtualIDs($type) {
		if(!self::$sessionCacheUsed) {
			self::getVirtualIDsFromSession();
			self::$sessionCacheUsed = true;
		}
		return isset(self::$virtualFileIDs[$type]) ? self::$virtualFileIDs[$type] : null; 
	}
	
	/**
	 * Returns the virtual ID of a file
	 *
	 * @param string $type
	 * @param string $filename
	 * @return mixed
	 */
	public static function getVirtualID($type, $filename) {
		if(!self::$sessionCacheUsed) {
			self::getVirtualIDsFromSession();
			self::$sessionCacheUsed = true;
		}
		if(isset(self::$virtualFileIDs[$type])) {			
			$fileIDs = array_flip(self::$virtualFileIDs[$type]);
			if(isset($fileIDs[$filename])) {
				return $fileIDs[$filename];
			}
		}
		else return null;
	}
	
	/**
	 * Loads the session cache	 
	 */
	protected static function getVirtualIDsFromSession() {
		$sessionVars = WCF::getSession()->getVars();
		if(isset($sessionVars['virtualLostAndFoundIDs'])) {
			self::$virtualFileIDs = $sessionVars['virtualLostAndFoundIDs'];
		}
	}
}
?>