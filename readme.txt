-------------------------
Listzen Theme
Body Font: Inter
Title Font: Inter
Homepage: 7
Images Uses from: Freepik

-------------------------

Blog Style:
===========
Blog Colum = ?column=default //col-lg-12, col-lg-6, col-lg-4, col-lg-3


Global Layout:
==============
layout = ?layout=full-width //left-sidebar, right-sidebar






==============================
Listing Archive
=============================

Sidebar Collapsible
-------------------
?collapsible=1

style=default// category-thumb

Listing map
=============
?map=1
?map_pos=top //side-r, side-l, bottom

Modify listing cols: only desktop view
========================
?cols=4

sidebar position
==================
sidebar= //Not implemented

Enable/Diable Banner
================
?banner=1

Listing Archive container width
=============
?con_width=1600px

This is an example URL with a query string that you can modify and use on your site to preview various layouts.
http://listzen.local/listings/?banner=0&map=1&collapsible=0&cols=4&con_width=1600px&map_pos=top





=========================
Listing Details
=========================

style=default / layout-2, layout-3

gallery_style=gallery //gallery, gallery-full, gallery-split
gallery_height=500px
thumbs_gallery=1
slider_cols=4
separate_video=1 //0


--------------------
Listing Form: Custom fields
--------------------
rtcl_faqs
rtcl_amenities


-------------------------
Export Functionality Guide
-------------------------

To enable Export functionality, first you need to define RT_EXPORT_ENABLE = true in the wp-config.php file

Exported File Location: All exported files are saved to: listzen-core/sample-data/

Manual Export
-------------
You must manually export the following files and place them in the sample-data directory of the listzen-core plugin:
	1.	All Contents
	2.	RTCL Settings
	3.	RTCL Forms


Automatic Export
----------------
The following data can be exported automatically by passing query strings in the URL:
	•	Export Everything:                   /?listzen_export=yes
	•	(optional) Export Only Users:        /?listzen_user=yes
	•	(optional) Export Only Ajax Filters: /?ajax_filter_export=yes

Important Notes
	•	After running any export, you must replace all exported files in your local project.
	•	Ensure that the sample-data directory is writable by the server.


