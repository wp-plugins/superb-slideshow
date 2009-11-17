<?php
/*
Plugin Name: Superb Slideshow
Plugin URI: http://gopi.coolpage.biz/demo/2009/10/02/superb-slideshow/
Description: Superb Slideshow script that incorporates some of your most requested features all rolled into one. Each instance of a fade in slideshow on the page is completely independent of the other, with support for different features selectively enabled for each slideshow.  
Author: Gopi.R
Version: 3.0
Author URI: http://gopi.coolpage.biz/demo/about/
Donate link: http://gopi.coolpage.biz/demo/2009/10/02/superb-slideshow/
*/

function sswld_show() 
{
	$sswld_siteurl = get_option('siteurl');
	$sswld_pluginurl = $sswld_siteurl . "/wp-content/plugins/superb-slideshow/";
	
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
	$doc->load( $sswld_pluginurl . 'superb-slideshow.xml' );
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
	?>
	<script type="text/javascript" src="<?php echo $sswld_pluginurl; ?>/inc/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo $sswld_pluginurl; ?>/inc/superb-slideshow.js"></script>
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


add_filter('the_content','sswld_show_filter');

function sswld_show_filter($content){
	return 	preg_replace_callback('/\[superb-slideshow=(.*?)\]/sim','sswld_show_filter_Callback',$content);
}

function sswld_show_filter_Callback($matches) 
{
	//echo $matches[1];
	$var = $matches[1];
	parse_str($var, $output);
	
	$filename = $output['filename'];
	if($filename==""){$filename = "superb-slideshow.xml";}
	
	$width = $output['amp;width'];
	if($width==""){$width = $output['width'];}
	if(!is_numeric($width)){$width = 200;} 
	
	$height = $output['amp;height'];
	if($height==""){$height = $output['height'];}
	if(!is_numeric($height)){$height = 200;} 
	
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
	$sswld_pp = $sswld_pp . '<script type="text/javascript" src="'. $sswld_pluginurl.'inc/jquery.min.js"></script>';
	$sswld_pp = $sswld_pp . '<script type="text/javascript" src="'.$sswld_pluginurl.'inc/superb-slideshow.js"></script>';
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
	add_option('sswld_title', "Slideshow");
	add_option('sswld_dir', "wp-content/plugins/superb-slideshow/images/");
	add_option('sswld_width', "200");
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
	include_once("extra.php");
	echo "<div class='wrap'>";
	echo "<h2>"; 
	echo wp_specialchars( "Superb Slideshow" ) ;
	echo "</h2>";
    
	$sswld_title = get_option('sswld_title');
	$sswld_dir = get_option('sswld_dir');
	$sswld_width = get_option('sswld_width');
	$sswld_height = get_option('sswld_height');
	$sswld_pause = get_option('sswld_pause');
	
	$sswld_duration = get_option('sswld_duration');
	$sswld_cycles = get_option('sswld_cycles');
	$sswld_displaydesc = get_option('sswld_displaydesc');
	
	if ($_POST['sswld_submit']) 
	{
		$sswld_title = stripslashes($_POST['sswld_title']);
		$sswld_dir = stripslashes($_POST['sswld_dir']);
		$sswld_width = stripslashes($_POST['sswld_width']);
		$sswld_height = stripslashes($_POST['sswld_height']);
		$sswld_pause = stripslashes($_POST['sswld_pause']);
		
		$sswld_duration = stripslashes($_POST['sswld_duration']);
		$sswld_cycles = stripslashes($_POST['sswld_cycles']);
		$sswld_displaydesc = stripslashes($_POST['sswld_displaydesc']);
		
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
	</td><td align="left" valign="top"> <?php if (function_exists (gads)) gads(); ?> </td></tr></table>
	</form>
    <hr />
    <b style="color:#FF0000;">If Image not display go and check the image path in XML file(Use full image url).</b><br /><br />
    We can use this plug-in in three different way.<br />
	1.	Go to widget menu and drag and drop the "Superb Slideshow" widget to your sidebar location. or <br />
	2.	Copy and past the below mentioned code to your desired template location.<br />
	3.	Past the given code to post or page.
    <h2><?php echo wp_specialchars( 'Paste the below code to your desired template location!' ); ?></h2>
    <div style="padding-top:7px;padding-bottom:7px;">
    <code style="padding:7px;">
    &lt;?php if (function_exists (sswld_show)) sswld_show(); ?&gt;
    </code></div>
	<h2><?php echo wp_specialchars( 'Use below code in post or page!' ); ?></h2>
	<div style="padding-top:7px;padding-bottom:7px;">
    <code style="padding:7px;">
    [superb-slideshow=filename=page1.xml&width=400&height=300]
    </code></div>
	filename = page1.xml<br />
	This is the name of the XML file gallery, this xml file should be available in plugin forder.<br />
	width = 400	<br />
	This is width of the gallery.<br />
	height = 300<br />
	This is the height of the gallery.

	<h2><?php echo wp_specialchars( 'How to add more image? This is only for widget, to create new gallery create new XML file and use above code in post or page.' ); ?></h2>
	1. Upload your images into "wp-content/plugins/vertical-carousel-slideshow/images/ " or any where.<br />
	2. Take the "superb-slideshow.xml" XML file from plugin folder.<br />
	3. Create new image node like below (add imagepath,link,title,target).<br />
	3. You can use full image path also.<br />
	<br />
	<code>
		&lt;image&gt;<br>
		&nbsp;&nbsp;&nbsp;&nbsp;	&lt;path&gt;http://www.sitename.com/wp-content/plugins/superb-slideshow/images/gSlide11.jpg&lt;/path&gt;<br>
		&nbsp;&nbsp;&nbsp;&nbsp;	&lt;target&gt;_new&lt;/target&gt;<br>
		&nbsp;&nbsp;&nbsp;&nbsp;	&lt;title&gt;Click to see Superb Slideshow demo&lt;/title&gt;<br>
		&nbsp;&nbsp;&nbsp;&nbsp;	&lt;link&gt;http://gopi.coolpage.biz/demo/&lt;/link&gt;<br>
		&lt;/image&gt;
	</code>
	<h2><?php echo wp_specialchars( 'Requirements/Restrictions!' ); ?></h2>
	1.Works with Wordpress 2.7+	<br />
	2.PHP 4.5+ or above with dom library support.	<br />
	&nbsp;&nbsp;See your phpinfo file to see DOM/XML enabled or not.	<br />
	&nbsp;&nbsp;To work this plugin DOM/XML should be in enabled mode(mostly this will be in enabled mode, so no problem).<br />
	3.Take this file superb-slideshow/inc/superb-slideshow.js and goto 3rd line and replace the image path with full URL.
    <h2><?php echo wp_specialchars( 'About Plugin!' ); ?></h2>
    Plug-in created by <a target="_blank" href='http://gopi.coolpage.biz/demo/about/'>Gopi</a>. ||
	<a target="_blank" href='http://gopi.coolpage.biz/demo/2009/10/02/superb-slideshow/'>click here</a> to see More information.||
    <a target="_blank" href='http://gopi.coolpage.biz/demo/2009/10/02/superb-slideshow/'>click here</a> to post suggestion or comments or how to improve this plugin.||
    <a target="_blank" href='http://gopi.coolpage.biz/demo/2009/10/02/superb-slideshow/'>click here</a> to see LIVE demo.||
    <a target="_blank" href='http://gopi.coolpage.biz/demo/2009/10/02/superb-slideshow/'>click here</a> To download my other plugins.
    <h2><?php echo wp_specialchars( 'Remove above ad!' ); ?></h2>
	Clicking the above ad will not affect the page, To remove the ad follow the below step. <br />
	1. delete the extra.php file. <br />
	2. In this file(superb-slideshow.php) go to line 91 and remove "include_once("extra.php");". <br />
	3. Thats it ad removal over!!<br><br>
    <br></p>
	<?php
	echo "</div>";
}

function sswld_control()
{
	echo '<p>Superb Slideshow.<br> To change the setting goto Superb Slideshow link under SETTING tab.';
	echo ' <a href="options-general.php?page=superb-slideshow/superb-slideshow.php">';
	echo 'click here</a></p>';
}

function sswld_widget_init() 
{
  	register_sidebar_widget(__('Superb Slideshow'), 'sswld_widget');   
	if(function_exists('register_sidebar_widget')) 	
	{
		register_sidebar_widget('Superb Slideshow', 'sswld_widget');
	}
	if(function_exists('register_widget_control')) 	
	{
		register_widget_control(array('Superb Slideshow', 'widgets'), 'sswld_control');
	} 
}

function sswld_deactivation() 
{
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
	add_options_page('Superb Slideshow', 'Superb Slideshow', 7, __FILE__, 'sswld_admin_option' );
}

add_action('admin_menu', 'sswld_add_to_menu');
add_action("plugins_loaded", "sswld_widget_init");
register_activation_hook(__FILE__, 'sswld_install');
register_deactivation_hook(__FILE__, 'sswld_deactivation');
add_action('init', 'sswld_widget_init');
?>
