<?php

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

	case '504':
		$statusCode = '504';
		$title = 'Gateway Timeout';
		$message = "This usually means something has taken too long to respond. You can try refreshing which may fix this issue.\nWe've logged the error and will investigate.";
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

?>