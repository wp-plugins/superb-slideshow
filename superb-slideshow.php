<?php
/*
Plugin Name: Superb Slideshow
Plugin URI: http://www.gopiplus.com/work/2010/07/18/superb-slideshow/
Description: Superb Slideshow script that incorporates some of your most requested features all rolled into one. Each instance of a fade in slideshow on the page is completely independent of the other, with support for different features selectively enabled for each slideshow.  
Author: Gopi.R
Version: 9.2
Author URI: http://www.gopiplus.com/work/2010/07/18/superb-slideshow/
Donate link: http://www.gopiplus.com/work/2010/07/18/superb-slideshow/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

function sswld_show() 
{
	$sswld_siteurl = get_option('siteurl');
	$sswld_pluginurl = $sswld_siteurl . "/wp-content/plugins/superb-slideshow/";
	
	$sswld_package = "";
	$sswld_width = get_option('sswld_width');
	
	$sswld_xml_file = get_option('sswld_xml_file');
	if($sswld_xml_file==""){$sswld_xml_file = "superb-slideshow-v2.xml";}
	
	$sswld_width = get_option('sswld_width');
	$sswld_height = get_option('sswld_height');
	$sswld_pause = get_option('sswld_pause');
	$sswld_duration = get_option('sswld_duration');
	$sswld_cycles = get_option('sswld_cycles');
	$sswld_displaydesc = get_option('sswld_displaydesc');
	
	if(!is_numeric($sswld_width)){$sswld_width = 200;} 
	if(!is_numeric($sswld_height)){$sswld_height = 150;} 
	if(!is_numeric($sswld_pause)){$sswld_pause = 2500;}
	if(!is_numeric($sswld_duration)){$sswld_duration = 500;}
	if(!is_numeric($sswld_cycles)){$sswld_cycles = 0;}
	
	$doc = new DOMDocument();
	$doc->load( $sswld_pluginurl . $sswld_xml_file );
	$images = $doc->getElementsByTagName( "image" );
	foreach( $images as $image )
	{
	  $paths = $image->getElementsByTagName( "path" );
	  $path = $paths->item(0)->nodeValue;
	  $targets = $image->getElementsByTagName( "target" );
	  $target = $targets->item(0)->nodeValue;
	  $titles = $image->getElementsByTagName( "title" );
	  $title = $titles->item(0)->nodeValue;
	  $links = $image->getElementsByTagName( "link" );
	  $link = $links->item(0)->nodeValue;
	  $sswld_package = $sswld_package .'["'.$path.'", "'.$link.'", "'.$target.'", "'.$title.'"],';
	}
	$sswld_random = get_option('sswld_random');
	if($sswld_random==""){$sswld_random = "Y";}
	if($sswld_random=="Y")
	{
		$sswld_package = explode("[", $sswld_package);
		shuffle($sswld_package);
		$sswld_package = implode("[", $sswld_package);
		$sswld_package = '[' . $sswld_package;
		$sswld_package = explode("[[", $sswld_package);
		$sswld_package = implode("[", $sswld_package); // ugly hack to get rid of stray [[
	}
	
	$sswld_package = substr($sswld_package,0,(strlen($sswld_package)-1));
	?>
	<script type="text/javascript">
	var sswldgallery=new sswldSlideShow({
		sswld_wrapperid: "sswld", //Unique ID of blank DIV on page to house Slideshow
		sswld_dimensions: [<?php echo $sswld_width; ?>, <?php echo $sswld_height; ?>], //width, height of gallery in pixels. Should reflect dimensions of largest image
		sswld_imagearray: [<?php echo $sswld_package; ?>],
		sswld_displaymode: {type:'auto', pause:<?php echo $sswld_pause; ?>, cycles:<?php echo $sswld_cycles; ?>, wraparound:false},
		sswld_persist: false, //remember last viewed slide and recall within same session?
		sswld_fadeduration: <?php echo $sswld_duration; ?>, //transition duration (milliseconds)
		sswld_descreveal: "<?php echo $sswld_displaydesc; ?>",
		sswld_togglerid: ""
	})
	</script>
	<div style="padding-top:5px;"></div>
	<div id="sswld"></div>
	<div style="padding-top:5px;"></div>
	<?php
}

add_shortcode( 'superb-slideshow', 'sswld_shortcode' );

function sswld_shortcode( $atts ) 
{
	$sswld_package  = "";
	$sswld_pp = "";
	//echo $matches[1];
	//$var = $matches[1];
	//parse_str($var, $output);
	
	//[superb-slideshow filename="page1.xml" width="400" height="300"]
	if ( ! is_array( $atts ) )
	{
		return '';
	}
	$width = $atts['width'];
	$height = $atts['height'];
	$filename = $atts['filename'];
	
	//$filename = $output['filename'];
	if($filename==""){$filename = "superb-slideshow-v2.xml";}
	
	//@$width = @$output['amp;width'];
	//if($width==""){@$width = $output['width'];}
	if(!is_numeric(@$width)){@$width = 200;} 
	
	//@$height = @$output['amp;height'];
	//if($height==""){@$height = @$output['height'];}
	if(!is_numeric(@$height)){@$height = 200;} 
	
	$sswld_siteurl = get_option('siteurl');
	$sswld_pluginurl = $sswld_siteurl . "/wp-content/plugins/superb-slideshow/";
	
	$sswld_width = $width;
	$sswld_height = $height;

	$sswld_pause = get_option('sswld_pause');
	$sswld_duration = get_option('sswld_duration');
	$sswld_cycles = get_option('sswld_cycles');
	$sswld_displaydesc = get_option('sswld_displaydesc');
	
	if(!is_numeric($sswld_pause)){$sswld_pause = 2500;}
	if(!is_numeric($sswld_duration)){$sswld_duration = 500;}
	if(!is_numeric($sswld_cycles)){$sswld_cycles = 0;}
	
	$doc = new DOMDocument();
	$doc->load( $sswld_pluginurl . $filename );
	$images = $doc->getElementsByTagName( "image" );
	foreach( $images as $image )
	{
	  $paths = $image->getElementsByTagName( "path" );
	  $path = $paths->item(0)->nodeValue;
	  $targets = $image->getElementsByTagName( "target" );
	  $target = $targets->item(0)->nodeValue;
	  $titles = $image->getElementsByTagName( "title" );
	  $title = $titles->item(0)->nodeValue;
	  $links = $image->getElementsByTagName( "link" );
	  $link = $links->item(0)->nodeValue;
	  $sswld_package = $sswld_package .'["'.$path.'", "'.$link.'", "'.$target.'", "'.$title.'"],';
	}
	$sswld_package = substr($sswld_package,0,(strlen($sswld_package)-1));
	
	$sswld_wrapperid = str_replace(".","_",$filename);
	$sswld_wrapperid = str_replace("-","_",$sswld_wrapperid);
	$sswld_pp = $sswld_pp . '<script type="text/javascript">';
	$sswld_pp = $sswld_pp . 'var sswldgallery=new sswldSlideShow({sswld_wrapperid: "'.$sswld_wrapperid.'", sswld_dimensions: ['.$sswld_width.', '. $sswld_height.'], sswld_imagearray: ['. $sswld_package.'],sswld_displaymode: {type:"auto", pause:'.$sswld_pause.', cycles:'. $sswld_cycles.', wraparound:false},sswld_persist: false, sswld_fadeduration: "'.$sswld_duration.'", sswld_descreveal: "'.$sswld_displaydesc.'",sswld_togglerid: ""})';
	$sswld_pp = $sswld_pp . '</script>';
	$sswld_pp = $sswld_pp . '<div style="padding-top:5px;"></div>';
	$sswld_pp = $sswld_pp . '<div id="'.$sswld_wrapperid.'"></div>';
	$sswld_pp = $sswld_pp . '<div style="padding-top:5px;"></div>';
	return $sswld_pp;
}

function sswld_install() 
{
	add_option('sswld_xml_file', "superb-slideshow-v2.xml");
	add_option('sswld_random', "Y");
	add_option('sswld_title', "Slideshow");
	add_option('sswld_dir', "wp-content/plugins/superb-slideshow/images/");
	add_option('sswld_width', "175");
	add_option('sswld_height', "150");
	add_option('sswld_pause', "2500");
	add_option('sswld_duration', "500");
	add_option('sswld_cycles', "0");
	add_option('sswld_displaydesc', "always");
}

function sswld_widget($args) 
{
	extract($args);
	echo $before_widget . $before_title;
	echo get_option('sswld_title');
	echo $after_title;
	sswld_show();
	echo $after_widget;
}

function sswld_admin_option() 
{
	echo "<div class='wrap'>";
	echo "<h2>"; 
	echo "Superb Slideshow";
	echo "</h2>";
    
	
	$sswld_xml_file = get_option('sswld_xml_file');
	$sswld_random = get_option('sswld_random');
	$sswld_title = get_option('sswld_title');
	$sswld_dir = get_option('sswld_dir');
	$sswld_width = get_option('sswld_width');
	$sswld_height = get_option('sswld_height');
	$sswld_pause = get_option('sswld_pause');
	
	$sswld_duration = get_option('sswld_duration');
	$sswld_cycles = get_option('sswld_cycles');
	$sswld_displaydesc = get_option('sswld_displaydesc');
	
	if (@$_POST['sswld_submit']) 
	{
		$sswld_xml_file = stripslashes($_POST['sswld_xml_file']);
		$sswld_random = stripslashes($_POST['sswld_random']);		
		$sswld_title = stripslashes($_POST['sswld_title']);
		$sswld_dir = stripslashes(@$_POST['sswld_dir']);
		$sswld_width = stripslashes($_POST['sswld_width']);
		$sswld_height = stripslashes($_POST['sswld_height']);
		$sswld_pause = stripslashes($_POST['sswld_pause']);
		
		$sswld_duration = stripslashes($_POST['sswld_duration']);
		$sswld_cycles = stripslashes($_POST['sswld_cycles']);
		$sswld_displaydesc = stripslashes($_POST['sswld_displaydesc']);
		
		update_option('sswld_xml_file', $sswld_xml_file );
		update_option('sswld_random', $sswld_random );
		update_option('sswld_title', $sswld_title );
		update_option('sswld_dir', $sswld_dir );
		update_option('sswld_width', $sswld_width );
		update_option('sswld_height', $sswld_height );
		update_option('sswld_pause', $sswld_pause );
		
		update_option('sswld_duration', $sswld_duration );
		update_option('sswld_cycles', $sswld_cycles );
		update_option('sswld_displaydesc', $sswld_displaydesc );
	}
	?>
	<form name="sswld_form" method="post" action="">
	<table width="100%" border="0" cellspacing="0" cellpadding="3"><tr><td align="left">
	<?php
	echo '<p>XML File:<br><input  style="width: 200px;" maxlength="500" type="text" value="';
	echo $sswld_xml_file . '" name="sswld_xml_file" id="sswld_xml_file" /><br>(Enter Name of the XML file, If You have updated the plugin from 3.0 to 4.0 then use your xml file name or enter "superb-slideshow.xml")</p>';

	echo '<p>Random:<br><input  style="width: 100px;" maxlength="1" type="text" value="';
	echo $sswld_random . '" name="sswld_random" id="sswld_random" />(Y/N)</p>';

	echo '<p>Title:<br><input  style="width: 200px;" maxlength="200" type="text" value="';
	echo $sswld_title . '" name="sswld_title" id="sswld_title" /></p>';
	
	echo '<p>Width:<br><input  style="width: 100px;" maxlength="4" type="text" value="';
	echo $sswld_width . '" name="sswld_width" id="sswld_width" />Only Number, This not for page and post gallery.</p>';
	
	echo '<p>Height:<br><input  style="width: 100px;" maxlength="4" type="text" value="';
	echo $sswld_height . '" name="sswld_height" id="sswld_height" />Only Number, This not for page and post gallery.</p>';
	
	echo '<p>Pause:<br><input maxlength="4" style="width: 100px;" type="text" value="';
	echo $sswld_pause . '" name="sswld_pause" id="sswld_pause" />Only Number<br>';
	echo 'Pause between slides</p>';
	
	echo '<p>Fade Duration:<br><input maxlength="4" style="width: 100px;" type="text" value="';
	echo $sswld_duration . '" name="sswld_duration" id="sswld_duration" />Only Number<br>';
	echo 'The duration of the fade effect when transitioning from one image to the next, in milliseconds.</p>';
	
	echo '<p>Cycles:<br><input maxlength="1" style="width: 100px;" type="text" value="';
	echo $sswld_cycles . '" name="sswld_cycles" id="sswld_cycles" />Only Number<br>';
	echo 'The cycles option when set to 0 will cause the slideshow to rotate perpetually,';
	echo 'while any number larger than 0 means it will stop after N cycles.</p>';

	echo '<p>Display Description:<br><input maxlength="10" style="width: 100px;" type="text" value="';
	echo $sswld_displaydesc . '" name="sswld_displaydesc" id="sswld_displaydesc" />always/ondemand<br>';
	echo 'ondemand = Show description when the user mouses over the slideshow.<br>';
	echo 'always = Always show description panel at the foot of the slideshow.<br></p>';

	echo '<input name="sswld_submit" id="sswld_submit" class="button-primary" value="Submit" type="submit" />';
	?>
	</td><td align="left" valign="top">  </td></tr></table>
	</form>
	<br />
	<strong>Plugin configuration</strong>
	<ul>
		<li>Option 1. Drag and drop the widget</li>
		<li>Option 2. Paste the below code to your desired template location</li>
		<li>Option 3. Use plugin short code in posts and pages</li>
	</ul>
	Check official website for more information <a target="_blank" href='http://www.gopiplus.com/work/2010/07/18/superb-slideshow/'>click here</a>
	<?php
	echo "</div>";
}

function sswld_control()
{
	echo '<p>Superb Slideshow.<br> To change the setting goto Superb Slideshow link under Setting menu.';
	echo ' <a href="options-general.php?page=superb-slideshow/superb-slideshow.php">';
	echo 'click here</a></p>';
}

function sswld_widget_init() 
{
	if(function_exists('wp_register_sidebar_widget')) 	
	{
		wp_register_sidebar_widget('Superb Slideshow', 'Superb Slideshow', 'sswld_widget');
	}
	if(function_exists('wp_register_widget_control')) 	
	{
		wp_register_widget_control('Superb Slideshow', array('Superb Slideshow', 'widgets'), 'sswld_control');
	} 
}


function sswld_deactivation() 
{
	delete_option('sswld_xml_file');
	delete_option('sswld_random');
	delete_option('sswld_title');
	delete_option('sswld_dir');
	delete_option('sswld_width');
	delete_option('sswld_height');
	delete_option('sswld_pause');
	delete_option('sswld_duration');
	delete_option('sswld_cycles');
	delete_option('sswld_displaydesc');
}

function sswld_add_to_menu() 
{
	add_options_page('Superb Slideshow','Superb Slideshow','manage_options',__FILE__,'sswld_admin_option');  
}

if (is_admin()) 
{
	add_action('admin_menu', 'sswld_add_to_menu');
}

function sswld_add_javascript_files() 
{
	if (!is_admin())
	{
		wp_enqueue_script( 'jquery');
		wp_enqueue_script( 'superb-slideshow', get_option('siteurl').'/wp-content/plugins/superb-slideshow/inc/superb-slideshow.js');
	}
}    
 
add_action('init', 'sswld_add_javascript_files');
add_action("plugins_loaded", "sswld_widget_init");
register_activation_hook(__FILE__, 'sswld_install');
register_deactivation_hook(__FILE__, 'sswld_deactivation');
add_action('init', 'sswld_widget_init');
?>