<?php
/**
* @version		$Id: mod_simpletubelist.php 1.0 $
* @package		Joomla
* @copyright	Copyright (C) 2005 - 2007 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// get params
$svList = trim($params->get('svList'));
$svStyle = trim($params->get('svStyle'));
$svGetTitle = $params->get('svGetTitle');
$svShuffle = $params->get('svShuffle');
// check params
if (!$svList) 
	die('Please, enter video params in the module administrator.');
if (!$svStyle) 
	$svStyle = "width:100%;height:auto;min-height:320px;";
	
// get video data (ID and title)
$vLines = explode("<br />",nl2br($svList));
// shuffle video list
if ($svShuffle=="yes") shuffle($vLines);
?>
<!-- the content: videos and list -->
<div id="svideoframe"></div>
<select id="svideolist" onchange="loadVideo();" style="max-width:100%;">
	<?php
	foreach ($vLines as $k => $vLine) {
		$sVideo = explode("*",$vLine);
		$id = $sVideo[0];
		if (count($sVideo)==1 && $svGetTitle=="yes") {
			// get video feed info (xml) from youtube, but only the title 
			$video_feed = file_get_contents("http://gdata.youtube.com/feeds/api/videos?v=2&q=".$id."&max-results=1&fields=entry(title)&prettyprint=true");
			// xml to object
			$video_obj = simplexml_load_string($video_feed);
			// get the title string to a variable
			$title = trim($video_obj->entry->title);
			if (!$title) $title= "Video #".($k+1);
		}else{
			$title = $sVideo[1];
		}
		?>
		<option value="<?php echo $id;?>"><?php echo $title;?></option>
		<?php 
	} ?>
</select>
<!-- the script -->
<script type="text/javascript">
		//defauld style
		var style = "width:100%;height:auto;min-height:320px";
		//on load, get the first video on list
		var first = document.getElementById("svideolist").options[0].value; 
		document.getElementById("svideoframe").innerHTML = itube( first, style );
		//on change selected item on list
		function loadVideo(){
			var idx = document.getElementById("svideolist").selectedIndex;
			var current = document.getElementById("svideolist").options[idx].value;
			document.getElementById("svideoframe").innerHTML = itube( current, style );
 		}
		function itube(ID, style) {
			return "<iframe style='" + style + "' src='//www.youtube.com/embed/" + ID + "' frameborder='0' allowfullscreen></iframe>";
		}
</script>
