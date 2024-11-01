<?php

/*

	Plugin Name: Shortcode to flag

	Plugin URI: http://www.djetelina.cz/shortcode-to-flag/

	Version: 1.0

	Author: David Jetelina & Hopefighter

	Author URI: http://djetelina.cz/

	Description: Basic plugin that turns a shortcode to a flag image

	License: GPL2

 */



// Importing flag css

function stf_flagstyle() {

	wp_register_style('stf_flagstyle', plugin_dir_url(__FILE__) . 'flagstyle.css');

wp_enqueue_style('stf_flagstyle');

}

add_action( 'wp_enqueue_scripts','stf_flagstyle');



//decoding csv database

function stf_csv($line)

{

	return str_getcsv($line, $delimiter=';');

}

$csv = array_map('stf_csv', file(plugin_dir_url(__FILE__) . 'countries.csv'));

$flags = array();

foreach ($csv as $bunka)

{

	$flags[$bunka[0]] = end($bunka); 

}





$i = 0;



// Creating shortcodes for csv elements

$code = "";



foreach ($flags as $country => $abbr)

{

	$var = "country$i";

	

	$short = strtoupper($abbr);



	$code .= <<<HEREDOC



function $var() {

	return "<img class=\"stf-flag\" src=\"" . plugin_dir_url(__FILE__) . 'flags/img/$short.png' . "\" alt=\"$country\" />";

};

add_shortcode("$short", "$var");



HEREDOC;



	$i++;

}



eval($code);