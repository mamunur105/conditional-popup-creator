<?php
	
require_once("metabox_constructor_class.php");
// initial metabox for popups_creator post type 
$metabox = new Metabox_Constructor(array(
	'id' 		=> 'popups_id',
	'title' 	=> __('Metabox Demo', 'popupcreator'),
	'screen' 	=> 'popups_creator'
));
// add checkbox fied 
$metabox->addCheckbox(array(
	'id' => 'popups_activation_field',
	'label' => __('Active/Deactive','popupcreator'),
	'desc' => __('Active Popup Or Popups Is not activate','popupcreator')
));
// page list 
function metabox_for_pagelist(){
	global $post;
	$args = array(
		'post_type' => 'page'
	);
	$pages = get_posts($args);
	$page_list = array();
	foreach($pages as $post){
		setup_postdata( $post );
		$page_list[get_the_ID()]= get_the_title(get_the_ID());
	}
	return $page_list;
}
// add select field 
$metabox->addSelect(array(
	'id' => 'popups_select_forpage',
	'label' => __('Popups  For Page','popupcreator'),
	'desc' => __('Select a page for shoing popups','popupcreator'),
	'options' => metabox_for_pagelist() 
));
// radio field 
$metabox->addRadio(
	array(
		'id' => 'dispay_exit_or_pageload',
		'label'=> __('Display popup','popupcreator')
	),
	array(
		'pageload' => __('Display on page load','popupcreator'),
		'exit'=> __('Display on exit','popupcreator')
	)
);
// text field
$metabox->addText(array(
	'id' => 'popups_url_field',
	'label' => __('Url','popupcreator'),
	'desc' => __('Input Url','popupcreator')

));
// number type field 
$metabox->addNumber(array(
	'id' => 'popups_delay',
	'label' => __('Delay Time ','popupcreator')
	'default' => 1,
	'desc' => __('Delay timer Secound example:1 (1s)','popupcreator')
));
// checkbox field 
$metabox->addCheckbox(array(
	'id' => 'popups_thumbnail_field',
	'label' => __('Show Popup Image','popupcreator'),
	'default' => 'on',
	'desc' => __('Click checkbox for show image','popupcreator')
));
// select field 
$metabox->addSelect(array(
	'id' => 'popups_select_field',
	'label' => __('Image Size ','popupcreator'),
	'desc' => __('select image size ','popupcreator'),
	'options' => array(
		'full' => __("Full",'popupcreator'),
		'popup-landscape' => __("Landscape",'popupcreator'),
		'popup-square' => __('Squere''popupcreator')
	)
));
// end metabox field for popups creator 