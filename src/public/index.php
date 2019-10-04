<?php

// Disable any kind of caching
header("Cache-Control: max-age=1, s-maxage=1, no-cache, must-revalidate, no-store");

if (!isset($_SERVER['HTTP_X_FORMAT'])) {
	if (!isset($_SERVER['HTTP_ACCEPT'])) {
		$_SERVER['HTTP_X_FORMAT'] = 'text/html';
	}
	else {
		$_SERVER['HTTP_X_FORMAT'] = $_SERVER['HTTP_ACCEPT'];
	}
}
if (!isset($_SERVER['HTTP_X_CODE'])) {
	$_SERVER['HTTP_X_CODE'] = '404';
}

// Read in accept headers from Nginx Ingress
if (strpos($_SERVER['HTTP_X_FORMAT'], 'text/html') !== FALSE) {
	$response = 'html';
}
elseif (strpos($_SERVER['HTTP_X_FORMAT'], 'application/json') !== FALSE) {
	$response = 'json';
}
elseif (strpos($_SERVER['HTTP_X_FORMAT'], 'application/xml') !== FALSE) {
	$response = 'xml';
}
else {
	$response = 'text';
}

// Set messaging based on the forwarded status code in X-Code

switch ($_SERVER['HTTP_X_CODE']) {

	case '400':
		$statusCode = '400';
		$title = 'Bad Request';
		$message = "Your request seems to be in a format or using a methid that isn't supported or recognised.\nWe've logged the error and will investigate.";
		break;

	case '401':
		$statusCode = '401';
		$title = 'Unauthorised';
		$message = "Authentication is required to access this page, please send HTTP Authentication header with your request.\nWe've logged the error and will investigate.";
		break;

	case '403':
		$statusCode = '403';
		$title = 'Forbidden';
		$message = "You may be trying to access a folder without an index or trying to access a file without the necessary permissions.\nWe've logged the error and will investigate.";
		break;

	case '404':
		$statusCode = '404';
		$title = 'Not Found';
		$message = "A 404 error has occurred. This usually means that what you're looking for has moved, been removed or was never at this URL.\nWe've logged the error and will investigate.";
		break;

	case '405':
		$statusCode = '405';
		$title = 'Method Not Allowed';
		$message = "The HTTP Method you're using isn't allowed on this URL.\nWe've logged the error and will investigate.";
		break;

	case '429':
		$statusCode = '429';
		$title = 'Too Many Requests';
		$message = "You've exceeded the rate limit for this server. Please wait a while before trying again.\nWe've logged the error and will investigate.";
		break;

	case '500':
		$statusCode = '500';
		$title = 'Server Error';
		$message = "Oops. Something went wrong. Try refreshing - this may be temporary.\nWe've logged the error and will investigate.";
		break;

	case '502':
		$statusCode = '502';
		$title = 'Bad Gateway';
		$message = "Oops. Something went wrong. Try refreshing - this may be temporary.\nWe've logged the error and will investigate.";
		break;

	case '503':
		$statusCode = '503';
		$title = 'Service Temporarily Unavailable';
		$message = "Oops. Something went wrong. Try refreshing - this may be temporary.\nWe've logged the error and will investigate.";
		break;

	case '504':
		$statusCode = '504';
		$title = 'Gateway Timeout';
		$message = "This usually means something has taken too long to respond. You can try refreshing which may fix this issue.\nWe've logged the error and will investigate.";
		break;

	case '505':
		$statusCode = '505';
		$title = 'HTTP Version Not Supported';
		$message = "The HTTP version you're using is not supported.\nWe've logged the error and will investigate.";
		break;

	default:
		$statusCode = $_SERVER['HTTP_X_CODE'];
		$title = 'Unhandled Error Code';
		$message = "An unknown error has occurred.\nWe've logged the error and will investigate.";
		break;
}

switch ($response) {
	case 'html':
		header('Content-Type: text/html');
		http_response_code($statusCode);
		include('../html.php');
		break;
	
	case 'json':
		header('Content-Type: application/json');
		http_response_code($statusCode);
		include('../json.php');
		break;

	case 'xml':
		header('Content-Type: application/xml');
		http_response_code($statusCode);
		include('../xml.php');
		break;

	case 'text':
		header('Content-Type: text/plain');
		http_response_code($statusCode);
		include('../text.php');
		break;
}

// If using Atatus, log errors
if (extension_loaded('atatus')) {

	// Set these conditionally in case the request is actually in-cluster (probably only happens for lets encrypt/cert-manager related ingresses)
	if (!isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
		$_SERVER['HTTP_X_FORWARDED_PROTO'] = $_SERVER['REQUEST_SCHEME'];
	}
	if (!isset($_SERVER['HTTP_X_FORWARDED_HOST'])) {
		$_SERVER['HTTP_X_FORWARDED_HOST'] = $_SERVER['HTTP_HOST'];
	}

    atatus_set_app_name("nginx-ingress");
    atatus_add_custom_data("URI", $_SERVER['HTTP_X_ORIGINAL_URI']);
    atatus_add_custom_data("ContentType", $_SERVER['HTTP_X_FORMAT']);
    atatus_add_custom_data("Namespace", $_SERVER['HTTP_X_NAMESPACE']);
    atatus_add_custom_data("IngressName", $_SERVER['HTTP_X_INGRESS_NAME']);
    atatus_add_custom_data("ServiceName", $_SERVER['HTTP_X_SERVICE_NAME']);
    atatus_add_custom_data("ServicePort", $_SERVER['HTTP_X_SERVICE_PORT']);
    atatus_add_custom_data("StatusCode", $_SERVER['HTTP_X_CODE']);
    atatus_notify_exception($statusCode . ' error on ' . $_SERVER['HTTP_X_FORWARDED_PROTO'] . '://' . $_SERVER['HTTP_X_FORWARDED_HOST'] . $_SERVER['HTTP_X_ORIGINAL_URI']);
    atatus_set_transaction_name($_SERVER['HTTP_X_FORWARDED_PROTO'] . '://' . $_SERVER['HTTP_X_FORWARDED_HOST'] . $_SERVER['HTTP_X_ORIGINAL_URI']);
}


?>