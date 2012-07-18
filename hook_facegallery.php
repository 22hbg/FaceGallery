<?php
// - Extension: FaceBook Gallery 
// - Identifier: facegallery
// - Version: 0.1
// - Author: Matteo Tumidei
// - Email: mtumidei@gmail.com
// - Description: Make gallery of images from facebook album Derived from Dir Gallery extension.
// - Date: 2012-06-30           
global $facegallery_config;
global $path;
global $configdata;
$path   = $PIVOTX['paths']['extensions_url'];

/**
* @params margin string img css margins (default 1)
*Ê@params padding string img css padding (default 1)
* @params thumbw string width for thumbs (default to 70)
* @params thumbh string height for thumbs (default to 70)
* @params rounded boolean apply rounded corners to thumbs (default true)
* @params col integer count of column (default 3)
* @params max integer max count of pictures (default 10)
* @params reverse boolean display pics in reverse order (default false)
* @params fancybox boolean use thinkbox for popups (default false)
*/

$facegallery_config = array(
	'facegallery_page_id' => '',
	'facegallery_album' => '',
	'facegallery_album_id' => ''
	
);
	
	$configdata = $PIVOTX['config']->getConfigArray();
	foreach ($facegallery_config as $key => $value) {
		if (isset($configdata[$key])) {
			$$key = $configdata[$key];
		} //isset($configdata[$key])
		else {
			$$key = $value;
		}
	} //$facegallery_config as $key => $value
	

$this->addHook('configuration_add', 'facegallery', array(
	"facegalleryAdmin",
	"FaceGallery"
));


$PIVOTX['template']->register_block('facegallery', 'smarty_facegallery');


function smarty_facegallery($params, $text, &$smarty)
{
	global $PIVOTX;
	global $facegallery_config;
	global $path;
	global $configdata;	

	
	
	
	if (!isset($text)) {
		return "";
	} //!isset($text)
	
	$params = cleanParams($params);
	
	
	$vars    = $smarty->get_template_vars();
	$content = getDefault($vars['page'], $vars['entry']);
	
	$thumbw  = getDefault($params['thumbw'], '70');
	$thumbh  = getDefault($params['thumbh'], '70');
	$rounded = getDefault($params['rounded'], true);
	$margin  = getDefault($params['margin'], '1');
	$padding = getDefault($params['padding'], '1');
	$col     = getDefault($params['col'], '3');
	$max	 = getDefault($params['max'], '10');
	$reverse = getDefault($params['reverse'], false);
        $fancybox = getDefault($params['thickbox'], false);
	
	$output = '';
	
	$timthumb = $configdata['pivotx_url'] . 'includes/timthumb.php';
	
	$output .= "
    <script src=\"" . $path . "facegallery/src/jquery.fbpagephotos.js\"></script>
    <script language=\"javascript\">
(function() {


    $.FBPagePhotos({
        page_id: \"" . $configdata['facegallery_page_id'] . "\"
        ,albums_cb: function(albums, next) {
        	next(albums[" . $configdata['facegallery_album_sel'] . "]);        // you could let the user select here, for simplicity I am just choosing the first album
        }
        , photos_cb: function(photos) {
            var e = $('#photos-3')
                , list = $('<ul></ul>')
                , img = $('<img>');

            e.append(list, img);
 
            ii = 1;

            $.each(photos, function(i, photo) {
           ";
           
           if ($max) { 
           	   $output .= "if(i == " . $max . ") { return false; }"; 
           }
           $output .="
            	var w = " . $thumbw . ";
            	var h = " . $thumbh . ";
            	var thumb = \"" . $timthumb . "?src=\"+photo.source+\"&w=\"+w+\"&h=\"+h+\"&zc=1&q=90\"; 
            ";
            
            if (!$reverse) {
            	    	    $output .= "\nlist.append($('<a></a>').attr('id', 'link_'+i).attr('href', photo.source).attr('target', '_blank'));";
            	    	    $output .= "\n$('#link_'+i).prepend($('<img>').attr('id', 'photo_'+i).attr('src', thumb));";            	    
            } else {
            	            $output .= "\nlist.prepend($('<a></a>').attr('id', 'link_'+i).attr('href', photo.source).attr('target', '_blank'));";
            	    	    $output .= "\n$('#link_'+i).prepend($('<img>').attr('id', 'photo_'+i).attr('src', thumb));";          
            }
            
       	    if ($fancybox) { $output .= "\n$('a#link_'+i).addClass('gallery_image');"; }
       	    $output .="
            
            
                $('img#photo_'+i).css('width', w + 'px');
                $('img#photo_'+i).css('height', h + 'px');
                
                if(ii==" . $col . "){ list.append($('<br>')); ii=0; }
                ";
	
	
	
	if ($rounded == true) {
		$output .= "$('img#photo_'+i).css('border-radius', '6px');";
	} //$rounded
	
	if ($margin) {
		$output .= "\n$('img#photo_'+i).css('margin', '" . $margin . "px');";
	} //$margin
	
	if ($padding) {
		$output .= "\n$('img#photo_'+i).css('padding', '" . $padding . "px');";
	} //$padding
	
	$output .= "

             ii++;
            });
        }
    });
})();
</script>

        <div id=\"photos-3\"></div>";
	
	if (isset($params['popup'])) {
		$callback = $params['popup'] . "IncludeCallback";
		if (function_exists($callback)) {
			$PIVOTX['extensions']->addHook('after_parse', 'callback', $callback);
		} //function_exists($callback)
		else {
			debug("There is no function '$callback' - the popups won't work.");
		}
	} //isset($params['popup'])
	$output .= $params['footer'];
	
	return entifyAmpersand($output);
	
}


