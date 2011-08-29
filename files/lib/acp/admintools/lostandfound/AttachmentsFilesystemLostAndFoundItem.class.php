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
 * Attachments Filesystem Item
 *
 * @author	Oliver Kliebisch
 * @copyright	2009 Oliver Kliebisch
 * @license	GNU General Public License <http://www.gnu.org/licenses/>
 * @package	net.hawkes.admintools
 * @subpackage acp.admintools.lostandfound
 * @category WCF
 */
class AttachmentsFilesystemLostAndFoundItem extends AbstractLostAndFoundFileSystemItem {

	/**
	 * Cronstructs the object by passing the objectID
	 *
	 * @param integer $attachmentID
	 */
	public function __construct($attachmentID) {
		parent::__construct('attachmentsFilesystem', $attachmentID);
	}

	/**
	 * @see AbstractLostAndFoundFileSystemItem::createVirtualIDSpace()
	 */
	public static function createVirtualIDSpace() {
		$attachments = array();
		chdir(WCF_DIR.'attachments');
		$dh=opendir(WCF_DIR.'attachments');
		$attachmentIDs = array();
		while($file = readdir ($dh)) {
			if(preg_match("/^(attachment|thumbnail).*/",$file) && $file != '.' && $file != '..' && $file != '.htaccess' && !preg_match("/^.*\.php$/",$file)) {
				$attachmentID = (int) preg_replace("/.*\-(\d+)$/", "$1", $file);
				if($attachmentID > 0) {
					$attachmentIDs[] = $attachmentID;
				}
			}
		}
		if (count($attachmentIDs)) {
			$sql = "SELECT attachmentID FROM wcf".WCF_N."_attachment WHERE attachmentID IN (".implode(',', $attachmentIDs).")";
			$result = WCF::getDB()->sendQuery($sql);
			$physicalAttachments = array_flip($attachmentIDs);
			while($row = WCF::getDB()->fetchArray($result)) {
				unset($physicalAttachments[$row['attachmentID']]);
			}
			$physicalAttachments = array_keys($physicalAttachments);
			foreach($physicalAttachments as $attachmentID) {
				$file = WCF_DIR.'attachments/attachment-'.$attachmentID;
				$attachments[] = $file;
			}
		}
		closedir($dh);
		self::$virtualFileIDs['attachmentsFilesystem'] = $attachments;
		WCF::getSession()->register('virtualLostAndFoundIDs', self::$virtualFileIDs);
	}

	/**
	 * @see AbstractLostAndFounDatabaseItem::delete()
	 */
	public function delete() {
		if (isset(self::$virtualFileIDs['attachmentsFilesystem'][$this->objectID])) {
			$file = self::$virtualFileIDs['attachmentsFilesystem'][$this->objectID];
			$attachmentID = (int) preg_replace("/.*\-(\d+)$/", "$1", $file);
			// delete attachment file
			if (file_exists(WCF_DIR.'attachments/attachment-'.$attachmentID)) @unlink(WCF_DIR.'attachments/attachment-'.$attachmentID);

			// delete thumbnail, if exists
			if (file_exists(WCF_DIR.'attachments/thumbnail-'.$attachmentID)) @unlink(WCF_DIR.'attachments/thumbnail-'.$attachmentID);
		}
	}

	/**
	 * @see AbstractLostAndFounDatabaseItem::deleteAll()
	 */
	public static function deleteAll() {
		$itemIDs = self::getMarkedItems('attachmentsFilesystem');
		foreach($itemIDs as $itemID) {
			$item = new AttachmentsFilesystemLostAndFoundItem($itemID);
			$item->delete();
		}
	}
}
?>