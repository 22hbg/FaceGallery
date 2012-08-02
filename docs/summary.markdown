Facebook Gallery
======================

This extension allows you to insert a Facebook Page Gallery on your pages and
entries. 

Required: Facebook Page, JQuery 1.7

How to create your Facebook Page? 
https://www.facebook.com/pages/create.php

How to create Albums?
https://www.facebook.com/help/?faq=174641285926169

How to get Your page ID?
http://rieglerova.net/how-to-get-a-facebook-fan-page-id/

IMPORTANT: You have to ensure ALLOW_EXTERNAL is set to TRUE in /pivotx/includes/timthumb.php (pivotx default is true) and
           add the following lines to the end of your /pivotx/includes/timthumb-config.php


*********************************************
// Extension FaceGallery add fbcdn.net:
$ALLOWED_SITES = array (
'flickr.com',
'staticflickr.com',
'picasa.com',
'img.youtube.com',
'upload.wikimedia.org',
'photobucket.com',
'imgur.com',
'imageshack.us',
'tinypic.com',
'fbcdn.net',
);
*********************************************


Installation:
======================
Unzip and copy to directory /pivotx/extensions/facegallery

Usage
======================
To use the gallery in its simplest form, just add [[facegallery]] to your template or directly in an entry or page.
This will insert the default gallery, with the default options selected.

    [[ facegallery col=3 max=10 ]]


Parameters
======================
The available parameters are:

- thumbw
  - set your thumbnails width (string)                      
  - Default: 70
- thumbh  
  - set your thumbnails height (string)
  - Default: 70
- rounded
   - apply 6px border radius to thumbnail by css (boolean)
   - Default: true
- reverse
   - display photos in reverse order (boolean)
   - Default: false
- max
   - set max number of photos to display, -1 = no limit (integer)
   - Default: -1
- col
   - set the number of column
   - Default: 3
- margin
   - set margin in pixels for each photo by css
   - Default: 1
- padding
   - set padding in pixels for each photo by css
   - Default: 1


