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
require_once(WCF_DIR.'lib/data/message/attachment/AttachmentsEditor.class.php');

/**
 * Attachments Database Item
 *
 * @author	Oliver Kliebisch
 * @copyright	2009 Oliver Kliebisch
 * @license	GNU General Public License <http://www.gnu.org/licenses/>
 * @package	net.hawkes.admintools
 * @subpackage acp.admintools.lostandfound
 * @category WCF 
 */
class AttachmentsDatabaseLostAndFoundItem extends AbstractLostAndFoundDatabaseItem {	

	/**
	 * Creates the object by setting the objectID
	 *
	 * @param integer $attachmentID
	 */
	public function __construct($attachmentID) {
		parent::__construct('attachmentsDatabase', $attachmentID);
	}
	
	/**
	 * @see AbstractLostAndFounDatabaseItem::delete	 
	 */
	public function delete() {
		$editor = new AttachmentsEditor();
		$editor->delete($this->objectID);
	}
	
	/**
	 * @see AbstractLostAndFounDatabaseItem::deleteAll()	 
	 */
	public static function deleteAll() {
		$itemIDs = self::getMarkedItems('attachmentsDatabase');		
		foreach($itemIDs as $itemID) {
			$item = new AttachmentsDatabaseLostAndFoundItem($itemID);
			$item->delete();
		}
	}
}
?>