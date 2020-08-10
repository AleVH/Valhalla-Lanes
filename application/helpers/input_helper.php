<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * This helper is for input sanitation/validation
 */

function sanitizeInteger($input_value){
	return filter_var($input_value, FILTER_SANITIZE_NUMBER_INT);
}

function sanitizeString($input_value){
	return filter_var($input_value, FILTER_SANITIZE_STRING);
}

function sanitizeDate($input_value){
	return preg_replace("([^0-9/] | [^0-9-])","" ,htmlentities($input_value));
}
