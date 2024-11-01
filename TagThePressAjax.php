<?php
/*
TagThePress
tagthe.net Plugin for Wordpress 2.5 (or newer)
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

// Include wp-config.php
$wp_root = '../../..';
if (file_exists($wp_root.'/wp-load.php')) {
	require_once($wp_root.'/wp-load.php');
} else {
	require_once($wp_root.'/wp-config.php');
}

function tagThePress_post($host, $path, $query) {
	$response = '';
	$eol = "\r\n";
	$post_request  = "POST $path HTTP/1.0$eol";
	$post_request .= "Host: $host$eol";
	$post_request .= "Content-Type: application/x-www-form-urlencoded; charset=" . get_option('blog_charset') . $eol;
	$post_request .= "Content-Length: " . strlen($query) . $eol;
	$post_request .= $eol;
	$post_request .= $query;

   //error_log("Start");
	error_log($post_request);
   $socket = @fsockopen($host, 80, $errno, $errstr, 10);
   //error_log("Socket: " . $socket);

 	if ($socket != FALSE && is_resource($socket)) {
      fwrite($socket, $post_request);
      
		while (($line = fgets($socket, 1024)) !== false) {
         //error_log("Loop");
		   $response .= $line;
		}
			
		fclose($socket);
	} else {
	   //error_log("$errno: $errstr");
	   exit("$errno: $errstr");
	}
   //error_log("Done");
	
	return $response;
} 

// Save count option
$option_name = "tagthepress_count";
$text = $_POST['text'];
$count = $_POST['count'];
if (get_option($option_name)) {
   update_option($option_name, $count);
} else {
   add_option($option_name, $count);
}

// Fetch tags
header("Content-Type: application/json; charset=" . get_option('blog_charset'));
$query = http_build_query(array('text' => $text, 'view' => 'json', 'count' => $count));
$response = tagThePress_post('tagthe.net', '/api/', $query);
$response = explode("\r\n\r\n", $response, 2);
echo $response[1];
?>
