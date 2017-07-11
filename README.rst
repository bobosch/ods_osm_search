==================================
 EXT: OpenStreetMap radial search
==================================
:Extension Key: ods_osm_search
:Description: Search tt_address records in a given radius (german "Umkreissuche").
:Author: Robert Heel <typo3@bobosch.de>
:Copyright: 2010-2017


Introduction
============

What does it do?
----------------
Adds a radial search to ods_osm


Users manual
============

Frontend Plugin
---------------
Insert the Plugin on the same page as the map. Choose in the "Extended" tab of this plugin the "ods_osm" element.

+----------------------------+------------------------------------------------+
| Plugin option (TS option)  |                  Description                   |
+============================+================================================+
| Record Storage Page (pid)  | Search in this page for records.               |
+----------------------------+------------------------------------------------+
| Show search result on map  | Map to show search result.                     |
+----------------------------+------------------------------------------------+


Configuration
=============

Reference
---------

.. |sonf| replace:: standard_on_none_found
.. |rfl| replace:: radius.fuzzy_loops

+-----------------+-----------+-------------------------------------+---------+
|     Property    | Data type |             Description             | Default |
+-----------------+-----------+-------------------------------------+---------+
| country         | string    | Comma separated list of country     |         |
|                 | list      | codes (e.g. DE)                     |         |
+-----------------+-----------+-------------------------------------+---------+
| distance.round  | integer   | Round distance to this precission   | 2       |
+-----------------+-----------+-------------------------------------+---------+
| limit           | integer   | Show top x search results           | 5       |
+-----------------+-----------+-------------------------------------+---------+
| list            | TS Object | A search result entry               | see l   |
+-----------------+-----------+-------------------------------------+---------+
| pid             | integer   | Search in this page for records     |         |
+-----------------+-----------+-------------------------------------+---------+
| radius          | integer   | Default search radius               | 25      |
+-----------------+-----------+-------------------------------------+---------+
| radius.auto_zoom| integer   | Comma separated list of zoom option | 13,10,  |
|                 | list      | Zoom settings for radius selection  | 9,8     |
+-----------------+-----------+-------------------------------------+---------+
| |rfl|           | integer   | Use next greater radius x times     | 2       |
+-----------------+-----------+-------------------------------------+---------+
| radius.select   | integer   | Comma separated list of distance    | 10,25,  |
|                 | list      | Possible distances for dropdown     | 50,100  |
+-----------------+-----------+-------------------------------------+---------+
| |sonf|          | boolean   | Show standard map if no records     |         |
|                 |           | found                               |         |
+-----------------+-----------+-------------------------------------+---------+
| template        | string    | Path to template file               |         |
+-----------------+-----------+-------------------------------------+---------+
| unit            | string    | Distance unit                       | km      |
+-----------------+-----------+-------------------------------------+---------+

l::
	tt_address {
		distance = TEXT
		distance.field = distance

		first_name = TEXT
		first_name.field = first_name

		image = IMAGE
		image.file.import = uploads/pics/
		image.file.import.field = image

		last_name = TEXT
		last_name.field = last_name
	}

Tutorial
========

1. Insert the Plugin on the same page as the map.
2. Choose in the "Extended" tab of this plugin the "ods_osm" element.