function facegalleryAdmin(&$form_html)
{
	global $form_titles, $facegallery_config, $PIVOTX, $path, $configdata;
	
	$form = $PIVOTX['extensions']->getAdminForm('facegallery');
	
	$form->add(array(
		'type' => 'text',
		'name' => 'facegallery_page_id',
		'label' => __('Page ID'),
		'value' => '',
		'error' => __('Insert a valid Page ID'),
		'text' => __('The ID of your facebook page. (es. 40796308305)'),
		'size' => 30,
		'isrequired' => 1,
		'validation' => 'string|minlen=1|maxlen=80'
	));
	
	$form->add(array(
		'type' => 'select',
		'name' => 'facegallery_album_sel',
		'label' => __('Album ID')
	));
	
	$form->add(array(
		'type' => 'hidden',
		'name' => 'facegallery_album_id'
	));
	
	$form->add(array(
		'type' => 'custom',
		'text' => '
		    <script src="' . $path . 'facegallery/src/jquery.fbpagephotos.js"></script>
    <script language="javascript">
(function() {

    $(\'#facegallery_album_sel\').FBPagePhotos({
        page_id: "' . $configdata['facegallery_page_id'] . '"
        , albums_cb: function(albums, next) {
            var select = $(\'#facegallery_album_sel\');

            $.each(albums, function(i, album) {
                select.append($(\'<option>\').attr(\'id\', album.id).attr(\'value\', i).text(album.name));
            });

            select.change(function() {
                var sel = document.getElementById(\'facegallery_album_sel\');
		aid = sel.options[sel.selectedIndex].getAttribute(\'id\');
		
                $(\'#facegallery_album_id\').val(aid);
            });

        }
        , error: function(msg) {
            console.error("FB Error:", msg.message);
            
            
        }
    });

    
})();
</script>
'
        ));
        $form->use_javascript(true);
        
        	$form->add(array(
		'type' => 'custom',
		'text' => '<span id="thanks" style="position: absolute; right: 200px;">
		<pre>
	         Based on:
	         
		 /*
		 * Facebook Page Photos (for jQuery)
		 * version: 1.0
		 * @requires jQuery v1.7 or later
		 * @homepage https://github.com/carlsverre/jquery-facebook-page-photos
		 *
		 * Licensed under the MIT:
		 *   http://www.opensource.org/licenses/mit-license.php
		 *
		 * Copyright 2011 Carl Sverre
		 */
		 
		 Thank You Carl!
		</pre><div id="#photos"></div>
		</span>'
	));
	
	$form_html['facegallery'] = $PIVOTX['extensions']->getAdminFormHtml($form, $facegallery_config);
	
}

?>
