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


function getBaseUrl() {
	$subDirectoryName = $_SERVER['SCRIPT_FILENAME'];
        $subDirectoryName = str_replace($_SERVER['DOCUMENT_ROOT'],  '', $subDirectoryName);
        $subDirectoryName = str_replace('/index.php',  '', $subDirectoryName);

        $protocol = isset($_SERVER['HTTPS']) && ($_SERVER['SERVER_PORT'] == 443 || $_SERVER['HTTPS'] == 'on')
            ? 'https' : 'http';

        $baseUrl = $protocol . '://' . $_SERVER['HTTP_HOST'] . $subDirectoryName;

        echo $baseUrl;
}
