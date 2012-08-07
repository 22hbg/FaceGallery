<?php
// - Extension: Facebook Gallery 
// - Identifier: facegallery
// - Version: 0.3
// - Author: Matteo Tumidei
// - Email: matteo@22hbg.com
// - Description: Make gallery of images from facebook album Derived from Dir Gallery extension.
// - Date: 2012-08-07           
global $facegallery_config;
global $path;
global $configdata;
$path   = $PIVOTX['paths']['extensions_url'];

/**
* @params margin string img css margins (default 1)
* @params thumbw string width for thumbs (default to 70)
* @params thumbh string height for thumbs (default to 70)
* @params rounded boolean apply rounded corners to thumbs (default true)
* @params col integer count of column (default 3)
* @params max integer max count of pictures (default 10)
* @params reverse boolean display pics in reverse order (default false)
* @params popup boolean use fancybox for popups (default false)
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
        $rounded = getDefault($params['rounded'], 0);
        $margin  = getDefault($params['margin'], '1');
        $col     = getDefault($params['col'], '3');
        $max     = getDefault($params['max'], '10');
        $reverse = getDefault($params['reverse'], 0);
        $popup = getDefault($params['popup'], 0);
        
        if ($thumbw <= 0) { $thumbw = 70; }
        if ($thumbh <= 0) { $thumbw = 70; }
        if ($margin <= 0) { $margin = 1; }
        if ($col <= 0) { $col = 3; }
        if ($max <= 0) { $max = -1; }
        
        $output = '';
        
        $timthumb = $configdata['pivotx_url'] . 'includes/timthumb.php';
      
        OutputSystem::instance()->addCode(
            'facegallery',
            OutputSystem::LOC_HEADEND,
            'script',
            array('src'=>$path.'facegallery/src/jquery.fbpagephotos.min.js','_priority'=>OutputSystem::PRI_NORMAL+20)
        ); 
        
        $output .= "
  (function() {

    jQuery.FBPagePhotos({
        page_id: \"" . $configdata['facegallery_page_id'] . "\"
        ,albums_cb: function(albums, next) {
                next(albums[" . $configdata['facegallery_album_sel'] . "]);        // you could let the user select here, for simplicity I am just choosing the first album
        }
        , photos_cb: function(photos) {
            var e = jQuery('#photos-3')
                , list = jQuery('<ul id=\"pic_list\"></ul><span id=\"error\"></span>')
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
                var thumb = \"" . $timthumb . "?src=\"+small+\"&w=\"+w+\"&h=\"+h+\"&zc=3&q=90\";                 
            ";
                    
$fancybox = false;
$activext = explode('|',$vars['config']['extensions_active']);
foreach ($activext as $exte) {
        if ($exte == "fancybox") { $fancybox = true; }
}

 
            if ($reverse == 0) {
                            $output .= "\nlist.append(jQuery('<a class=\"fancybox\" title=\"\" alt=\"\" rel=\"FaceGallery\"></a>').attr('id', 'link_'+i).attr('href', photo.source).attr('target', '_blank'));";
                            $output .= "\njQuery('#link_'+i).prepend(jQuery('<img>').attr('id', 'photo_'+i).attr('src', thumb));";           
            } else {
                            $output .= "\nlist.prepend(jQuery('<a class=\"fancybox\" rel=\"FaceGallery\"></a>').attr('id', 'link_'+i).attr('href', photo.source).attr('target', '_blank'));";
                            $output .= "\njQuery('#link_'+i).prepend(jQuery('<img>').attr('id', 'photo_'+i).attr('src', thumb));";          
            }
           




            $output .="
            
            
                jQuery('img#photo_'+i).css('width', w + 'px');
                jQuery('img#photo_'+i).css('height', h + 'px');
                if(ii==" . $col . "){ list.append(jQuery('<br>')); ii=0; }
                ";
        

        if ($popup) {
            if ($fancybox) {

$output .= "


if(jQuery().fancybox) { 
        jQuery(\"a.fancybox\").fancybox(); 
}
else { 
        jQuery('#error').html('You have to add [[ fancybox_setup ]] to your template.'); 
}";
            } else {
                        $output .= "\njQuery('a.fancybox').removeClass('fancybox').addClass('thickbox');";
            }
        }
        
        
        if ($rounded == 1) {
                $output .= "jQuery('img#photo_'+i).css('border-radius', '6px');";
        } //$rounded
        
        if ($margin) {
                $output .= "\njQuery('img#photo_'+i).css('margin', '" . $margin . "px');";
        } //$margin
        
        $output .= "

             ii++;
            });

        }

    });

})();


";


$html = "<div id=\"photos-3\"></div>";


        OutputSystem::instance()->addCode(
            'facegallery_custom',
            OutputSystem::LOC_HEADEND,
            'script',
            array('_priority'=>OutputSystem::PRI_NORMAL+20),
            $output
        ); 

        return entifyAmpersand($html);
        
}


function facegalleryAdmin(&$form_html)
{
        global $form_titles, $facegallery_config, $PIVOTX, $path, $configdata;
        
        $form = $PIVOTX['extensions']->getAdminForm('facegallery');
        
        $form->add(array(
                'type' => 'custom',
                'text' => '
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
                '
        ));
        
        $form->add(array(
                'type' => 'text',
                'name' => 'facegallery_page_id',
                'label' => __('Page ID/Name'),
                'value' => '',
                'error' => __('Insert a valid Page ID/Name'),
                'text' => __('The ID (or name) of your facebook page <br /> (e.g. 40796308305)'),
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
                'type' => 'textarea',
                'name' => 'errortext',
                'label' => 'Error:',
                'value' => '',
                'size' => 80
        ));
        
        $form->add(array(
                'type' => 'custom',
                'text' => '
                    <script src="' . $path . 'facegallery/src/jquery.fbpagephotos.js"></script>
    <script language="javascript">
    
    function album() {
              var facegallery_val = jQuery(\'#facegallery_page_id\').val();
    
          jQuery(\'select#facegallery_album_sel\').FBPagePhotos({
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
                jQuery(\'#errortext\').val("FB Error: " + msg.message);
                
                
            }
          });
    }
    
    function showload() {
            jQuery(\'img#waitimg\').css(\'visibility\',\'visible\');
    }

    function hideload() {
            jQuery(\'img#waitimg\').css(\'visibility\',\'hidden\');
    }

    function clearalbum() {
            jQuery(\'select#facegallery_album_sel\').children().remove().end();
            jQuery(\'#errortext\').val("");
            album();
    }
    
    (function() {

          jQuery(\'#facegallery_page_id\').val(\'' . $configdata['facegallery_page_id'] . '\');
          clearalbum();
          jQuery(\'#facegallery_page_id\').after(\'<div style="display: inline; float: right; margin-right: 250px;" class="buttons"><button type="button" onclick="showload();clearalbum();hideload();" id="update_album">OK</button></div>\');
          jQuery(\'select#facegallery_album_sel\').after(\'<img style="display: block; z-index: 999" id="waitimg" src="' . $path . '/facegallery/wait.gif" />\');
          hideload();    
})();

    
</script>
<style>
.formclass textarea#errortext{
  color: red !important;
  background: white !important;
}
</style>
'
        ));
        

                
        $form->use_javascript(true);
        $form_html['facegallery'] = $PIVOTX['extensions']->getAdminFormHtml($form, $facegallery_config);
        
}

?>
