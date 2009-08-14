<?php

/**
 * Views entry list
 *
 */

class tx_bitsgallery_views_common extends tx_lib_phpTemplateEngine {
	
	
	function printFormTag($id) {
		$link = tx_div::makeInstance('tx_lib_link');
		$link->destination($this->getDestination());
		$link->noHash();
		$action = $link->makeUrl();
		printf(chr(10) . '<form id="%s" action="%s" method="post">' . chr(10), $id, $action);
	}

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/bitsgallery/views/class.tx_bitsgallery_views_common.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/bitsgallery/views/class.tx_bitsgallery_views_common.php']);
}
?>
