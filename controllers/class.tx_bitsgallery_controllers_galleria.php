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
 * controller of the jquery galleria script
 *
 */
class tx_bitsgallery_controllers_galleria extends tx_lib_controller{

	 	var $templateKey;
		var $defaultAction 				= 'showGalleryAction';
		var $commonViewClass 			= 'tx_bitsgallery_views_common';
		var $template		 			= 'galleriaTemplate';
		var $extKey 					= 'bitsgallery';
		var $modelClassName				= 'tx_bitsgallery_models_common';
		var $entryListViewClassName 	= 'tx_bitsgallery_views_entrylistview';
		var $listentryClassName			= 'tx_bitsgallery_views_listentry';
		var $imgDirectory;
	
	function showGalleryAction() {
	
		$entryListClassName = tx_div::makeInstanceClassName( $this->entryListViewClassName );
		$entryClassName = tx_div::makeInstanceClassName( $this->listentryClassName );
		$viewClassName = tx_div::makeInstance( $this->commonViewClass );
		$modelClassName = tx_div::makeInstanceClassName( $this->modelClassName );
		
		$model = new $modelClassName($this);
		$model->controller($this);
		$model->chkIsDirectory ( $this->configurations->get('imgDirectory') );
		$model->setImgDirectory( $this->configurations->get('imgDirectory') );
		$model->getImagesFromDirectory();
		
		$resultList = $model->get('resultList');
		
		$entryList = new $entryListClassName($this);

		for($resultList->rewind(); $resultList->valid(); $resultList->next()) {
				$entry = new $entryClassName($this, $resultList->current());
				$entryList->append($entry);
		}
		
		$view = new $viewClassName($this);
		$view->set('entryList', $entryList);
		$view->set('imgDirectory', $model->getImgDirectory());
		
		$GLOBALS['TSFE']->additionalHeaderData['includeCSS'] = '<link rel="stylesheet" type="text/css" href="'. t3lib_extMgm::siteRelPath($this->extKey) .'templates/js/galleria/galleria.css" />';
		$GLOBALS['TSFE']->additionalHeaderData['includeJQuery'] = '<script type="text/javascript" src="' . t3lib_extMgm::siteRelPath($this->extKey) .'templates/js/jquery/jquery.min.galleria.js"></script>';
		$GLOBALS['TSFE']->additionalHeaderData['includeGalleria'] = '<script type="text/javascript" src="' . t3lib_extMgm::siteRelPath($this->extKey) .'templates/js/galleria/jquery.galleria.js"></script>';
		$GLOBALS['TSFE']->additionalHeaderData['includeOnReady'] = '<script type="text/javascript">
			$(document).ready(function(){
					
					$(".gallery_demo_unstyled").addClass("gallery_demo"); // adds new class name to maintain degradability
					
					$("ul.gallery_demo").galleria({
						history   : true, // activates the history object for bookmarking, back-button etc.
						clickNext : true, // helper for making the image clickable
						insert    : "#main_image", // the containing selector for our main image
						onImage   : function(image,caption,thumb) { // let"s add some image effects for demonstration purposes
							
							// fade in the image & caption
							image.css("display","none").fadeIn(1000);
							caption.css("display","none").fadeIn(1000);
							
							// fetch the thumbnail container
							var _li = thumb.parents("li");
							
							// fade out inactive thumbnail
							_li.siblings().children("img.selected").fadeTo(500,0.3);
							
							// fade in active thumbnail
							thumb.fadeTo("fast",1).addClass("selected");
							
							// add a title for the clickable image
							image.attr("title","Next image >>");
						},
						onThumb : function(thumb) { // thumbnail effects goes here
							
							// fetch the thumbnail container
							var _li = thumb.parents("li");
							
							// if thumbnail is active, fade all the way.
							var _fadeTo = _li.is(".active") ? "1" : "0.3";
							
							// fade in the thumbnail when finnished loading
							thumb.css({display:"none",opacity:_fadeTo}).fadeIn(1500);
							
							// hover effects
							thumb.hover(
								function() { thumb.fadeTo("fast",1); },
								function() { _li.not(".active").children("img").fadeTo("fast",0.3); } // don"t fade out if the parent is active
							)
						}
		});
	});
	</script>';


		$view->render($this->configurations->get( $this->template ));
		$className = tx_div::makeInstanceClassName('tx_lib_translator');
		$translator = new $className($this, $view);
		return $translator->translateContent();
		
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/bitsgallery/controllers/class.tx_bitsgallery_controllers_galleria.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/bitsgallery/controllers/class.tx_bitsgallery_controllers_galleria.php']);
}
?>
