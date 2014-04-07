<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "ods_osm_search".
 *
 * Auto generated 09-07-2013 21:16
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Openstreetmap radial search',
	'description' => 'Search tt_address records in a given radius (german "Umkreissuche").',
	'category' => 'fe',
	'author' => 'Robert Heel',
	'author_email' => 'typo3@bobosch.de',
	'shy' => '',
	'dependencies' => 'ods_osm',
	'conflicts' => '',
	'priority' => '',
	'module' => '',
	'state' => 'beta',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => 'tt_content',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'author_company' => '',
	'version' => '1.1.1',
	'constraints' => array(
		'depends' => array(
			'ods_osm' => '1.3.0-',
			'typo3' => '4.5.0-9.9.9',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:13:{s:9:"ChangeLog";s:4:"8b8e";s:29:"class.tx_odsosmsearch_div.php";s:4:"03e7";s:32:"class.tx_odsosmsearch_odsosm.php";s:4:"067a";s:12:"ext_icon.gif";s:4:"0e70";s:17:"ext_localconf.php";s:4:"72dd";s:14:"ext_tables.php";s:4:"5048";s:14:"ext_tables.sql";s:4:"eeed";s:24:"ext_typoscript_setup.txt";s:4:"5975";s:16:"locallang_db.xml";s:4:"a681";s:10:"README.txt";s:4:"ee2d";s:33:"pi1/class.tx_odsosmsearch_pi1.php";s:4:"50fa";s:17:"pi1/locallang.xml";s:4:"d413";s:23:"pi1/ods_osm_search.html";s:4:"be7e";}',
	'suggests' => array(
	),
);

?>