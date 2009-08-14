<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2009 Joachim Karl
 *  Contact: joachim.karl@bitsafari.de
 *  All rights reserved
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 ***************************************************************/

/** 
 * Depends on:  
 *
 * @author Joachim Karl <joachim.karl@bitsafari.de>
 * @package TYPO3
 * @subpackage bitsgallery
 */

class tx_bitsgallery_views_listentry extends tx_lib_phpTemplateEngine {
	
	
	function printAsImage($str) {
			
			$image = tx_div::makeInstance('tx_lib_image');
			$image->path($str);
			print $image->make();
	}

	function renderImage() {
			// make the link
			$image = tx_div::makeInstance('tx_lib_image');
			
			print_r($this->getDesignator());
			
			/*
			$link->designator($this->getDesignator());
			
			$link->destination($this->controller->parameters->get('backId'));
			$link->anchor($this->controller->parameters->get('backAnchor'));
			$searchString = $this->controller->parameters->get('searchString');
			if(strlen($searchString)) {
				$link->overruled(array('searchString' => $searchString));
				$link->noHash(); // Don't cache this dynamic query, else we risk a DOS attack!!!!
								 // But the page will completely rebuild. TODO: USER/USER_INT switch 
			}
			$link->parameters(array('action' => 'filter'));
			$link->label($label, 1);
	
			// print
			print $link->makeTag();
			*/
	}
	

}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/bitsgallery/views/class.tx_bitsgallery_views_listentry.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/bitsgallery/views/class.tx_bitsgallery_views_listentry.php']);
}
?>
