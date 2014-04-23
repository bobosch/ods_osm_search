<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Robert Heel <rheel@1drop.de>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 * Hint: use extdeveval to insert/update function index above.
 */

require_once(PATH_tslib.'class.tslib_pibase.php');
require_once(t3lib_extMgm::extPath('ods_osm_search').'class.tx_odsosmsearch_div.php');
require_once(t3lib_extMgm::extPath('static_info_tables').'pi1/class.tx_staticinfotables_pi1.php');


/**
 * Plugin 'Openstreetmap search' for the 'ods_osm_search' extension.
 *
 * @author	Robert Heel <rheel@1drop.de>
 * @package	TYPO3
 * @subpackage	tx_odsosmsearch
 */
class tx_odsosmsearch_pi1 extends tslib_pibase {
	public $prefixId      = 'tx_odsosmsearch_pi1';		// Same as class name
	public $scriptRelPath = 'pi1/class.tx_odsosmsearch_pi1.php';	// Path to this script relative to the extension dir.
	public $extKey        = 'ods_osm_search';	// The extension key.
	
	public $staticInfo;

	function init($conf){
		$this->conf = $conf;
		$this->pi_setPiVarDefaults();
		$this->pi_loadLL();
		$this->pi_USER_INT_obj = 1;	// Configuring so caching is not expected. This value means that no cHash params are ever set. We do this, because it's a USER_INT object!

		$this->staticInfo = &t3lib_div::getUserObj('&tx_staticinfotables_pi1');
// 		$this->staticInfo = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('SJBR\\StaticInfoTables\\PiBaseApi');
		if ($this->staticInfo->needsInit()) {
			$this->staticInfo->init();
		}
	}

	/**
	 * The main method of the PlugIn
	 *
	 * @param	string		$content: The PlugIn content
	 * @param	array		$conf: The PlugIn configuration
	 * @return	The content that is displayed on the website
	 */
	function main($content, $conf) {
		$this->init($conf);

		/* ==================================================
			Template
		================================================== */
		$templateCode=$this->cObj->fileResource($this->conf['template']);
		$this->template['total']=$this->cObj->getSubpart($templateCode,'###ODSOSMSEARCH###');

		$subpart=array(
			'###INPUT###'=>$this->getInput(),
			'###LIST###'=>$this->getList(),
		);

		$content=$this->cObj->substituteMarkerArrayCached($this->template['total'],array(),$subpart);
	
		return $this->pi_wrapInBaseClass($content);
	}

	function getInput(){
		$template['input']=$this->cObj->getSubpart($this->template['total'],'###INPUT###');

		$conf_radius_value = intval($this->conf['radius']);
		$radius_value = intval($this->piVars['radius']);
		$radius_value = $radius_value ? $radius_value : $conf_radius_value;

		$conf_radius_select = $this->conf['radius.']['select'];
		if (!empty($conf_radius_select)) {
			$radius_array = array_flip(t3lib_div::intExplode(',', $conf_radius_select, true));
		}
		$radius_array[$conf_radius_value] = 1;
		$radius_array[$radius_value] = 1;
		ksort($radius_array);
		$radius_array = array_keys($radius_array);
		foreach ($radius_array as $radius) {
			$radius_select.='<option value="'.$radius.'"'.($radius==$radius_value ? ' selected="selected"' : '').'>'.$radius.'</option>';
		}

		$limit_value = intval($this->piVars['limit']);
		$limit_value = $limit_value ? $limit_value : intval($this->conf['limit']);

		$country_options='';
		if($this->conf['country']){
			foreach(explode(',',$this->conf['country']) as $country_code){
 				$country_options.='<option value="'.$country_code.'"'.($country_code==$this->piVars['country'] ? ' selected="selected"' : '').'>'.$this->staticInfo->getStaticInfoName('COUNTRIES', $country_code).'</option>';
			}
		}

		$marker=array(
			'###FORM_ACTION###'=>$this->pi_getPageLink($this->conf['pid'] ? $this->conf['pid'] : $GLOBALS['TSFE']->id),
			'###ZIP_NAME###'=>$this->prefixId.'[zip]',
			'###ZIP_VALUE###'=>$this->piVars['zip'],
			'###COUNTRY_LABEL###'=>$this->pi_getLL('country'),
			'###COUNTRY_NAME###'=>$this->prefixId.'[country]',
			'###COUNTRY_OPTIONS###'=>$country_options,
			'###RADIUS_NAME###'=>$this->prefixId.'[radius]',
			'###RADIUS_SELECT###'=>$radius_select,
			'###LIMIT_NAME###'=>$this->prefixId.'[limit]',
			'###LIMIT_VALUE###'=>$limit_value,
			'###SUBMIT_VALUE###'=>$this->pi_getLL('search'),
			'###LABEL_ZIP###' => $this->pi_getLL('label_zip'),
			'###LABEL_RADIUS###' => $this->pi_getLL('label_radius'),
			'###RADIUS_UNIT###' => $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_odsosmsearch_pi1.']['unit'],
			'###LABEL_LIMIT###' => $this->pi_getLL('label_limit'),
		);
		return $this->cObj->substituteMarkerArrayCached($template['input'],$marker);
	}

	function getList(){
		if($this->piVars['zip']){
			$addresses=tx_odsosmsearch_div::getRecords($this->piVars,$this->cObj->data['pages'],$this);
			$template['list']=$this->cObj->getSubpart($this->template['total'],'###LIST###');
			$template['list_item']=$this->cObj->getSubpart($template['list'],'###LIST_ITEM###');
			$local_cObj=t3lib_div::makeInstance('tslib_cObj');
			$subpart['###LIST_ITEM###']='';
			if(is_array($addresses)){
				foreach($addresses as $address){
					$distance=round($address['distance'],$this->conf['distance.']['round']);
					$address['distance']=number_format($distance, intval($this->staticInfo->currencyInfo['cu_decimal_digits']), $this->staticInfo->currencyInfo['cu_decimal_point'], (($this->staticInfo->currencyInfo['cu_thousands_point'])?$this->staticInfo->currencyInfo['cu_thousands_point']:chr(32)));
					$conf=$this->conf['list.']['tt_address.'];
					$local_cObj->data=$address;
					$marker=array(
						'###RADIUS_UNIT###' => $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_odsosmsearch_pi1.']['unit'],
					);
					foreach($conf as $key=>$value){
						if(substr($key,-1)!='.') {
							$marker['###'.strtoupper($key).'###']=$local_cObj->cObjGetSingle($conf[$key],$conf[$key.'.']);
						}
					}
					$subpart['###LIST_ITEM###'].=$this->cObj->substituteMarkerArrayCached($template['list_item'],$marker);
				}
			}
			if(empty($addresses)){
				$result_info = sprintf($this->pi_getLL('result_info_empty'));
			}else{
				$radius_by_fuzzy_loops = intval($this->conf['radius_by_fuzzy_loops']);
				if (!$radius_by_fuzzy_loops)
					$result_info = sprintf($this->pi_getLL('result_info'), count($addresses));
				else
					$result_info = sprintf($this->pi_getLL('result_info_fuzzy'), count($addresses), $radius_by_fuzzy_loops);
			}
			$marker['###RESULT_INFO###'] = nl2br($result_info);
			return $this->cObj->substituteMarkerArrayCached($template['list'],$marker,$subpart);
		}
	}
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/ods_osm_search/pi1/class.tx_odsosmsearch_pi1.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/ods_osm_search/pi1/class.tx_odsosmsearch_pi1.php']);
}

?>