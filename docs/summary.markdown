Facebook Gallery
======================

This extension allows you to insert a Facebook Page Gallery on your pages and
entries. 

Required: JQuery 1.7

IMPORTANT: You have to ensure ALLOW_EXTERNAL in /pivotx/includes/timthumb.php and
           add 'fbcdn.net' in $ALLOWED_SITES.

Usage
-----

To use the gallery in its simplest form, just add `[[facegallery]]` in the
template of your entrypage, in a weblog entry, or on a page. This will insert
the default gallery, with the default options selected.

Several parameters are available to customize the button. For example, to insert
a simpler button, use:

    [[ facegallery col=3 max=10 ]]

The available other parameters are:

PARAMETER
- thumbw
        set your thumbnails width (string)                      
        Default: 70
- thumbh  
        set your thumbnails height (string)
        Default: 70
- rounded
        apply 6px border radius to thumbnail by css (boolean)
        Default: true
- reverse
        display photos in reverse order (boolean)
        Default: false
- max
        set max number of photos to display, -1 = no limit (integer)
        Default: -1
- col
        set the number of column
        Default: 3
- margin
        set margin in pixel for each photo by css
        Default: 1
- padding
        set padding in pixel for each photo by css
        Default: 1


