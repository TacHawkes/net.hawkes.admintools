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
require_once(WCF_DIR.'lib/acp/admintools/lostandfound/MarkableLostAndFoundItem.class.php');

/**
 * Abstract implementation of a markable lost and found item which provides the session mark functions
 * and serves as basic class for all database lost and found items.
 *
 * @author	Oliver Kliebisch
 * @copyright	2009 Oliver Kliebisch
 * @license	GNU General Public License <http://www.gnu.org/licenses/>
 * @package	net.hawkes.admintools
 * @subpackage acp.admintools.lostandfound
 * @category WCF 
 */
abstract class AbstractLostAndFoundDatabaseItem implements MarkableLostAndFoundItem {
	
	/**
	 * The name of the current item
	 *
	 * @var string
	 */
	public $itemName = '';
	
	/**
	 * The editor object of the current item. Only useful if the item is for handling database objects
	 *
	 * @var unknown_type
	 */
	public $editor;
	
	/**
	 * The id of the current oobject
	 *
	 * @var integer
	 */
	public $objectID = 0;
	
	/**
	 * The filename of the current item
	 *
	 * @var string
	 */
	public $filename = '';
	
	/**
	 * The formatted filesize(reason of string type)
	 *
	 * @var string
	 */
	public $filesize = '';
	
	/**
	 * The unix timestamp of the last modification
	 *
	 * @var integer
	 */
	public $fileLastModTime = 0;
	
	/**
	 * The name of the file's owner
	 *
	 * @var string
	 */
	public $user;
	
	/**
	 * Constructs the object
	 *
	 * @param string $itemName
	 * @param integer $objectID
	 */
	public function __construct($itemName, $objectID) {
		$this->itemName = $itemName;		
		$this->objectID = $objectID;
	}
	
	/**
	 * @see MarkableLostAndFoundItem::mark()	 
	 */
	public function mark() {
		$markedItems = self::getMarkedItems($this->itemName);
		if ($markedItems == null || !is_array($markedItems)) {
			$markedItems = array($this->objectID);				
			WCF::getSession()->register('marked'.ucfirst($this->itemName), $markedItems);
		}
		else {
			if (!in_array($this->objectID, $markedItems)) {				
				array_push($markedItems, $this->objectID);
				WCF::getSession()->register('marked'.ucfirst($this->itemName), $markedItems);
			}
		}
	}

	/**
	 * @see MarkableLostAndFoundItem::mark()	 
	 */
	public function unmark() {
		$markedItems = self::getmarkedItems($this->itemName);
		if (is_array($markedItems) && in_array($this->objectID, $markedItems)) {
			$key = array_search($this->objectID, $markedItems);				
			unset($markedItems[$key]);
			if (count($markedItems) == 0) {
				self::unmarkAll($this->itemName);
			}
			else {
				WCF::getSession()->register('marked'.ucfirst($this->itemName), $markedItems);
			}
		}
	}

	/**
	 * @see MarkableLostAndFoundItem::mark()	 
	 */
	public function isMarked() {
		$sessionVars = WCF::getSession()->getVars();
		if (isset($sessionVars['marked'.ucfirst($this->itemName)])) {
			if (in_array($this->objectID, $sessionVars['marked'.ucfirst($this->itemName)])) return 1;
		}
		
		return 0;
	}
	
	/**
	 * Deletes the current item	 
	 */
	abstract public function delete();
	
	/**
	 * Deletes all items of the current type	 
	 */
	public static function deleteAll() { }			
	
	/**
	 * @see MarkableLostAndFoundItem::mark()	 
	 */
	public static function unmarkAll($itemName) {
		WCF::getSession()->unregister('marked'.ucfirst($itemName));
	}

	/**
	 * Fetches all marked items of a certain types from the session variable storage
	 *
	 * @param string $itemName
	 * @return mixed
	 */
	public static function getMarkedItems($itemName) {
		$sessionVars = WCF::getSession()->getVars();
		if (isset($sessionVars['marked'.ucfirst($itemName)])) {
			return $sessionVars['marked'.ucfirst($itemName)];
		}
		return null;
	}
}
?>