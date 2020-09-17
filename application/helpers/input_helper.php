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

function sanitizeStringRemoveAllNonAlphanumeric($input_value){
	return preg_replace("/[^A-Za-z0-9]/", '_', $input_value); // this will replace everything that is not a letter or a number with an underscore
//	return preg_replace("/[^A-Za-z0-9 ]/", '_', $input_value); // this will replace everything is not a letter, number or space with an underscore
}
