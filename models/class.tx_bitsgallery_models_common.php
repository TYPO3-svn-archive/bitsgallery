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
 * Model of the subpackage bitsgallery 
 *
 */
class tx_bitsgallery_models_common extends tx_lib_object {
		var $obj_table;

	public function __construct() {
		parent::__construct();
		$this->obj_table = 'tx_tupaja_objects';
		
	}
	
	
	function setSelect($val) {
		$this->select = $val;
	}
	
	function getSelect(){
        return $this->select;
    }
	
	function setTable($table){
        $this->table = $table;
    }
	
	function getTable(){
        return $this->table;
    }
	
	function chkIsDirectory($val){
			
			return $this->isDirectory = is_dir($val);
	
    }
	
	function setImgDirectory($val){
			$this->imgDirectory = $val;
    }
	
	function getImgDirectory(){
        	return $this->imgDirectory;
    }
	
	function setWhere($val){
        $this->where = $val;
    }
	
	function getWhere(){
        return $this->where;
    }
	
	function setModelDataEntry ($val) {
			$this->modelDataEntry = $val;
	}
	
	function getModelDataEntry(){
        return $this->modelDataEntry;
    }
	
	function setImgColumn ($val) {
			$this->imgColumn = $val;
	}
	
	function getImgColumn(){
        return $this->imgColumn;
    }
	
	function setTopicEntry ($val) {
			$this->topicEntry = $val;
	}
	
	function getTopicEntry(){
        return $this->topicEntry;
    }
	
	function setRequestId () {
			$result 	=	$GLOBALS['TYPO3_DB']->exec_SELECTquery($this->select, $this->table, $this->where, $this->groupBy ,$this->orderBy, $this->limit);
			while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($result)) {
					$val = $row[$this->select];					
				}
	
