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


/**
 * controller of the jquery galleryview script
 *
 */
class tx_bitsgallery_controllers_galleryview extends tx_lib_controller{

		
		/**
		 * the default action
		 *
		 */
		var $defaultAction 				= 'showGalleryAction';
		
		/**
		 * the template to use
		 *
		 */
		var $commonViewClass 			= 'tx_bitsgallery_views_common';
		
		/**
		 * the template to use
		 *
		 */
		var $template		 			= 'galleryviewTemplate';
		
		/**
		 * the extension key
		 *
		 */
		var $extKey 					= 'bitsgallery';
		
		/**
		 * the model to use
		 *
		 */
		var $modelClassName				= 'tx_bitsgallery_models_common';
		
		/**
		 * the view class container for the list of images
		 *
		 */
		var $entryListViewClassName 	= 'tx_bitsgallery_views_entrylistview';
		
		/**
		 * the view class container for the entries of images
		 *
		 */
		var $listentryClassName			= 'tx_bitsgallery_views_listentry';
		
		/**
		 * the directory where the images are stored
		 *
		 */
		var $imgDirectory;
		
		/**
		 * the column in database where images are stored
		 *
		 */
		var $imgColumn;
	
	
	
	function showGalleryAction() {
		
		switch( $this->configurations->get('mode') ){
		
			case 'DIRECTORY':
			
						$entryListClassName = tx_div::makeInstanceClassName($this->entryListViewClassName);
						$entryClassName = tx_div::makeInstanceClassName($this->listentryClassName);
						$viewClassName = tx_div::makeInstance($this->commonViewClass);
						
						$modelClassName = tx_div::makeInstanceClassName($this->modelClassName);
						$model = new $modelClassName($this);
						$model->controller($this);
						$model->chkIsDirectory ( $this->configurations->get('imgDirectory') );
						$model->setImgDirectory( $this->configurations->get('imgDirectory') );
						$model->setModelDataEntry('resultList'); // this is the key you have to call in the template
						$model->getImagesFromDirectory();
						
			
			break;
			
			case 'RECORDS':
			
			/*
					$modelClassName = tx_div::makeInstanceClassName($this->modelClassName);
					$model = new $modelClassName($this);
					$model->controller($this);
					$model->setImgColumn( $this->imgColumn );
					$model->setSelect('uid,pid,bodytext,CType');
					$model->setTable($this->imageTable);
					$model->setWhere($this->imageTable.'.pid ='.$GLOBALS['_GET']['uid'].'  AND tt_content.CType="text"');
					//$model->setWhere('lookup_param='.$GLOBALS['TYPO3_DB']->fullQuoteStr(t3lib_div::_GP('param1'), $model->getTable));
					$model->setModelDataEntry('resultList'); // this is the key you have to call in the template
					$model->getImagesFromDB();

			*/
					
					
			break;
		
		}
		
		$resultList = $model->get( $model->getModelDataEntry() );
						
		$entryList = new $entryListClassName($this);

		for($resultList->rewind(); $resultList->valid(); $resultList->next()) {
				$entry = new $entryClassName($this, $resultList->current());
				$entryList->append($entry);
		}
		
		$view = new $viewClassName($this);
		$view->set('entryList', $entryList);
		$view->set('imgDirectory', $model->getImgDirectory());
		
		
		$GLOBALS['TSFE']->additionalHeaderData['includeJQuery'] = '<script type="text/javascript" src="' . t3lib_extMgm::siteRelPath($this->extKey) .'templates/js/jquery/jquery-1.3.2.min.js"></script>';
		$GLOBALS['TSFE']->additionalHeaderData['includeEase'] = '<script type="text/javascript" src="' . t3lib_extMgm::siteRelPath($this->extKey) .'templates/js/jqgalleryview/jquery.easing.1.3.js"></script>';
		$GLOBALS['TSFE']->additionalHeaderData['includeGallery'] = '<script type="text/javascript" src="' . t3lib_extMgm::siteRelPath($this->extKey) .'templates/js/jqgalleryview/jquery.galleryview-2.0-pack.js"></script>';
		$GLOBALS['TSFE']->additionalHeaderData['includeTimer'] = '<script type="text/javascript" src="' . t3lib_extMgm::siteRelPath($this->extKey) .'templates/js/jqgalleryview/jquery.timers-1.1.2.js"></script>';
		$GLOBALS['TSFE']->additionalHeaderData['tx_bitsgallery'] = '<link rel="stylesheet" type="text/css" href="'. t3lib_extMgm::siteRelPath($this->extKey) .'templates/js/jqgalleryview/galleryview.css" />';
		$GLOBALS['TSFE']->additionalHeaderData['includeOnReady'] = '<script type="text/javascript">
			$(document).ready(function(){
					$("#gallery").galleryView({
							panel_width:'.$this->configurations->get('panel_width').',
							panel_height: '.$this->configurations->get('panel_height').',
							frame_width: '.$this->configurations->get('frame_width').',
							frame_height: '.$this->configurations->get('frame_height').',
							transition_speed: '.$this->configurations->get('transition_speed').',
							easing: "'.$this->configurations->get('easing').'",
							transition_interval: '.$this->configurations->get('transition_interval').',
							nav_theme: "'.$this->configurations->get('nav_theme').'"
					});
			});
		</script>';
		$view->render($this->configurations->get( $this->template ));
						
		$className = tx_div::makeInstanceClassName('tx_lib_translator');
		$translator = new $className($this, $view);
		return $translator->translateContent();
		
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/bitsgallery/controllers/class.tx_bitsgallery_controllers_galleryview.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/bitsgallery/controllers/class.tx_bitsgallery_controllers_galleryview.php']);
}
?>
