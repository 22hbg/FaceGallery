Facebook Gallery
======================

This extension allows you to insert a Facebook Gallery on your pages and
entries. 


Usage
-----

To use the gallery in its simplest form, just add `[[facegallery]]` in the
template of your entrypage, in a weblog entry, or on a page. This will insert
the default gallery, with the default options selected.

Several parameters are available to customize the button. For example, to insert
a simpler button, use:

    [[ facegallery col=3 max=10 ]] [[ /facegallery ]]

The available other parameters are:

PARAMETER | Description  						| Default
---------------------------------------------------------------------------------
- thumbw  | set your thumbnails width (string)				| 70
- thumbh  | set your thumbnails height (string)				| 70
- rounded | apply 6px border radius to thumbnail by css (boolean) 	| true
- reverse | display photos in reverse order (boolean)			| false
- max	  | set max number of photos to display, -1 = no limit (integer)| -1
- col	  | set the number of column					| 3
- margin  | set margin in pixel for each photo by css			| 1
- padding | set padding in pixel for each photo by css			| 1

---------------------------------------------------------------------------------



