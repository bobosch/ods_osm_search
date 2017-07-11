<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "ods_osm_search".
 *
 * Auto generated 09-07-2017 21:07
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array (
  'title' => 'OpenStreetMap radial search',
  'description' => 'Search tt_address records in a given radius (german "Umkreissuche").',
  'category' => 'fe',
  'author' => 'Robert Heel',
  'author_email' => 'typo3@bobosch.de',
  'state' => 'beta',
  'uploadfolder' => 0,
  'createDirs' => '',
  'clearCacheOnLoad' => 0,
  'author_company' => '',
  'version' => '2.0.0',
  'constraints' => 
  array (
    'depends' => 
    array (
      'typo3' => '6.2.0-8.99.99',
      'ods_osm' => '2.0.0-',
      'static_info_tables' => '',
    ),
    'conflicts' => 
    array (
    ),
    'suggests' => 
    array (
    ),
  ),
  'clearcacheonload' => 0,
);

