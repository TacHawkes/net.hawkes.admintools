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
 * Outputs a group options multi select
 * 
 * @author	Oliver Kliebisch
 * @copyright	2009 Oliver Kliebisch
 * @license	GNU General Public License <http://www.gnu.org/licenses/>
 * @package	net.hawkes.admintools
 * @subpackage system.template.plugin
 * @category WCF 
 */
class TemplatePluginFunctionAcpmenugroupoptions implements TemplatePluginFunction {
	public $html = '';
	public $selected = array();
	/**
	 * @see TemplatePluginFunction::execute()
	 */
	public function execute($tagArgs, Template $tplObj) {

		if (!isset($tagArgs['options']) || !is_array($tagArgs['options'])) {
			throw new SystemException("missign 'options' argument in htmlCheckboxes tag", 12001);
		}

		if (isset($tagArgs['disableEncoding']) && $tagArgs['disableEncoding']) {
			$this->disableEncoding = true;
		}
		else {
			$this->disableEncoding = false;
		}

		// get selected values
		if (isset($tagArgs['selected'])) {
			$this->selected = $tagArgs['selected'];
		}
		

		if (!isset($tagArgs['separator'])) {
			$tagArgs['separator'] = '';
		}

		// build html
		foreach ($tagArgs['options'] as $key => $value) {
			$this->buildHtml($value);			
		}

		return $this->html;
	}

	/**
	 * builds the HTML output
	 *
	 * @param string $item
	 * @param integer $depth
	 */
	protected function buildHtml($item, $depth=0) {		
		if(isset($item['categoryID'])) {
			$first = false;
			if($depth == 0) {
				$first = true;
				$this->html .= "<optgroup label='".$item['localizedName']."'>";
			}					
			foreach($item['options'] as $option) {
				$this->buildHtml($option, $depth);
			}
			if(isset($item['categories'])) {
				$depth++;
				foreach($item['categories'] as $category) {
					$this->buildHtml($category, $depth);
				}
			}
			if($first) $this->html .= "</optgroup>";
		}
		else if(isset($item['optionID'])) {
			$selected='';
			if(in_array($item['optionName'], $this->selected)) $selected='selected="selected"';
			$this->html .= "<option label='".WCF::getLanguage()->get('wcf.acp.group.option.'.$item['optionName'])."' value='".$item['optionName']."' ".$selected.">".$item['localizedName']."</option>";			
		}		
	}
}

?>