<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * This helper is all about responses for ajax calls or others and the formatting of them
 */

/**
 * this method handles the basic from the responses
 * @param $status
 * @param $message
 * @param $response_type
 * @return mixed
 */
function returnResponse($status, $message, $response_type){
	$response = array(
		'status' => $status,
		'message' => $message
	);
	$final_response = $response_type($response);
	return $final_response;
}

/**
 * this is to format the response in a particular way. in this case to json
 * @param $response
 * @return false|string
 */
function jsonizeResponse($response){
	return json_encode($response, JSON_FORCE_OBJECT);
}
