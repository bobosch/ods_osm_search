<?php
if (!defined ('TYPO3_MODE')) {
 	die ('Access denied.');
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPItoST43($_EXTKEY, 'pi1/class.tx_odsosmsearch_pi1.php', '_pi1', 'list_type', 0);

$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['ods_osm']['class.tx_odsosm_pi1.php'][]='EXT:ods_osm_search/class.tx_odsosmsearch_odsosm.php:tx_odsosmsearch_odsosm';
?>