<?php
require_once(WCF_DIR.'lib/page/SortablePage.class.php');
require_once(WCF_DIR.'lib/acp/admintools/spider/Spider.class.php');

/**
 * Displays the spiders
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
 * @package	net.hawkes.admintools
 * @subpackage acp.page
 * @category WCF 
 */
class AdminToolsSpiderListPage extends SortablePage {
	public $templateName = 'adminToolsSpider';
	public $spiders = array();
	public $markedSpiders = 0;	
	public $defaultSortField = 'spiderName';
	public $url = '';
	
	/**
	 * @see SortablePage::validateSortField()	 
	 */
	public function validateSortField() {
		parent::validateSortField();
		
		switch($this->sortField) {
			case 'spiderName' :
			case 'spiderIdentifier' :
			case 'spiderURL' :
			case 'spiderID'	:
				break;
			default : $this->sortField = $this->defaultSortField;
		}
	}
	
	/**
	 * @see Page::readData()	 
	 */
	public function readData() {
		parent::readData();

		$sql = "SELECT * FROM wcf".WCF_N."_admin_tools_spider
				ORDER BY ".$this->sortField." ".$this->sortOrder;
		$result = WCF::getDB()->sendQuery($sql, $this->itemsPerPage, ($this->pageNo - 1) * $this->itemsPerPage);
		
		while($row = WCF::getDB()->fetchArray($result)) {
			$this->spiders[] = new Spider(null, $row); 
		}
		
		// build page url
		$this->url = 'index.php?page=AdminToolsSpiderList&pageNo='.$this->pageNo.'&sortField='.$this->sortField.'&sortOrder='.$this->sortOrder.'&packageID='.PACKAGE_ID.SID_ARG_2ND_NOT_ENCODED;
		
		$sessionVars = WCF::getSession()->getVars();
		if (isset($sessionVars['markedSpiders'])) {
			$this->markedSpiders = count($sessionVars['markedSpiders']);
		}
	}
	
	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();

		WCF::getTPL()->assign(array(    		
			'spiders' => $this->spiders,
			'markedSpiders' => $this->markedSpiders,
			'url' => $this->url
		));
	}

	/**
	 * @see Page::show()
	 */
	public function show() {    
		// enable menu item
		WCFACP::getMenu()->setActiveMenuItem('wcf.acp.menu.link.admintools.spider');

		WCF::getUser()->checkPermission('admin.system.admintools.canView');
		
		// show page
		parent::show();
	}
	
	public function countItems() {
		parent::countItems();
		
		$sql = "SELECT COUNT(*) AS count FROM wcf".WCF_N."_admin_tools_spider";
		$row = WCF::getDB()->getFirstRow($sql);
		
		return $row['count'];
	}
}
?>