			$this->set($this->idEntry, $val);
	}
	
	function getImagesFromDB(){
			
			$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery($this->select, $this->table, $this->where, $this->groupBy ,$this->orderBy, $this->limit);
			while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($result)) {
					$val = $row[$this->imgColumn];					
			}
			if (!$val) {
					return array('No files found !!');
			} else { $this->set($this->modelDataEntry, $val); }
			
	}
	
	
	
	/**
	 * get the images out of a directory
	 *
	 * @param	int		$limitImages: How many images to return; default=0 list all
	 * @return	void
	 */
	function getImagesFromDirectory() {
			
		if ( $this->isDirectory == TRUE ) {
		
				$list = tx_div::makeInstance('tx_lib_object');
				$resultList = $this->getFiles( $this->imgDirectory );
				if ($resultList) {
						foreach ($resultList as $val) {
								$img['image'] = $val;
								$entry = new tx_lib_object($img);
								$list->append($entry);
						}
				}
				
				$this->set( $this->modelDataEntry, $list);	
		}
	} 
	
	
	
	
	/**
	 * Random view of an array and slice it afterwards, preserving the keys
	 *
	 * @param	array  $array: Array to modify
	 * @param	array  $offset: Where to start the slicing
	 * @param	array  $length: Length of the sliced array
	 * @return the randomized and sliced array
	 */
	function getSlicedRandomArray($array, $offset, $length) {
		// shuffle
		$new_arr = array();
		while ( count( $array ) > 0 ) {
			$val = array_rand( $array );
			$new_arr[$val] = $array[$val];
			unset( $array[$val] );
		}
		$result = $new_arr;
		
		// slice
		$result2 = array();
		$i = 0;
		if ($offset < 0)
			$offset = count( $result ) + $offset;
		if ($length > 0) {
			$endOffset = $offset + $length;
		}
		else if ($length < 0) {
			$endOffset = count( $result ) + $length;
		}
		else {
			$endOffset = count( $result );
		}
			
		// collect elements
		foreach ( $result as $key => $value ) {
			if ($i >= $offset && $i < $endOffset)
				$result2[$key] = $value;
			$i ++;
		}
		return $result2;
	}
	
	/**
	 * Gets all image files out of a directory 
	 *
	 * @param	string  $path: Path to the directory
	 * @return Array with the images
	 */
	function getFiles($path, $extra = "") {
		$files = Array();
		
		// check for needed slash at the end
		$length = strlen( $path );
		if ($path {$length - 1} != '/') {
			$path .= '/';
		}
		
		$imagetypes = $this->conf["filetypes"] ? explode(',', $this->conf["filetypes"]) : array(
			'jpg',
			'jpeg',
			'gif',
			'png'
		);
		
		if ($dir = dir( $path )) {	
			while ( false !== ($file = $dir->read()) ) {
				if ($file != '.' && $file != '..') {
					$ext = strtolower( substr( $file, strrpos( $file, '.' ) + 1 ) );
					if (in_array( $ext, $imagetypes )) {
						array_push( $files, $extra . $file );
					}
					else if ($this->conf["recursive"] == '1' && is_dir( $path . "/" . $file )) {
						$dirfiles = $this->getFiles( $path . "/" . $file, $extra . $file . "/" );
						if (is_array( $dirfiles )) {
							$files = array_merge( $files, $dirfiles );
						}
					}
				}
			}
			
			$dir->close();
			// sort files, thx to all
			sort( $files );
			
			return $files;
		}
	} # end getFiles
	
	
	
	
	
	
	
	/**
	 * Read data from an image or an associated text file.
	 *
	 * @param	string		$image: path to the image
	 * @return	array		title, description and author
	 */
	function readImageInfo($image) {
		$exifData = $this->readExif( $image );
		$iptcData = $this->readIptc( $image );
		$textData = $this->readTextComment( $image );
		
		$this->getPrioritizedContent( $exifData, 'author', 'description' );
		
		// Text data has precedence, then EXIF, then IPTC
		$title = $this->getPrioritizedContent( array($textData['title'], $exifData['title'], $iptcData['title'] ) );
		$description = $this->getPrioritizedContent( array($textData['description'], $exifData['description'], $iptcData['description'] ) );
		$author = $this->getPrioritizedContent( array($textData['author'], $exifData['author'], $iptcData['author'] ) );
		
		// Append author to the description
		if ($author && $this->conf['author']) {
			if ($description)
				$description .= ' ';
			$description .= str_replace( '|', $author, $this->pi_getLL('authorWrap') );
		}
		
		return array($title, $description );
	}
	
	/**
	 * Read EXIF data from an image.
	 *
	 * @param	string		$image: path to the image
	 * @return	array		title, description and author
	 */
	function readExif($image) {
		if (! t3lib_div::isAbsPath( $image )) {
			$image = t3lib_div::getFileAbsFileName( $image );
		}
		
		$data = array('title' => '', 'description' => '', 'author' => '' );
		
		if (! t3lib_div::inArray( get_loaded_extensions(), 'exif' ) || $this->conf['exif']!=1) { // If there is no EXIF Support at your installation
			return $data;
		}
		
		if (file_exists( $image ))
			$image_info = getimagesize( $image );
		if ($image_info[2] == 2) { // check for correct image-type
			//ini_set('exif.encode_unicode', 'UTF-8'); //Set encoding to Unicode, if not set in php.ini
			$exif_array = exif_read_data( $image, TRUE, FALSE ); // Load all EXIF informations from the original Pic in an Array
			$exif_array['Comments'] = htmlentities( str_replace( "\n", '<br />', $exif_array['Comments'] ) ); // Linebreak
			

			$data['title'] = $this->getPrioritizedContent( $exif_array, 'Title', 'Subject' );
			$data['description'] = $this->getPrioritizedContent( $exif_array, 'Comments', 'ImageDescription' );
			$data['author'] = $this->getPrioritizedContent( $exif_array, 'Author', 'Artist' );
		}
		
		return $data;
	}
	
	/**
	 * Read IPTC data from an image.
	 *
	 * @param	string		$image: path to the image
	 * @return	array		title, description and author
	 */
	function readIptc($image) {
		if (! t3lib_div::isAbsPath( $image )) {
			$image = t3lib_div::getFileAbsFileName( $image );
		}
		
		$data = array('title' => '', 'description' => '', 'author' => '' );

		if ($this->conf['iptc']!=1) { // If there is no EXIF Support at your installation
			return $data;
		}

		
		$info = NULL;
		getimagesize( $image, $info );
		if (is_array( $info )) {
			$iptc = iptcparse( $info["APP13"] );
			
			$data['title'] = $this->getPrioritizedContent( $iptc, '2#005', '2#105' ); // Title then Headline
			// Array is returned, use first item
			$data['title'] = $data['title'][0];
			
			$data['description'] = $iptc['2#120'][0];
			$data['author'] = $iptc['2#080'][0];
		}
		
		return $data;
	}
	
	/**
	 * Read image information from a associated text file.
	 * The text file should have the same name as the image but
	 * should end with '.txt'. Format
	 * <pre>
	 * {{title}}
	 * {{description}}
	 * {{author}}
	 * </pre>
	 *
	 * @param	string		$image: path to the image
	 * @return	array		title, description and author
	 */
	function readTextComment($image) {
		if (! t3lib_div::isAbsPath( $image )) {
			$image = t3lib_div::getFileAbsFileName( $image );
		}
		
		$data = array('title' => '', 'description' => '', 'author' => '' );
		
		$textfile = substr( $image, 0, strrpos( $image, '.' ) ) . '.txt';
		
		if (file_exists( $textfile )) {
			$lines = file( $textfile );
			
			if (count( $lines )) {
				$data['title'] = $lines[0];
				$data['description'] = $lines[1];
				$data['author'] = $lines[2];
			}
		}
		
		return $data;
	}
	
	/**
	 * Extract content from an array. First key that is
	 * associated to a content that is not empty will be
	 * returned. If keys are null then it returns the first
	 * non-empty element.
	 *
	 * @param	array		$array: array whose content should be extracted
	 * @param	mixed		key1
	 * @param	mixed		key2
	 * @param	mixed		...
	 * @return	string
	 */
	function getPrioritizedContent($array) {
		$keys = func_get_args();
		array_shift( $keys );
		
		if (! count( $keys ))
			$keys = array_keys( $array );
		
		foreach ( $keys as $key ) {
			if ($array[$key])
				return $array[$key];
		}
		
		return '';
	}
	
	
	function output ($val) {
		echo '<pre>';
			print_r($val);
		echo '</pre>';
	}
	
	
	
	
}	



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/bitsgallery/models/class.tx_bitsgallery_models_common.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/bitsgallery/models/class.tx_bitsgallery_models_common.php']);
}
?>
