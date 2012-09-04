Facebook Gallery
======================

This extension allows you to insert a Facebook Page Gallery on your pages and
entries. 

Required: A Facebook Page, JQuery 1.7, Fancybox

How to create your Facebook Page? <br />
<https://www.facebook.com/pages/create.php> <br />

How to create Albums? <br />
<https://www.facebook.com/help/?faq=174641285926169> <br />

How to get Your page ID? <br />
<http://rieglerova.net/how-to-get-a-facebook-fan-page-id/> <br />

IMPORTANT: You have to ensure ALLOW_EXTERNAL is set to TRUE in /pivotx/includes/timthumb.php (pivotx default is true) and add the following lines to the end of your /pivotx/includes/timthumb-config.php

 <br />
*********************************************
// Extension FaceGallery add fbcdn.net: <br />
$ALLOWED_SITES = array ( <br />
       'flickr.com', <br />
       'staticflickr.com', <br />
       'picasa.com', <br />
       'img.youtube.com', <br />
       'upload.wikimedia.org', <br />
       'photobucket.com', <br />
       'imgur.com', <br />
       'imageshack.us', <br />
       'tinypic.com', <br />
       'fbcdn.net', <br />
       ); <br />
*********************************************

Installation
======================
Unzip and copy to directory /pivotx/extensions/facegallery

Usage
======================
To use the gallery in its simplest form, just add [[facegallery]] to your template or directly in an entry or page.
This will insert the default gallery, with the default options selected.

    [[ facegallery col=3 max=9 ]]


If you want to use fancybox for popups, please add [[ fancybox_setup ]] in your template.
See <http://www.fancybox.net/> for more details.

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
- fancybox
   - set fancybox animation
   - Default: false
