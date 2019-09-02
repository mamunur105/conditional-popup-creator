<?php


require_once("metabox_constructor_class.php");

$metabox = new Metabox_Constructor(array(
	'id' => 'popups_id',
	'title' => __('Metabox Demo', 'experiment_functionality'),
	'screen' => 'popups_creator'
));

$metabox->addCheckbox(array(
	'id' => 'popups_activation_field',
	'label' => 'Active/Deactive',
	'desc' => 'Active Popup Or Popups Is not activate'
));
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

$metabox->addSelect(array(
	'id' => 'popups_select_forpage',
	'label' => 'Popups  For Page',
	'desc' => 'Select a page for shoing popups',
	'options' => metabox_for_pagelist() 
));

$metabox->addRadio(
	array(
		'id' => 'dispay_exit_or_pageload',
		'label'=>'Display popup'
	),
	array(
		'pageload' => 'Display on page load'
		// 'exit'=> 'Display on exit'
	)
);
// addRadio
$metabox->addText(array(
	'id' => 'popups_url_field',
	'label' => 'Url',
	'desc' => 'Input Url'

));

$metabox->addNumber(array(
	'id' => 'popups_delay',
	'label' => 'Delay Time ',
	'default' => 1,
	'desc' => 'Delay timer Secound example:1 (1s)'
));

$metabox->addCheckbox(array(
	'id' => 'popups_thumbnail_field',
	'label' => 'Show Popup Image',
	'default' => 'on',
	'desc' => 'Click checkbox for show image'
));

$metabox->addSelect(array(
	'id' => 'popups_select_field',
	'label' => 'Image Size ',
	'desc' => 'select image size ',
	'options' => array(
		'full' => "Full",
		'popup-landscape' => "Landscape",
		'popup-square' => 'Squere'
	)
));
