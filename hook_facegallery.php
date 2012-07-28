<?php
// - Extension: FaceBook Gallery 
// - Identifier: facegallery
// - Version: 0.2
// - Author: Matteo Tumidei
// - Email: matteo@22hbgcom
// - Description: Make gallery of images from facebook album Derived from Dir Gallery extension.
// - Date: 2012-07-27           
global $facegallery_config;
global $path;
global $configdata;
$path   = $PIVOTX['paths']['extensions_url'];

/**
* @params margin string img css margins (default 1)
* @params padding string img css padding (default 1)
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
        


//$PIVOTX['template']->register_block('facegallery', 'smarty_facegallery');
$PIVOTX['template']->register_function('facegallery', 'smarty_facegallery');

$this->addHook('configuration_add', 'facegallery', array(
        "facegalleryAdmin",
        "FaceGallery"
));




function smarty_facegallery($params, &$smarty)
{
        global $PIVOTX;
        global $facegallery_config;
        global $path;
        global $configdata;     

        
        $params = cleanParams($params);
        
        
        $vars    = $smarty->get_template_vars();
        $content = getDefault($vars['page'], $vars['entry']);
        
        $thumbw  = getDefault($params['thumbw'], '70');
        $thumbh  = getDefault($params['thumbh'], '70');
        $rounded = getDefault($params['rounded'], true);
        $margin  = getDefault($params['margin'], '1');
        $padding = getDefault($params['padding'], '1');
        $col     = getDefault($params['col'], '3');
        $max     = getDefault($params['max'], '10');
        $reverse = getDefault($params['reverse'], false);
        $fancybox = getDefault($params['thickbox'], false);
        
        $output = '';
        
        $timthumb = $configdata['pivotx_url'] . 'includes/timthumb.php';
        
        
        $output .= "
    <script src=\"" . $path . "facegallery/src/jquery.fbpagephotos.js\"></script>
    <script language=\"javascript\">
(function() {


    jQuery.FBPagePhotos({
        page_id: \"" . $configdata['facegallery_page_id'] . "\"
        ,albums_cb: function(albums, next) {
                next(albums[" . $configdata['facegallery_album_sel'] . "]);        // you could let the user select here, for simplicity I am just choosing the first album
        }
        , photos_cb: function(photos) {
            var e = jQuery('#photos-3')
                , list = jQuery('<ul></ul>')
                , img = jQuery('<img>');

            e.append(list, img);
 
            ii = 1;

            jQuery.each(photos, function(i, photo) {
           ";
           
           if ($max) { 
                   $output .= "if(i == " . $max . ") { return false; }"; 
           }
           $output .="
                var w = " . $thumbw . ";
                var h = " . $thumbh . ";
                var small = photo.source.slice(0,photo.source.length-5) + 's.jpg';
                var thumb = \"" . $timthumb . "?src=\"+small+\"&w=\"+w+\"&h=\"+h+\"&zc=1&q=90\";                 
            ";
            
            if (!$reverse) {
                            $output .= "\nlist.append(jQuery('<a></a>').attr('id', 'link_'+i).attr('href', photo.source).attr('target', '_blank'));";
                            $output .= "\njQuery('#link_'+i).prepend(jQuery('<img>').attr('id', 'photo_'+i).attr('src', thumb));";                    
            } else {
                            $output .= "\nlist.prepend(jQuery('<a></a>').attr('id', 'link_'+i).attr('href', photo.source).attr('target', '_blank'));";
                            $output .= "\njQuery('#link_'+i).prepend(jQuery('<img>').attr('id', 'photo_'+i).attr('src', thumb));";          
            }
            
            if ($fancybox) { $output .= "\njQuery('a#link_'+i).addClass('gallery_image');"; }
            $output .="
            
            
                jQuery('img#photo_'+i).css('width', w + 'px');
                jQuery('img#photo_'+i).css('height', h + 'px');
                
                if(ii==" . $col . "){ list.append(jQuery('<br>')); ii=0; }
                ";
        
        
        
        if ($rounded == true) {
                $output .= "jQuery('img#photo_'+i).css('border-radius', '6px');";
        } //$rounded
        
        if ($margin) {
                $output .= "\njQuery('img#photo_'+i).css('margin', '" . $margin . "px');";
        } //$margin
        
        if ($padding) {
                $output .= "\njQuery('img#photo_'+i).css('padding', '" . $padding . "px');";
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
                'label' => __('Page ID/Name'),
                'value' => '',
                'error' => __('Insert a valid Page ID/Name'),
                'text' => __('The ID (or name) of your facebook page. (e.g. 40796308305)'),
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
    
    function album() {
              var facegallery_val = jQuery(\'#facegallery_page_id\').val();
    
          jQuery(\'#facegallery_album_sel\').FBPagePhotos({
              page_id: facegallery_val
            , albums_cb: function(albums, next) {
                var select = jQuery(\'#facegallery_album_sel\');
      
                jQuery.each(albums, function(i, album) {
                  select.append(jQuery(\'<option>\').attr(\'id\', album.id).attr(\'value\', i).text(album.name));
                });
      
                select.change(function() {
                  var sel = document.getElementById(\'facegallery_album_sel\');
                  aid = sel.options[sel.selectedIndex].getAttribute(\'id\');
                  
                  jQuery(\'#facegallery_album_id\').val(aid);
                });
      
            }
            , error: function(msg) {
                console.error("FB Error:", msg.message);
                
                
            }
          });
    }
    
    function clearalbum() {
            jQuery(\'select#facegallery_album_sel\').children().remove().end();
            album();
    }
    
    (function() {

              jQuery(\'#facegallery_page_id\').val(\'' . $configdata['facegallery_page_id'] . '\');

          clearalbum();
          jQuery(\'#facegallery_page_id\').after(\'<div style="display: inline; float: right; margin-right: 250px;" class="buttons"><button type="button" onclick="clearalbum();" id="update_album">OK</button></div>\');
    
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
