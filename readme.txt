FaceGallery for PivotX

Version: 0.2
Date: 2012-07-27
Requirements: PivotX 2.3.3, a Facebook Public Page 

Author: Matteo Tumidei
E-mail: matteo@22hbg.com

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

Description:
Fetch Facebook Page albums. 
Derived from DirGallery extension.
Primary use for automatic gallery for entry or page.

Installation:
Unzip and copy to directory /pivotx/extensions/facegallery


ChangeLog:

0.2 - 2012-07-27
        Changed Block to Function
        No need to put closing tag

0.1 - 2012-06-30
        First Working Release


