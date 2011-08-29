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
require_once(WCF_DIR.'lib/page/SortablePage.class.php');

/**
 * The page for handling lost and found items. Backups, attachments and avatars are harcodet. Further functions can be used via event listeners
 * 
 * @author	Oliver Kliebisch
 * @copyright	2009 Oliver Kliebisch
 * @license	GNU General Public License <http://www.gnu.org/licenses/>
 * @package	net.hawkes.admintools
 * @subpackage acp.page
 * @category WCF 
 */
class AdminToolsLostAndFoundPage extends SortablePage  {
	public $activeMenuItem = 'wcf.acp.menu.link.admintools.lostandfound';
	public $templateName = 'adminToolsLostAndFound';
	public $activeTabMenuItem = 'backup';
	public $activeSubTabMenuItem = 'database';
	public $markedItems = 0;
	public $itemsPerPage = 20;
	public $pageNo = 1;
	public $classname = '';

	public $count = 0;
	
	/**
	 * The object array storing the lost and found items
	 * 
	 * @var array<MarkableLostAndFoundItem> 
	 */
	public $itemData = array();

	/**
	 * @see Page::readParameters() 
	 */
	public function readParameters() {
		parent::readParameters();

		if (isset($_GET['activeTabMenuItem'])) $this->activeTabMenuItem = StringUtil::trim($_GET['activeTabMenuItem']);
		if (isset($_GET['activeSubTabMenuItem'])) $this->activeSubTabMenuItem = StringUtil::trim($_GET['activeSubTabMenuItem']);
	}
	
	/**
	 * @see Page::readData() 
	 */
	public function readData() {
		$functionName = 'read'.ucfirst($this->activeTabMenuItem);
		if(method_exists($this, $functionName)) {
			$this->{$functionName}();
		}

		parent::readData();
	}

	/**
	 * Reads the backup items
	 */ 
	protected function readBackup() {
		$this->activeSubTabMenuItem = 'filesystem';
		require_once(WCF_DIR.'lib/acp/admintools/lostandfound/BackupFilesystemLostAndFoundItem.class.php');
		BackupFilesystemLostAndFoundItem::createVirtualIDSpace();
		$this->markedItems = intval(count(BackupFilesystemLostAndFoundItem::getMarkedItems('backupFilesystem')));
		$this->classname = 'BackupFilesystemLostAndFoundItem';
		chdir(WCF_DIR.'acp/backup');
		$dh= opendir(WCF_DIR.'acp/backup');
		if(!$dh) {
			$this->count = 0;
			return;
		}
		$i=0;
		while($file = readdir ($dh)) {
			if($file != '.' && $file != '..' && $file != '.htaccess' && !is_dir($file)) {
				if(($i < ($this->pageNo-1)*$this->itemsPerPage) || ($i > $this->pageNo*$this->itemsPerPage)) {
					$i++;
					continue;
				}
				$backup = new BackupFilesystemLostAndFoundItem(BackupFilesystemLostAndFoundItem::getVirtualID('backupFilesystem', $file));
				$backup->filename = $file;
				$backup->filesize = round((filesize($file) / 1000),2).' kB';
				$backup->fileLastModTime = filemtime($file);
				$this->itemData[] = $backup;
				$i++;
			}
		}
		closedir($dh);
		$this->count = $i;
	}

