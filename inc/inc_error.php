<?php

set_error_handler("error_handler", E_ERROR | E_WARNING);

set_exception_handler('exception_handler');

function exception_handler($exception) {
	$msg = "Exception: " . $exception->getMessage();
	$uri = $_SERVER['REQUEST_URI'];
	$post = json_encode($_POST);
	$msg .= " uri: $uri, post: $post";
	logError($msg);
}

function error_handler($errno, $errstr, $errfile, $errline) {
    if (!(error_reporting() & $errno)) {
        // This error code is not included in error_reporting
        return;
    }

    switch ($errno) {
    case E_USER_ERROR:
	$msg = "PHP Error: no: $errno, msg: $errstr on line $errline in file $errfile"; 
	$uri = $_SERVER['REQUEST_URI'];
	$post = json_encode($_POST);
	$msg .= " uri: $uri, post: $post";
	logError($msg);
	return false;
        break;

    case E_USER_WARNING:
	$msg = "PHP Warning: no: $errno, msg: $errstr on line $errline in file $errfile"; 
	$uri = $_SERVER['REQUEST_URI'];
	$post = json_encode($_POST);
	$msg .= " uri: $uri, post: $post";
	logInfo($msg);
	return false;
        break;

    default:
	$msg = "PHP Unknown Error: no: $errno, msg: $errstr on line $errline in file $errfile"; 
	$uri = $_SERVER['REQUEST_URI'];
	$post = json_encode($_POST);
	$msg .= " uri: $uri, post: $post";
	logInfo($msg);
        break;
    }

    /* Don't execute PHP internal error handler */
    return true;
}

