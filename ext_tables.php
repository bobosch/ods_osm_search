<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$tempColumns = array (
	'tx_odsosmsearch_cid' => array (        
		'exclude' => 1,        
		'label' => 'LLL:EXT:ods_osm_search/locallang_db.xml:tt_content.tx_odsosmsearch_cid',        
		'config' => array (
			'type' => 'select', 
			'renderType' => 'selectSingle',
			'items' => array (
				array('',0),
			),
			'foreign_table' => 'tt_content',    
			'foreign_table_where' => 'AND tt_content.pid=###CURRENT_PID### AND list_type="ods_osm_pi1" ORDER BY tt_content.uid',    
			'size' => 1,    
			'minitems' => 0,
			'maxitems' => 1,
		)
	),
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
	'tt_content',
	$tempColumns
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
	'tt_content',
	'tx_odsosmsearch_cid'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPlugin(array(
	'LLL:EXT:ods_osm_search/locallang_db.xml:tt_content.list_type_pi1',
	$_EXTKEY . '_pi1',
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'ext_icon.gif'
),'list_type');
?>