<?php
require_once(t3lib_extMgm::extPath('ods_osm').'class.tx_odsosm_div.php');

class tx_odsosmsearch_div {
	function getRecords($input,$pid,$obj){
		if($GLOBALS['ods_osm_search']) return $GLOBALS['ods_osm_search'];

		if ($input['radius']) {
			$radius = intval($input['radius']);
		}
		$fuzzy_loops = intval($GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_odsosmsearch_pi1.']['radius.']['fuzzy_loops']);
		$conf_radius_value = intval($GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_odsosmsearch_pi1.']['radius']);
		$conf_radius_select = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_odsosmsearch_pi1.']['radius.']['select'];
		if (!empty($conf_radius_select)) {
			$radius_array = array_flip(t3lib_div::intExplode(',', $conf_radius_select, true));
		}
		$radius_array[$conf_radius_value] = 1;
		$radius_array[$radius] = 1;
		ksort($radius_array);
		$radius_array = array_keys($radius_array);

		$addresses = array();
		$k = array_search($radius, $radius_array);
		while ($radius_array[$k++]) {
			$sql=tx_odsosmsearch_div::getSql($input);
			if($sql){
				$sql['SELECT'][]='tt_address.*';
				$sql['FROM'][]='tt_address';
				$sql['WHERE'][]='tx_odsosm_lon != 0'; // Use only addresses with coordinates
				if($pid) $sql['WHERE'][]='pid IN ('.$pid.')';
				$addresses=$GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
					implode(',',$sql['SELECT']),
					implode(',',$sql['FROM']),
					implode(' AND ',$sql['WHERE']).$obj->cObj->enableFields('tt_address').' HAVING '.implode(' AND ',$sql['HAVING']),
					'',
					implode(',',$sql['ORDER'])
				);
			}
			if (!empty($addresses) || !$fuzzy_loops--)
				break;

			$radius = $radius_array[$k];
			$input['radius'] = $radius;
			$obj->conf['radius_by_fuzzy_loops'] = $radius;
		}

		$auto_zoom = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_odsosmsearch_pi1.']['radius.']['auto_zoom'];
		if (!empty($addresses) && $auto_zoom && !empty($radius_array)) {
			$zoom_array = t3lib_div::intExplode(',', $auto_zoom, true);
			$r_count = count($radius_array);
			$z_count = count($zoom_array);
			$size = ($r_count > $z_count) ? $z_count : $r_count;
			$radius_array = array_slice($radius_array, 0, $size);
			$zoom_array = array_slice($zoom_array, 0, $size);
			$radius2zoom = array_combine($radius_array, $zoom_array);
			if ($radius2zoom[$radius])
				$obj->config['zoom'] = $radius2zoom[$radius];
		}
		$search_limit = intval($input['limit']);
		$search_limit = $search_limit ? $search_limit : intval($GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_odsosmsearch_pi1.']['limit']);
		if ($search_limit > 0) {
			array_splice($addresses, $search_limit);
		}
		$GLOBALS['ods_osm_search']=$addresses;
		return $addresses;
	}

	function getSql($input){
		// Erdradius (geozentrischer Mittelwert) in km
		$earth_radius=6368;

		switch(strtolower($GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_odsosmsearch_pi1.']['unit'])){
			case 'mi':
			case 'miles':
				$earth_radius*=1/1.609344; # 1 mile = 1.609344 km
				break;
		}

		// der Umkreis
		if($input['radius']) $radius=intval($input['radius']);
		if($radius<=0 or $radius>$earth_radius) $radius=$GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_odsosmsearch_pi1.']['radius'];

		$address['zip']=trim($input['zip']);
		if($input['country']) $address['country']=$input['country'];
		$ll=tx_odsosm_div::updateAddress($address);
		if($ll){
			// Umrechnung von GRAD IN RAD
			$lon=$address['lon'] / 180 * M_PI;
			$lat=$address['lat'] / 180 * M_PI;

			$sql=array(
				'SELECT'=>array(
'('.$earth_radius.' * SQRT(2*(1-cos(RADIANS(tx_odsosm_lat)) * 
cos('.$lat.') * (sin(RADIANS(tx_odsosm_lon)) *
sin('.$lon.') + cos(RADIANS(tx_odsosm_lon)) * 
cos('.$lon.')) - sin(RADIANS(tx_odsosm_lat)) *
sin('.$lat.')))) AS distance'
				),
				'HAVING'=>array(
					'(distance <= '.$radius.' OR distance is NULL)'
				),
				'ORDER'=>array(
					'distance'
				),
			);
			
			// Hook to change sql
			if(is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['ods_osm_search']['class.tx_odsosmsearch_div.php'])){
				foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['ods_osm_search']['class.tx_odsosmsearch_div.php'] as $classRef){
					$hook=&t3lib_div::getUserObj($classRef);
					$hook->changeSql($sql,$input,$this);
				}
			}
			
			return $sql;
		}
	}
}
?>
