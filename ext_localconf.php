<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

$_EXTKEY='bitsgallery';

t3lib_extMgm::addUserTSConfig('
	options.saveDocNew.tx_bitsgallery_common = 1
');
require_once(t3lib_extMgm::extPath('div') . 'class.tx_div.php');

if(TYPO3_MODE == 'FE') tx_div::autoLoadAll($_EXTKEY);



?>
