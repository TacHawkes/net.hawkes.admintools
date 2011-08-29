<?php
require_once(WCF_DIR.'lib/acp/admintools/spider/Spider.class.php');

/**
 * Performs actions on a spider object
 *
 * This file is part of Admin Tools 2.
 *
 * Admin Tools 2 is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Admin Tools 2 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Admin Tools 2.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author	Oliver Kliebisch
 * @copyright	2009 Oliver Kliebisch
 * @license	GNU General Public License <http://www.gnu.org/licenses/>
 * @package	net.hawkes.admintools.wcf.functions
 * @subpackage acp.admintools.spider
 * @category WCF
 */
class SpiderEditor extends Spider  {
	
	/**
	 * Creates a new spider object
	 *
	 * @param string $spiderIdentifier
	 * @param string $spiderName
	 * @param string $spiderURL
	 */
	public static function create($spiderIdentifier, $spiderName, $spiderURL = '') {
		$sql = "INSERT INTO wcf".WCF_N."_admin_tools_spider
					(spiderIdentifier, spiderName, spiderURL)
					VALUES ('".escapeString($spiderIdentifier)."',
							'".escapeString($spiderName)."',
							'".escapeString($spiderURL)."')";
		WCF::getDB()->sendQuery($sql);
		
		$spiderID = WCF::getDB()->getInsertID();
		return new SpiderEditor($spiderID);
	}
	
	/**
	 * Updates a spider
	 *
	 * @param string $spiderIdentifier
	 * @param string $spiderName
	 * @param string $spiderURL
	 */
	public function update($spiderIdentifier, $spiderName, $spiderURL = '') {
		$sql = "UPDATE wcf".WCF_N."_admin_tools_spider
					SET spiderIdentifier = '".escapeString($spiderIdentifier)."',
						spiderName = '".escapeString($spiderName)."',
						spiderURL = '".escapeString($spiderURL)."'
					WHERE spiderID = ".$this->spiderID;
		WCF::getDB()->sendQuery($sql);
	}
	
	/**
	 * Tests whether this spider exists in the native spider list or in the admin tools list.
	 * Returns false if none exists.
	 *
	 * @param string $spiderIdentifier
	 */
	public static function test($spiderIdentifier) {		
		// native table
		$sql = "SELECT COUNT(spiderID) AS count FROM wcf".WCFN."_spider WHERE spiderIdentifier = '".$spiderIdentifier."'";
		$row = WCF::getDB()->getFirstRow($sql);
		if ($row['count'] > 0) return true;
		
		// admin tools table
		$sql = "SELECT COUNT(spiderID) AS count FROM wcf".WCFN."_admin_tools_spider WHERE spiderIdentifier = '".$spiderIdentifier."'";
		$row = WCF::getDB()->getFirstRow($sql);
		if ($row['count'] > 0) return true;
		
		return false;
	}
	
	/**
	 * Deletes this spider
	 *
	 */
	public function delete() {
		self::deleteAll(array($this->spiderID));
	}
	
	/**
	 * Deletes all passed spiders from the database
	 *
	 * @param array<integer> $spiderIDs
	 */
	public static function deleteAll($spiderIDs) {		
		if (!is_array($spiderIDs)) {
			$spiderIDs = array(intval($spiderIDs));
		}
		$sql = "DELETE FROM wcf".WCF_N."_admin_tools_spider
				WHERE spiderID IN(".implode(',', $spiderIDs).")";
		WCF::getDB()->sendQuery($sql);
	}
	
	/**
	 * Returns the marked spiders.
	 * 
	 * @return	array		marked spiders
	 */
	public static function getMarkedSpiders() {
		$sessionVars = WCF::getSession()->getVars();
		if (isset($sessionVars['markedSpiders'])) {
			return $sessionVars['markedSpiders'];
		}
		return null;
	}
	
	
	/**
	 * Marks this spider.
	 */
	public function mark() {
		$markedSpiders = self::getMarkedSpiders();
		if ($markedSpiders == null || !is_array($markedSpiders)) { 
			$markedSpiders = array($this->spiderID);
			WCF::getSession()->register('markedSpiders', $markedSpiders);
		}
		else {
			if (!in_array($this->spiderID, $markedSpiders)) {
				array_push($markedSpiders, $this->spiderID);
				WCF::getSession()->register('markedSpiders', $markedSpiders);
			}
		}
	}
	
	/**
	 * Unmarks this spider.
	 */
	public function unmark() {
		$markedSpiders = self::getMarkedSpiders();
		if (is_array($markedSpiders) && in_array($this->spiderID, $markedSpiders)) {
			$key = array_search($this->spiderID, $markedSpiders);
			
			unset($markedSpiders[$key]);
			if (count($markedSpiders) == 0) {
				self::unmarkAll();
			}
			else {
				WCF::getSession()->register('markedSpiders', $markedSpiders);
			}
		}
	}
	
	/**
	 * Unmarks all marked spiders.
	 */
	public static function unmarkAll() {
		WCF::getSession()->unregister('markedSpiders');
	}
	
	/**
	 * Synchronizes the admin tools spiders with the native spiders	 
	 */
	public static function synchronize() {
		require_once(WCF_DIR.'lib/system/cronjob/RefreshSearchRobotsCronjob.class.php');
		RefreshSearchRobotsCronjob::execute(null);		
		$sql = "INSERT IGNORE INTO wcf".WCF_N."_spider
					(spiderIdentifier, spiderName, spiderURL)
					SELECT spiderIdentifier, spiderName, spiderURL
					FROM wcf".WCF_N."_admin_tools_spider"; 
		WCF::getDB()->sendQuery($sql);
        WCF::getCache()->clear(WCF_DIR.'cache', 'cache.spiders.php');
	}
	
}
?>