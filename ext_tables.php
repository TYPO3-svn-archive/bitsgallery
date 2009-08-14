<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');


require_once(t3lib_extMgm::extPath('div', 'class.tx_div.php'));
t3lib_extMgm::addStaticFile($_EXTKEY, './configuration', 'bitsGallery');                              // (extKey, path, label)
t3lib_extMgm::addPlugin(Array('LLL:EXT:bitsgallery/locallang_db.php:pluginLabel', 'tx_bitsgallery'));       // array(title, pluginKey)
t3lib_extMgm::addPiFlexFormValue('tx_bitsgallery', 'FILE:EXT:bitsgallery/configuration/flexform_common.xml');     // (pluginKey, path)
$TCA['tt_content']['types']['list']['subtypes_excludelist']['tx_bitsgallery']='layout,select_key,pages,recurs';
$TCA['tt_content']['types']['list']['subtypes_addlist']['tx_bitsgallery']='pi_flexform';

if (TYPO3_MODE=='BE')	$TBE_MODULES_EXT['xMOD_db_new_content_el']['addElClasses']['tx_bitsgallery_wizicon'] = t3lib_extMgm::extPath($_EXTKEY).'controllers/class.tx_bitsgallery_wizicon.php';


?>