	/**
	 * Reads attachment items
	 */ 
	public function readAttachments() {
		switch($this->activeSubTabMenuItem) {
			case 'database' :
				require_once(WCF_DIR.'lib/acp/admintools/lostandfound/AttachmentsDatabaseLostAndFoundItem.class.php');
				$this->markedItems = intval(count(AttachmentsDatabaseLostAndFoundItem::getMarkedItems('attachmentsDatabase')));
				$this->classname = 'AttachmentsDatabaseLostAndFoundItem';
				$sql = "SELECT attachment.*, user.username FROM wcf".WCF_N."_attachment attachment
						LEFT JOIN wcf".WCF_N."_user user
						ON (user.userID = attachment.userID)";
				$result = WCF::getDB()->sendQuery($sql);
				$i = 0;
				while($row = WCF::getDB()->fetchArray($result)) {
					if(!is_file(WCF_DIR.'attachments/attachment-'.$row['attachmentID'])) {
						if(($i < ($this->pageNo-1)*$this->itemsPerPage) || ($i > $this->pageNo*$this->itemsPerPage)) {
							$i++;
							continue;
						}
						$attachment = new AttachmentsDatabaseLostAndFoundItem($row['attachmentID']);
						$attachment->filename = $row['attachmentName'];
						$attachment->filesize = round((($row['attachmentSize']) / 1000),2).' kB';
						$attachment->fileLastModTime = $row['uploadTime'];
						$attachment->user = $row['username'];
						$this->itemData[] = $attachment;
					}
				}
				$this->count = $i;
				break;
			case 'filesystem' :
				require_once(WCF_DIR.'lib/acp/admintools/lostandfound/AttachmentsFilesystemLostAndFoundItem.class.php');
				AttachmentsFilesystemLostAndFoundItem::createVirtualIDSpace();
				$this->markedItems = intval(count(AttachmentsFilesystemLostAndFoundItem::getMarkedItems('attachmentsFilesystem')));
				$this->classname = 'AttachmentsFilesystemLostAndFoundItem';
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
					$this->count = count($physicalAttachments);
					$i = 0;
					foreach($physicalAttachments as $attachmentID) {
						if(($i < ($this->pageNo-1)*$this->itemsPerPage) || ($i > $this->pageNo*$this->itemsPerPage)) {
							$i++;
							continue;
						}
						$file = WCF_DIR.'attachments/attachment-'.$attachmentID;
						$attachment = new AttachmentsFilesystemLostAndFoundItem(AttachmentsFilesystemLostAndFoundItem::getVirtualID('attachmentsFilesystem', $file));
						$attachment->filename = $file;
						$attachment->filesize = round((filesize($file) / 1000),2).' kB';
						$attachment->fileLastModTime = filemtime($file);
						$this->itemData[] = $attachment;
						$i++;
					}
				}
				closedir($dh);
				break;
		}
	}
	
	/**
	 * Reads avatar items
	 */ 
	public function readAvatars() {
		switch($this->activeSubTabMenuItem) {
			case 'database' :
				require_once(WCF_DIR.'lib/acp/admintools/lostandfound/AvatarsDatabaseLostAndFoundItem.class.php');
				$this->markedItems = intval(count(AvatarsDatabaseLostAndFoundItem::getMarkedItems('avatarsDatabase')));
				$this->classname = 'AvatarsDatabaseLostAndFoundItem';
				$sql = "SELECT avatar.*, user.username FROM wcf".WCF_N."_avatar avatar
						LEFT JOIN wcf".WCF_N."_user user
						ON (user.userID = avatar.userID)";
				$result = WCF::getDB()->sendQuery($sql);
				$i = 0;
				while($row = WCF::getDB()->fetchArray($result)) {
					if(!is_file(WCF_DIR.'images/avatars/avatar-'.$row['avatarID'].'.'.$row['avatarExtension'])) {
						if(($i < ($this->pageNo-1)*$this->itemsPerPage) || ($i > $this->pageNo*$this->itemsPerPage)) {
							$i++;
							continue;
						}
						$avatar = new AvatarsDatabaseLostAndFoundItem($row['avatarID']);
						$avatar->filename = $row['avatarName'];						
						$avatar->user = $row['username'];						
						$this->itemData[] = $avatar;
						$i++;
					}
				}
				$this->count = $i;
				break;
			case 'filesystem' :
				require_once(WCF_DIR.'lib/acp/admintools/lostandfound/AvatarsFilesystemLostAndFoundItem.class.php');
				AvatarsFilesystemLostAndFoundItem::createVirtualIDSpace();
				$this->markedItems = intval(count(AvatarsFilesystemLostAndFoundItem::getMarkedItems('avatarsFilesystem')));
				$this->classname = 'AvatarsFilesystemLostAndFoundItem';
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
					$this->count = count($physicalAvatars);
					$i = 0;
					foreach($physicalAvatars as $avatarID) {
						if($i <= ($this->pageNo-1)*$this->itemsPerPage) {
							$i++;
							continue;
						}
						else if($i > $this->pageNo*$this->itemsPerPage) break;
						$file = WCF_DIR.'images/avatars/avatar-'.$avatarID.$avatars[$avatarID];
						$avatar = new AvatarsFilesystemLostAndFoundItem(AvatarsFilesystemLostAndFoundItem::getVirtualID('avatarsFilesystem', $file)); 
						$avatar->filename = $file;						
						$avatar->filesize = round((filesize($file) / 1000),2).' kB';
						$avatar->fileLastModTime = filemtime($file);
						$this->itemData[] = $avatar;						
						$i++;
					}
				}
				closedir($dh);
				break;
		}
	}

	/**
	 * @see Page::assignVariables()	 
	 */
	public function assignVariables() {
		parent::assignVariables();

		WCF::getTPL()->assign(array('activeTabMenuItem' => $this->activeTabMenuItem,
									'activeSubTabMenuItem' => $this->activeSubTabMenuItem,
									'markedItems' => $this->markedItems,									
									'defaultSortField' => $this->defaultSortField,
									'defaultSortOrder' => $this->defaultSortOrder,
									'classname' => $this->classname,
									'itemData' => $this->itemData));
	}

	/**
	 * @see Page::show()	
	 */
	public function show() {
		WCF::getUser()->checkPermission('admin.system.admintools.canView');
		
		WCFACP::getMenu()->setActiveMenuItem($this->activeMenuItem);

		parent::show();
	}
	
	/**
	 * @see MultipleLinkPage::countItems()	 	 
	 */
	public function countItems() {
		parent::countItems();

		return $this->count;
	}
}
?>