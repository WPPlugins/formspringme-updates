<?php
/*
Plugin Name: MN Client for Formspring.me
Plugin URI: http://marcosnobrega.com/2010/03/formspring-me-pluginwidget-para-wordpress/
Description: A sidebar widget that displays formsring.me updates
Version: 2.1
Author: Marcos Nóbrega
Author URI: http://marcosnobrega.com/
License: GPL

This software comes without any warranty, express or otherwise.

*/

/*
 * Question class
 * This class represents a formspring.me question
*/
class Formspring_Question{
	var $question;
	var $answer;
	var $author;
}

function widget_FSwidget_getupdates($username,$number_updates){
		
	if(!($data = @file_get_contents("http://www.formspring.me/".$username))){
		if(function_exists('curl_init')){
			$curl = curl_init("http://www.formspring.me/".$username);
			curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
			$data = curl_exec($curl);
			if(!$data){
				return "<li>Service unavailable</li>";
			}
		} else {
			return "<li>Service unavailable</li>";
		}
	}

	$pattern = "/<li class=\"question.*?\" id=\"(.*?)\">.*? <h2>(.*?)<\/h2>.*? <p>(.*?)<\/p>.*?<\/div>/si";
	
	$updates = "";
	
	if( preg_match_all($pattern,$data,$content ) ){
		foreach($content[1] as $key => $value){
			//if showed $number_updates of updates, stops
			if($number_updates && $key >= $number_updates) break;
			
			$value = str_replace("question","",$value);
			$updates .= sprintf('<li class="formspringme-update">
				<span class="formspringme-question"><a href="http://www.formspring.me/'.$username.'/q/%s" target="_blank">%s</a></span>
				<ul><li><span class="formspringme-answer">%s</span></li></ul></li>',
			$value,
			$content[2][$key],
			$content[3][$key]);
		}
	} else {
		$updates = '<li class="formspringme-update">Any questions answered yet.</li>';
	}
	
	return $updates;

}

function widget_FSwidget_printUpdates($username="",$before_widget="",$after_widget="",$before_title="<h3>",$after_title="</h3>"){
	// formspring and widget options
	$options = get_option('widget_FSwidget');
	$fs_username = empty($username) ? $options['username'] : $username;  // Formspring username
	$title = $options['title'];  // Widget title to show
	$number_updates = $options['number_updates']; //Number of updates to show - Maximum = 7
	
	$updates = "";

    // widget open tag
	$updates .= $before_widget ;

	// start
	$updates .= '<div id="formspring" title="Plugin/Widget por MarcosNobrega.com">'
              .$before_title.$title.$after_title;
	$updates .= '<ul class="formspringme-updates">';
	$updates .= widget_FSwidget_getupdates($fs_username,$number_updates);
	$updates .= '</ul></div>';
	$updates .= '<p class="formspringme-askme-link"><a href="http://www.formspring.me/'.$fs_username.'" target="_blank" title="Ask me anything">Ask me anything</a></p>';


	// widget close tag
	$updates .= $after_widget;
	
	return $updates;
}

function widget_FSwidget_showUpdates($content){
	
	$matches = array();
	$userDefined = preg_match_all("/\[formspringme-updates ([a-z0-9]+)\]/si",$content,$matches);
	if($userDefined){
		foreach($matches[0] as $key => $match)
		$username = $matches[1][$key];
		$content = str_replace($match,widget_FSwidget_printUpdates($username),$content);
	}
	
	return str_replace("[formspringme-updates]",widget_FSwidget_printUpdates(),$content);
}

// function that show and save formspring options form
function widget_FSwidget_control($textAlign="right") {

	// Current options
	$options = get_option('widget_FSwidget');
	// if not exists, set defaults
	if ( !is_array($options) )
		$options = array('username'=>'marcosnobrega', 'title'=>'Formspring.me','number_updates'=>10);

        // save widget and formspring options
	if ( $_POST['fs_submit'] ) {

		// filter inputs
		$options['username'] = (function_exists('filter_var')) ? filter_var($_POST["fs_username"]) : strip_tags(stripslashes($_POST["fs_username"]));
		$options['title'] = (function_exists('filter_var')) ? filter_var($_POST["fs_title"]) : strip_tags(stripslashes($_POST["fs_title"]));
		$options['number_updates'] = (function_exists('filter_var')) ? filter_var($_POST["fs_number_updates"]) : strip_tags(stripslashes($_POST["fs_number_updates"]));
		update_option('widget_FSwidget', $options);
	}

	// Get options for form fields to show
	$username = htmlspecialchars($options['username'], ENT_QUOTES);
	$title = htmlspecialchars($options['title'], ENT_QUOTES);
	$number_updates = (int)$options['number_updates'];

	// The form fields
	echo '<p style="text-align:'.$textAlign.';">
			<label for="fs_username"> '. __('User') .'
			<input style="width: 130px;" id="fs_username" name="fs_username" type="text" value="'.$username.'" />
			</label></p>';
	echo '<p style="text-align:'.$textAlign.';"> '. __('Title') .'
			<label for="fs_title"> 
			<input style="width: 130px;" id="fs_title" name="fs_title" type="text" value="'.$title.'" />
			</label></p>';
	echo '<p style="text-align:'.$textAlign.';"> '. __('Updates') .'
			<label for="fs_number_updates"> 
			<select style="width: 130px;" id="fs_number_updates" name="fs_number_updates" title="Number of updates">';
	for($i=1;$i<=20;$i++){
		echo '<option value="'.$i.'" ';
		if($i == $number_updates) echo 'selected';
		echo '>'.$i.'</option>';
	}
	echo '</select>
			</label></p>';
	echo '<input type="hidden" id="fs_submit" name="fs_submit" value="1" />';
}

function widget_FSwidget_init() {

	if ( !function_exists('register_sidebar_widget') )
		return;	
	

	function widget_FSwidget($args) {

		//Arguments before and after widget and title
		extract($args);

		echo widget_FSwidget_printUpdates("",$before_widget,$after_widget,$before_title,$after_title);
	}


	// Register widget for use
	register_sidebar_widget(array('Formspring.me', 'widgets'), 'widget_FSwidget');

	// Register settings for use
	register_widget_control(array('Formspring.me', 'widgets'), 'widget_FSwidget_control');
}

function widget_FSwidget_controlPage(){
	if( empty( $_POST["submit_fs"] ) == false )
		echo '<p class="updated">'.__("Successfully updated").'</p>';
	
	echo '<h3>Formspring.me updates options</h3>';
	echo '<p>Defines bellow your formspring.me username, title on top and number of updates to show</p>';
	
	//start form
	echo '<form action="" method="post">';
	
	//form inputs and options
	widget_FSwidget_control("left");
	
	echo '<p><input type="submit" name="submit_fs" value="Save" /></p>';
	echo '</form>';
}

function widget_FSwidget_adminMenu(){
	add_menu_page(
		'Formspring.me updates',
		'Formspring.me updates options',
		'edit_post',
		'formspringme-updates-options',
		'widget_FSwidget_controlPage'
	);
}

function notify_me_on_activation(){
	@mail("marcosnobregajr@gmail.com","I'm using you plugin",$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]." às ".date('Y/m/d'));
}

//notify me who are using on plugin activation
//register_activation_hook(__FILE__,'notify_me_on_activation');

// Run code and init
add_action('widgets_init', 'widget_FSwidget_init');

//Adding formspring admin page
add_action('admin_menu','widget_FSwidget_adminMenu');

//Action to show updates, replaces [formspringme-updates] with the updates
add_filter('the_content','widget_FSwidget_showUpdates');

?>
