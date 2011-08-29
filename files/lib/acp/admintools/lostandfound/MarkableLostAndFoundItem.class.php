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
 * This interface defines the basic methods of a markable lost and found item
 *
 * @author	Oliver Kliebisch
 * @copyright	2009 Oliver Kliebisch
 * @license	GNU General Public License <http://www.gnu.org/licenses/>
 * @package	net.hawkes.admintools
 * @subpackage acp.admintools.lostandfound
 * @category WCF 
 */
interface MarkableLostAndFoundItem {
	
	/**
	 * Marks the current item
	 *
	 */
	public function mark();
	
	/**
	 * Unmarks the current item
	 *
	 */
	public function unmark();
	
	/**
	 * Checks if the current item is marked
	 * 
	 * @return integer
	 */
	public function isMarked();
	
	/**
	 * Unmarks all items of certain item type
	 * 
	 * @param string $itemName
	 */
	public static function unmarkAll($itemName);
	
}
?>