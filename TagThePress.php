<?php
/*
Plugin Name: TagThePress
Plugin URI: http://www.vividvisions.com/projects/tagthepress/
Description: WordPress plugin for tag suggestions using <a href="http://tagthe.net/">tagthe.net</a>
Author: VividVisions.com
Version: 3.0
License: GPL
Author URI: http://www.vividvisions.com/
*/
?>
<?php
/*
Copyright (C) 2008 VividVisions.com

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

function tagThePress_scripts () {
   $uri = $_SERVER['REQUEST_URI'];
   $pathinfo = pathinfo($uri);
   $ajax_path = plugins_url('TagThePressAjax.php' , __FILE__ );
   $logo_path = plugins_url('tagthenet_logo.png' , __FILE__ );
   $version = get_bloginfo('version');
   
   if (version_compare($version , '2.8', '<')) {
      $html_add_to = '#tagsdiv .inside';
      $newtags = '#newtag';
      $taginput = '#tags-input';

   } else if (version_compare($version , '2.8', '>=') &&
              version_compare($version , '3.2', '<')) {
      $html_add_to = '.tagsdiv .ajaxtag';
      $newtags = '#new-tag-post_tag';
      $taginput = '#tax-input[post_tag]';

   } else { // >= 3.2
      $html_add_to = '.tagsdiv .ajaxtag';
      $newtags = '#new-tag-post_tag';
      $taginput = '#tax-input-post_tag';

   }
   
   $count = get_option('tagthepress_count'); 
   if (!$count) $count = 15;
   
   load_plugin_textdomain('TagThePress', 'wp-content/plugins/tagthepress');

   if (strpos($pathinfo["basename"], 'post.php') === 0 ||
       strpos($pathinfo["basename"], 'post-new.php') === 0) {
      echo 
      '<script type="text/javascript">
         /*<![CDATA[*/

         var tagThePress_fetchTags    = "' . __("Fetch tags", "TagThePress") . '";
         var tagThePress_fetchingTags = "' . __("Fetching tags...", "TagThePress") . '";
         var tagThePress_timeout      = "' . __("TagThePress: tagthe.net seems to be down at the moment. Please try again later.", "TagThePress") . '";
         var tagThePress_error        = "' . __("TagThePress: An error occurred. Please inform the creator of this plugin. And please be nice. ;-)", "TagThePress") . '";
         
         addLoadEvent(function() {
            jQuery("' . $html_add_to . '").prepend("<div style=\"margin-bottom: 5px;\"><select name=\"tagthepress_count\" id=\"tagthepress_count\"><option value=\"5\">5</option><option value=\"10\">10</option><option value=\"15\">15</option><option value=\"20\">20</option><option value=\"30\">30</option><option value=\"40\">40</option><option value=\"50\">50</option><option value=\"60\">60</option><option value=\"70\">70</option><option value=\"80\">80</option><option value=\"90\">90</option><option value=\"100\">100</option></select> <input type=\"button\" class=\"button\" value=\"" + tagThePress_fetchTags + "\" id=\"tagthepress\" style=\"vertical-align: middle;\" /> " +
            " <a href=\"http://tagthe.net/\" target=\"_blank\" title=\"tagthe.net\"><img src=\"' . $logo_path . '\" alt=\"tagthe.net\" style=\"vertical-align: middle; margin-left: 3px;\" /></a></div>");

            jQuery("#tagthepress_count").val("' . $count . '");
            jQuery("#tagthepress").click(function(e) {

               var content = (typeof tinyMCE == "undefined" || 
                              typeof tinyMCE.getInstanceById("content") == "undefined" ||
                              tinyMCE.getInstanceById("content").isHidden()) ?
                              "<div>" + jQuery("#content").val() + "</div>" : 
                              tinyMCE.getInstanceById("content").getContent();


               var text = jQuery("#title").val() + " " + 
                          ((content.search(/\\S/) != -1) ? jQuery(content).text() : "") + " " +
                          jQuery("#excerpt").val();

               if (text.search(/\\S/) != -1) {

                  jQuery(this).val(tagThePress_fetchingTags);
                  var count = jQuery("#tagthepress_count").val();
                  
                  jQuery.ajax({
                     type: "POST",
                     async: true,
                     timeout: 15000,
         			   data: { text: text, count: count },
         			   dataType: "json",
         			   dataFilter: function(data, type) {
         			      return data.replace(/<\/?[^>]+>/gi, "");
         			   },         			   
         			   url: "' . $ajax_path . '",
         			   success: function(data, textStatus) {
                        jQuery("' . $newtags . '").focus();
            
                        var fetchedTags = data.memes[0].dimensions.topic || [];
                        if (data.memes[0].dimensions.person)
                           fetchedTags = fetchedTags.concat(data.memes[0].dimensions.person);
                        if (data.memes[0].dimensions.location)
                           fetchedTags = fetchedTags.concat(data.memes[0].dimensions.location);
                  
                        var oldTags     = jQuery.grep(jQuery("' . $taginput . '").val().split(/\s*,\s*/), function(obj, index) { return (obj != ""); });
                        var currentTags = jQuery.grep(jQuery("' . $newtags . '").val().split(/\s*,\s*/), function(obj, index) { return (obj != ""); });
                 
                        for (var i = 0; i < fetchedTags.length; i++) {
                           if (fetchedTags[i] != null &&
                               fetchedTags[i].search(/\\S/) != -1 &&
                               jQuery.inArray(fetchedTags[i], oldTags) == -1 &&
                               jQuery.inArray(fetchedTags[i], currentTags) == -1) {
                              currentTags.push(fetchedTags[i]);
                           }
                        }
                 
                        jQuery("' . $newtags . '").val(currentTags.join(", "));
         			   },
         			   error: function (request, textStatus, errorThrown) {
                        if ("timeout" == textStatus) {
                           alert("TagThePress: tagthe.net seems to be down at the moment. Please try again later.");
                        } else {
                           alert("TagThePress: An error occurred. Please inform the creator of this plugin. And please be nice. ;-) [" + textStatus + ", " + errorThrown + "]");
                        }
                     },
                     complete: function(request, textStatus) {
                        jQuery("#tagthepress").val(tagThePress_fetchTags); 
                     }
                  });
               }
            });
         });

         /*]]>*/
      </script>';
   }
   
}
add_action('admin_print_scripts', 'tagThePress_scripts');
?>
