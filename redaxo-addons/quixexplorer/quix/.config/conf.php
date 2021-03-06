<?php
//------------------------------------------------------------------------------
// Configuration Variables
	
	// login to use QuiXplorer: (true/false)
	$GLOBALS["require_login"] = false;
	
	// language: (en, de, es, fr, nl, ru)
	$GLOBALS["language"] = "de";
	
	// the filename of the QuiXplorer script: (you rarely need to change this)
	$GLOBALS["script_name"] = "http://".$_SERVER['HTTP_HOST'].$_SERVER["PHP_SELF"];
	
	// allow Zip, Tar, TGz -> Only (experimental) Zip-support
	$GLOBALS["zip"] = false;	//function_exists("gzcompress");
	$GLOBALS["tar"] = false;
	$GLOBALS["tgz"] = false;
	
	// QuiXplorer version:
	$GLOBALS["version"] = "2.3";
//------------------------------------------------------------------------------
// Global User Variables (used when $require_login==false)
	
	// the home directory for the filemanager: (use '/', not '\' or '\\', no trailing '/')
	$GLOBALS["home_dir"] = "..";
	
	// the url corresponding with the home directory: (no trailing '/')
	$GLOBALS["home_url"] = "http://".$_SERVER['HTTP_HOST'].dirname(dirname($_SERVER["PHP_SELF"]));
	
	// show hidden files in QuiXplorer: (hide files starting with '.', as in Linux/UNIX)
	$GLOBALS["show_hidden"] = true;
	
	// filenames not allowed to access: (uses PCRE regex syntax)
	$GLOBALS["no_access"] = "^\.ht";
	
	// user permissions bitfield: (1=modify, 2=password, 4=admin, add the numbers)
	$GLOBALS["permissions"] = 7;
//------------------------------------------------------------------------------
/* NOTE:
	Users can be defined by using the Admin-section,
	or in the file ".config/.htusers.php".
	For more information about PCRE Regex Syntax,
	go to http://www.php.net/pcre.pattern.syntax
*/
//------------------------------------------------------------------------------
?>