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
require_once(WCF_DIR.'lib/system/exception/SystemException.class.php');
require_once(WCF_DIR.'lib/system/template/TemplatePluginFunction.class.php');
require_once(WCF_DIR.'lib/system/template/Template.class.php');

/**
 * Outputs the acp menu sitemap
 * 
 * @author	Oliver Kliebisch
 * @copyright	2009 Oliver Kliebisch
 * @license	GNU General Public License <http://www.gnu.org/licenses/>
 * @package	net.hawkes.admintools
 * @subpackage system.template.plugin
 * @category WCF 
 */
class TemplatePluginFunctionAcpmenumap implements TemplatePluginFunction {
	protected $output = "";	
	protected $menuItems = array();
	
	/**
	 * @see TemplatePluginFunction::execute()
	 */
	public function execute($tagArgs, Template $tplObj) {
		if(!class_exists('WCFACP')) return;		
		$menuItemData = $tagArgs['menuItemData'];
		
		for ($i = 0; $i < count($menuItemData); $i++) {
			if (!isset($this->menuItems[$menuItemData[$i][0]])) {
				$this->menuItems[$menuItemData[$i][0]] = array();
			}
			
			$menuObject = array();
			$menuObject['menuItem'] = $menuItemData[$i][1];
			$menuObject['menuItemName'] = $menuItemData[$i][2];
			$menuObject['menuItemLink'] = $menuItemData[$i][3];
			$menuObject['menuItemIcon'] = $menuItemData[$i][4];
			
			$this->menuItems[$menuItemData[$i][0]][count($this->menuItems[$menuItemData[$i][0]])] = $menuObject;
		}
		$this->makeSiteMap();
		return $this->output;
	}

	/**
	 * Makes sitemap containers
	 *
	 * @param string $parentItem
	 * @param integer $depth
	 */
	protected function makeSiteMap($parentItem = '', $depth = 0) {
		if (!isset($this->menuItems[$parentItem])) return;
		
		$this->output .= "<ul";
		
		if ($depth == 2) {
			$this->output .= " class=\"sitemapDepth-".$depth." container-1\">\n";
		}
		else {
			$this->output .= " class=\"sitemapDepth-".$depth."\">\n";
		}
		
		for ($i=0; $i < count($this->menuItems[$parentItem]); $i++) {
			if ($depth == 1 && $i > 0 && $i % 3 == 0) {
				$this->output .= "<div class=\"clear\" />\n";
			}			
			$this->makeSiteMapItem($this->menuItems[$parentItem][$i], $depth);			
		}
		
		$this->output .= "</ul>\n";	
	}
	
	/**
	 * Makes a sitemap node
	 *
	 * @param string $item
	 * @param integer $depth
	 */
	protected function makeSiteMapItem($item, $depth = 0) {
		if (!empty($item['menuItemLink']) || $depth < 2) {
			$itemTitle = $item['menuItemName'];
			$this->output .= "<li";
			$headline = "<h".($depth +2).">\n";
			
			if (!empty($item['menuItemIcon'])) {
				$headline .= "<img src=\"".$item['menuItemIcon']."\" alt=\"\"/>\n";
			}
			
			//if (!empty($item['menuItemLink'])) {
				$headline .= "<a href=\"index.php?form=AdminToolsMenuEdit&amp;menuItem=".rawurlencode($item['menuItem'])."&amp;packageID=".PACKAGE_ID.SID_ARG_2ND."\">".$itemTitle."</a>\n";
			//}
			//else {
			//	$headline .= "<span>".$itemTitle."</span>\n";
			//}
			$headline .= "</h".($depth+2).">\n";
			
			if ($depth == 1) {
				$this->output .= " class=\"border\">\n";
				$this->output .= "<div class=\"containerHead\">\n".$headline."</div>\n";
			}
			else {
				$this->output .= ">\n".$headline;
			}
			
			//$this->output .= "</li>\n";			
		}
		
		$this->makeSiteMap($item['menuItem'], $depth + 1);
		$this->output .= "</li>\n";
	}
}
?>