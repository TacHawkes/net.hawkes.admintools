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
require_once(WCF_DIR.'lib/acp/admintools/function/AdminToolsFunction.class.php');
require_once(WCF_DIR.'lib/system/event/EventHandler.class.php');

/**
 * This class is very similar to the abstract action but I don't want that eventlisteners on AbstractAction activate here.
 * It is a basic container for executing actions and leaving a return message (not for cronjobs)
 *
 * @author	Oliver Kliebisch
 * @copyright	2009 Oliver Kliebisch
 * @license	GNU General Public License <http://www.gnu.org/licenses/>
 * @package	net.hawkes.admintools
 * @subpackage acp.admintools.function
 * @category WCF 
 */
abstract class AbstractAdminToolsFunction implements AdminToolsFunction {		
	
	/**
	 * A parameter array containing the option values for the current function
	 * 
	 * @var array<mixed>
	 */
	public $data;
	
	/**
	 * Executes the function
	 * 
	 * @param array $data	 
	 */
	public function execute($data) {
		$this->data = &$data;
		EventHandler::fireAction($this, 'execute');
	}
	
	/**
	 * Function to set return messages that will be displayed on the functions page
	 * 
	 * @param string $type The return message type
	 * @param string $message The return message
	 */
	public function setReturnMessage($type = 'success', $messageText, $override = false) {
		// detect cronjob action
		if (!class_exists('WCFACP')) return;
		
		$message = array();
		if(is_array(WCF::getSession()->getVar('functionReturnMessage'))) {
			$message = WCF::getSession()->getVar('functionReturnMessage');
		}
		if($override) {
			$message[$this->data['functionID']] = array($type => $messageText);
		}
		else {
			if(!isset($message[$this->data['functionID']])) $message[$this->data['functionID']] = array();
			if(!isset($message[$this->data['functionID']][$type])) {
				$message[$this->data['functionID']][$type] = $messageText;			
			}
			else $message[$this->data['functionID']][$type] .= $messageText;
		}
		WCF::getSession()->register('functionReturnMessage', $message);
	}		
	
	/**
	 * This function is called after the function has been executed
	 */
	protected function executed() {
		EventHandler::fireAction($this, 'executed');
	}
}
?>