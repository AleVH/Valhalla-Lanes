<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

function standardisedMessage($error_message){
	if(stripos($error_message, 'duplicate') !== FALSE){
		return 'Duplicated value';
	}
	if(stripos($error_message, 'some other error') !== FALSE){
		return 'Error while saving in database';
	}

	return 'General error, tell Ale to check this';
}
