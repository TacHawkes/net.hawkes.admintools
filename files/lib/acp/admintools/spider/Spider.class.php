<?php
require_once(WCF_DIR.'lib/data/DatabaseObject.class.php');

/**
 * Provides read access to spiders
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
class Spider extends DatabaseObject  {
	protected $sqlJoins = '';
	protected $sqlSelects = '';
	protected $sqlGroupBy = '';
	
	/**
	 * Gets the main data of the passed spider	 
	 *
	 * @param 	string 		$spiderID
	 * @param 	array 		$row	 
	 */
	public function __construct($spiderID, $row = null) {				
		// execute sql statement
		$sqlCondition = '';
		if ($spiderID !== null) {
			$sqlCondition = "spider.spiderID = ".$spiderID;
		}		
		
		if (!empty($sqlCondition)) {
			$sql = "SELECT 	".$this->sqlSelects."
					spider.*
				FROM 	wcf".WCF_N."_admin_tools_spider spider
					".$this->sqlJoins."
				WHERE 	".$sqlCondition.
					$this->sqlGroupBy;
			$row = WCF::getDB()->getFirstRow($sql);
		}
		
		// handle result set
		parent::__construct($row);
	}
	
	/**
	 * Returns true, if this spider is marked in the active session.
	 */
	public function isMarked() {
		$sessionVars = WCF::getSession()->getVars();
		if (isset($sessionVars['markedSpiders'])) {
			if (in_array($this->spiderID, $sessionVars['markedSpiders'])) return 1;
		}
		
		return 0;
	}

}
?>