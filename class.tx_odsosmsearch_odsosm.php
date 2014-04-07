<?php
require_once(t3lib_extMgm::extPath('ods_osm_search').'class.tx_odsosmsearch_div.php');

class tx_odsosmsearch_odsosm {
	function changeRecords(&$records,$record_ids,$obj){
		$standard_on_none_found = intval($GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_odsosmsearch_pi1.']['standard_on_none_found']);

		if($_POST['tx_odsosmsearch_pi1']){
			$row=$GLOBALS['TYPO3_DB']->exec_SELECTgetRows('pages','tt_content','tx_odsosmsearch_cid='.intval($obj->cObj->data['uid']));
			if($row){
				$addresses = tx_odsosmsearch_div::getRecords($_POST['tx_odsosmsearch_pi1'],$row[0]['pages'],$obj);
				if (!empty($addresses) || !$standard_on_none_found){
					// Change records
					$records['tt_address'] = $addresses;
				}
			}
		}
	}
}
?>
