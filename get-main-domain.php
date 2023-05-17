<?php
// get main domain from
function getMainDomain() {
  $home_url = home_url();

	$server_http_host = $_SERVER['HTTP_HOST'];

	$request_uri = $_SERVER['REQUEST_URI'];

	// clean domain
	$domain = str_replace(['http:', 'https:', '//'], '', trim($home_url, '/'));

	// get subdirectory
	$subdir = str_replace($server_http_host, '', $domain);
	//$subdir = trim($subdir, '/');

	return str_replace($subdir, '', $request_uri);
}
