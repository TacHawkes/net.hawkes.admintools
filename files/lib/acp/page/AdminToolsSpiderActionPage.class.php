<?php
require_once(WCF_DIR.'lib/page/AbstractPage.class.php');
require_once(WCF_DIR.'lib/acp/admintools/spider/SpiderEditor.class.php');

/**
 * Performs AJAX actions for the spider page
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
class AdminToolsSpiderActionPage extends AbstractPage {	
	public $itemID = 0;
	public $item;
	public $items = array();
	public $url = '';
	public static $validFunctions = array('mark', 'unmark', 'delete', 'unmarkAll', 'deleteAll');
	
	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		if (isset($_REQUEST['classname'])) $this->classname = $_REQUEST['classname'];
		if (isset($_REQUEST['pagename'])) $this->pagename = $_REQUEST['pagename'];
		if (isset($_REQUEST['spiderID'])) $this->itemID = ArrayUtil::toIntegerArray($_REQUEST['spiderID']);
		if (isset($_REQUEST['itemID'])) $this->itemID = ArrayUtil::toIntegerArray($_REQUEST['itemID']);
		if (isset($_REQUEST['url'])) $this->url = $_REQUEST['url'];							
	}
	
	/**
	 * @see Page::show()
	 */
	public function show() {
		parent::show();
					
		if(is_array($this->itemID)) {
			foreach($this->itemID as $itemID) {				
				$this->items[] = new SpiderEditor($itemID);
			}
		}
		else $this->item = new SpiderEditor($this->itemID);		
		if (in_array($this->action, self::$validFunctions)) {			
			$this->{$this->action}();
		}					
		
	}
	
	/**
	 * Marks items	 
	 */
	public function mark() {
		if(is_array($this->itemID)) {
			foreach($this->items as $item) {
				$item->mark();
			}
		}
		else $this->item->mark();
	}
	
	/**
	 * Unmarks items	 
	 */
	public function unmark() {
		if(is_array($this->itemID)) {
			foreach($this->items as $item) {
				$item->unmark();
			}
		}
		else $this->item->unmark();
	}
	
	/**
	 * Unmarks all items	 
	 */
	public function unmarkAll() {
		SpiderEditor::unmarkAll();
	}
	
	/**
	 * Deletes items	 
	 */
	public function delete() {
		$this->item->delete();
		$this->item->unmark();
		if(!empty($this->url)) {
			HeaderUtil::redirect($this->url);
		}
	}
	
	/**
	 * Deletes all items	 
	 */
	public function deleteAll() {
		$spiders = SpiderEditor::getMarkedSpiders();
		SpiderEditor::deleteAll($spiders);
		$this->unmarkAll();
		if(!empty($this->url)) {
			HeaderUtil::redirect($this->url);
		}
	}

}
?>