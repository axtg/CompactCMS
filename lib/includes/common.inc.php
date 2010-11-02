<?php

/*
part of CompactCMS
*/


/* make sure no-one can run anything here if they didn't arrive through 'proper channels' */
if(!defined("COMPACTCMS_CODE")) { die('Illegal entry point!'); } /*MARKER*/



if (defined('CCMS_DEVELOPMENT_ENVIRONMENT'))
{
	/* always flush cached data at the start of each invocation -- which always passes through here, at least. */
	clearstatcache();
}



define('MENU_TARGET_COUNT', 5); // CCMS supports 5 menu 'destinations'

if (!defined('BASE_PATH'))
{
	$base = str_replace('\\','/',dirname(dirname(dirname(__FILE__))));
	define('BASE_PATH', $base);
}



/**
Convert any string input to a US-ASCII limited character set with a few common conversions included.

Use this function for filtering any input which doesn't need the full UTF8 range. Most useful as a preprocessor for further 
security-oriented input filters.

Code ripped from function pagetitle($data, $options = array()) in the 'fancyupload' PHP module.
*/
function str2USASCII($src)
{
	static $regex;
	
	if (!$regex)
	{
		$regex = array(
			explode(' ', 'Æ æ Œ œ ß Ü ü Ö ö Ä ä À Á Â Ã Ä Å &#260; &#258; Ç &#262; &#268; &#270; &#272; Ð È É Ê Ë &#280; &#282; &#286; Ì Í Î Ï &#304; &#321; &#317; &#313; Ñ &#323; &#327; Ò Ó Ô Õ Ö Ø &#336; &#340; &#344; Š &#346; &#350; &#356; &#354; Ù Ú Û Ü &#366; &#368; Ý Ž &#377; &#379; à á â ã ä å &#261; &#259; ç &#263; &#269; &#271; &#273; è é ê ë &#281; &#283; &#287; ì í î ï &#305; &#322; &#318; &#314; ñ &#324; &#328; ð ò ó ô õ ö ø &#337; &#341; &#345; &#347; š &#351; &#357; &#355; ù ú û ü &#367; &#369; ý ÿ ž &#378; &#380;'),
			explode(' ', 'Ae ae Oe oe ss Ue ue Oe oe Ae ae A A A A A A A A C C C D D D E E E E E E G I I I I I L L L N N N O O O O O O O R R S S S T T U U U U U U Y Z Z Z a a a a a a a a c c c d d e e e e e e g i i i i i l l l n n n o o o o o o o o r r s s s t t u u u u u u y y z z z'),
		);
		
		//$regex[0][] = '"';
		//$regex[0][] = "'";
	}
	
	$src = strval($src); // force cast to string before we do anything
	
	// US-ASCII-ize known characters...
	$src = str_replace($regex[0], $regex[1], $src);
	// replace any remaining non-ASCII chars...
	$src = preg_replace('/([^ -~])+/', '~', $src);
	
	return trim($src);
}

function str2VarOrFileName($src, $extra_accept_set = '', $accept_leading_minus = false)
{
	static $regex4var;
	
	if (!$regex4var)
	{
		$regex4var = array(
			explode(' ', '&amp; & +'),
			explode(' ', '_n_ _n_ _n_'),
		);
		
		$regex4var[0][] = '"';
		$regex4var[0][] = "'";
	}
	
	$src = str2USASCII($src);

	$src = str_replace($regex4var[0], $regex4var[1], $src);
	
	$src = preg_replace('/(?:[^\-A-Za-z0-9_' . $extra_accept_set . ']|_)+/', '_', $src);
	// reduce series of underscores to a single one:
	$src = preg_replace('/_+/', '_', $src);
	// remove leading and trailing underscores (which may have been whitespace or other stuff before)
	$src = trim($src, '_');
	// We NEVER tolerate a leading dot:
	$src = preg_replace('/^\.+/', '', $src);
	if (!$accept_leading_minus)
	{
		$src = preg_replace('/^-+/', '', $src);
	}
	return $src;
}

/*
moved here from tiny_mce_gzip.php; augmented to accept '.', NOT accepting '_' as it's a wildcard in SQL
*/

/**
Return the value of a $_GET[] entry (or $def if the entity doesn't exist), stripped of
anything that can adversily affect 
- SQL queries (anti-SQL injection) 
- HTML output (anti-XSS)
- filenames (including UNIX 'hidden' files, which start with a dot '.')

Accepted/passed set of characters are, specified as a regex:

[0-9A-Za-z\-][0-9A-Za-z,.\-]*[0-9A-Za-z]

As such, this is very good filter for numeric values, alphanumeric 'id's and filenames.
*/
function getGETparam4IdOrNumber($name, $def = null) 
{
	if (!isset($_GET[$name]))
		return $def;

	return filterParam4IdOrNumber($_GET[$name], $def);
}
	
function getPOSTparam4IdOrNumber($name, $def = null) 
{
	if (!isset($_POST[$name]))
		return $def;

	return filterParam4IdOrNumber($_POST[$name], $def);
}
	
function filterParam4IdOrNumber($value, $def = null) 
{
	if (!isset($value))
		return $def;

	$value = strval($value); // force cast to string before we do anything
	
	// see if the value is a valid integer (plus or minus); only then do we accept a leading minus.
	$numval = intval($value);
	if ($numval == 0 || strval($numval) != $value)
	{
		// no full match for the integer check, so this is a string and we don't tolerate leading minus.
		$value = str2VarOrFileName($value);
	}
	else
	{
		$value = strval($numval);
	}
	return $value;
}

function getGETparam4Filename($name, $def = null) 
{
	if (!isset($_GET[$name]))
		return $def;

	return filterParam4Filename($_GET[$name], $def);
}

function getPOSTparam4Filename($name, $def = null) 
{
	if (!isset($_POST[$name]))
		return $def;

	return filterParam4Filename($_POST[$name], $def);
}

/**
As filterParam4IdOrNumber(), but also accepts '_' underscores and '.' dots, but NOT at the start or end of the filename!
*/
function filterParam4Filename($value, $def = null)
{
	if (!isset($value))
		return $def;

	$value = str2VarOrFileName($value, '~\.');
	
	return $value;
}






function getGETparam4CommaSeppedFilenames($name, $def = null) 
{
	if (!isset($_GET[$name]))
		return $def;

	return filterParam4CommaSeppedFilenames($_GET[$name], $def);
}

/**
As filterParam4Filename(), but also accepts a 'comma' separator
*/
function filterParam4CommaSeppedFilenames($value, $def = null)
{
	if (!isset($value))
		return $def;

	$fns = explode(',', strval($value));
	if (!is_array($fns))
	{
		return $def;
	}
	for ($i = count($fns); $i-- > 0; )
	{
		$fns[$i] = filterParam4Filename($fns[$i], '');
	}
	
	return implode(',', $fns);
}



function getGETparam4FullFilePath($name, $def = null) 
{
	if (!isset($_GET[$name]))
		return $def;

	return filterParam4FullFilePath($_GET[$name], $def);
}

/**
As filterParam4Filename(), but also accepts '/' directory separators
*/
function filterParam4FullFilePath($value, $def = null)
{
	if (!isset($value))
		return $def;

	$fns = explode('/', strval($value));
	if (!is_array($fns))
	{
		return $def;
	}
	for ($i = count($fns); $i-- > 0; )
	{
		$fns[$i] = filterParam4Filename($fns[$i], '');
		if ($i > 0 && $i < count($fns) - 1 && empty($fns[$i]))
		{
			return $def; // illegal path specified!
		}
	}
	
	return implode('/', $fns);
}




function getGETparam4boolYN($name, $def = null)
{
	if (!isset($_GET[$name]))
		return $def;

	return filterParam4boolYN($_GET[$name], $def);
}

function getPOSTparam4boolYN($name, $def = null)
{
	if (!isset($_POST[$name]))
		return $def;

	return filterParam4boolYN($_POST[$name], $def);
}

/*
Accepts any boolean value: as any number, T[rue]/F[alse] or Y[es]/N[o]
*/
function filterParam4boolYN($value, $def = null)
{
	if (!isset($value))
		return $def;

	$value = trim(strval($value)); // force cast to string before we do anything
	if (empty($value))
		return $def;
	
	// see if the value is a valid integer (plus or minus)
	$numval = intval($value);
	if (strval($numval) !== $value)
	{
		// no full match for the integer check, so this is a string hence we must check the text-based boolean values here:
		switch (strtoupper(substr($value, 0, 1)))
		{
		case 'T':
		case 'Y':
			return 'Y';
			
		case 'F':
		case 'N':
			return 'N';
			
		default:
			return $def;
		}
	}
	else
	{
		return ($numval != 0 ? 'Y' : 'N');
	}
	return $def;
}




function getGETparam4boolean($name, $def = null)
{
	if (!isset($_GET[$name]))
		return $def;

	return filterParam4boolean($_GET[$name], $def);
}

function getPOSTparam4boolean($name, $def = null)
{
	if (!isset($_POST[$name]))
		return $def;

	return filterParam4boolean($_POST[$name], $def);
}

/*
Accepts any boolean value: as any number, T[rue]/F[alse] or Y[es]/N[o]
*/
function filterParam4boolean($value, $def = null)
{
	if (!isset($value))
		return $def;

	$value = trim(strval($value)); // force cast to string before we do anything
	if (empty($value))
		return $def;
	
	// see if the value is a valid integer (plus or minus)
	$numval = intval($value);
	if (strval($numval) !== $value)
	{
		// no full match for the integer check, so this is a string hence we must check the text-based boolean values here:
		switch (strtoupper(substr($value, 0, 1)))
		{
		case 'T':
		case 'Y':
			return true;
			
		case 'F':
		case 'N':
			return false;
			
		default:
			return $def;
		}
	}
	else
	{
		return ($numval != 0);
	}
	return $def;
}




function getGETparam4Number($name, $def = null)
{
	if (!isset($_GET[$name]))
		return $def;

	return filterParam4Number($_GET[$name], $def);
}

function getPOSTparam4Number($name, $def = null)
{
	if (!isset($_POST[$name]))
		return $def;

	return filterParam4Number($_POST[$name], $def);
}

/*
Accepts any number
*/
function filterParam4Number($value, $def = null)
{
	if (!isset($value))
		return $def;

	$value = trim(strval($value)); // force cast to string before we do anything
	if (empty($value))
		return $def;
	
	// see if the value is a valid integer (plus or minus)
	$numval = intval($value);
	if (strval($numval) !== $value)
	{
		// no full match for the integer check, so this is a non-numeric string:
		return $def;
	}
	else
	{
		return $numval;
	}
}

















/*
	public static function pagetitle($data, $options = array()){
		static $regex;
		if (!$regex){
			$regex = array(
				explode(' ', 'Æ æ Œ œ ß Ü ü Ö ö Ä ä À Á Â Ã Ä Å &#260; &#258; Ç &#262; &#268; &#270; &#272; Ð È É Ê Ë &#280; &#282; &#286; Ì Í Î Ï &#304; &#321; &#317; &#313; Ñ &#323; &#327; Ò Ó Ô Õ Ö Ø &#336; &#340; &#344; Š &#346; &#350; &#356; &#354; Ù Ú Û Ü &#366; &#368; Ý Ž &#377; &#379; à á â ã ä å &#261; &#259; ç &#263; &#269; &#271; &#273; è é ê ë &#281; &#283; &#287; ì í î ï &#305; &#322; &#318; &#314; ñ &#324; &#328; ð ò ó ô õ ö ø &#337; &#341; &#345; &#347; š &#351; &#357; &#355; ù ú û ü &#367; &#369; ý ÿ ž &#378; &#380;'),
				explode(' ', 'Ae ae Oe oe ss Ue ue Oe oe Ae ae A A A A A A A A C C C D D D E E E E E E G I I I I I L L L N N N O O O O O O O R R S S S T T U U U U U U Y Z Z Z a a a a a a a a c c c d d e e e e e e g i i i i i l l l n n n o o o o o o o o r r s s s t t u u u u u u y y z z z'),
			);
			
			$regex[0][] = '"';
			$regex[0][] = "'";
		}
		
		$data = trim(substr(preg_replace('/(?:[^A-z0-9]|_|\^)+/i', '_', str_replace($regex[0], $regex[1], $data)), 0, 64), '_');
		return !empty($options) ? self::checkTitle($data, $options) : $data;
	}

	*/



// GENERAL FUNCTIONS ==
/**
 Test whether the filter regex (for URL detection) matches the given $data value. Return
 TRUE if so.

 This is used to see if there's a URL specified in the page description.
*/
function regexUrl($data) {
	$regex = "((https?|ftp)\:\/\/)?"; // SCHEME 
	$regex .= "([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?"; // User and Pass 
	$regex .= "([a-z0-9-.]*)\.([a-z]{2,3})"; // Host or IP 
	$regex .= "(\:[0-9]{2,5})?"; // Port 
	$regex .= "(\/([a-z0-9+\$_-]\.?)+)*\/?"; // Path 
	$regex .= "(\?[a-z+&\$_.-][a-z0-9;:@&%=+\/\$_.-]*)?"; // GET Query 
	$regex .= "(#[a-z_.-][a-z0-9+\$_.-]*)?"; // Anchor 
	
	if(preg_match("/^$regex/i", $data)) {
		return true;
	}
	return false;
}



function DetermineTemplateName($name = null, $printing = 'N')
{
	global $cfg, $ccms;
	
	if (!empty($name))
	{
		$name = $name . ($printing == 'N' ? '' : '/print');
		
		// Set the template variable for current page
		$templatefile = BASE_PATH . '/lib/templates/' . $name . '.tpl.html';
	
		// Check whether template exists, specify default or throw "no templates" error.
		if(is_file($templatefile)) 
		{
			return $name;
		}
	}
	
	if(is_array($ccms['template_collection']) && count($ccms['template_collection']) > 0) 
	{
		// pick default template
		$name = $ccms['template_collection'][0] . ($printing == 'N' ? '' : '/print');
		
		// Set the template variable for current page
		$templatefile = BASE_PATH . '/lib/templates/' . $name . '.tpl.html';
	
		// Check whether template exists, specify default or throw "no templates" error.
		if(is_file($templatefile)) 
		{
			return $name;
		}
	}
	
	// for printing ONLY, see if the 'ccms' template exists anyway.
	if ($printing != 'N')
	{
		$name = 'ccms/print';
		
		// Set the template variable for current page
		$templatefile = BASE_PATH . '/lib/templates/' . $name . '.tpl.html';

		// Check whether template exists, specify default or throw "no templates" error.
		if(file_exists($templatefile)) 
		{
			return $name;
		}
	}
	
	die($ccms['lang']['system']['error_notemplate']);
}


/**
Determine how the PHP interpreter was invoked: cli/cgi/fastcgi/server,
where 'server' implies PHP is part of a webserver in the form of a 'module' (e.g. mod_php5) or similar.

This information is used, for example, to decide the correct way to send the 'respose header code':
see send_response_status_header().
*/
function get_interpreter_invocation_mode()
{
	global $_ENV;
	global $_SERVER;
	
	/*
	see 
	
	http://nl2.php.net/manual/en/function.php-sapi-name.php
	http://stackoverflow.com/questions/190759/can-php-detect-if-its-run-from-a-cron-job-or-from-the-command-line
	*/
	$mode = "server";
	$name = php_sapi_name();
	if (preg_match("/fcgi/", $name) == 1)
	{
		$mode = "fastcgi";
	} 
	else if (preg_match("/cli/", $name) == 1)
	{
		$mode = "cli";
	} 
	else if (preg_match("/cgi/", $name) == 1)
	{
		$mode = "cgi";
	} 
	
	/*
	check whether POSIX functions have been compiled/enabled; xampp on Win32/64 doesn't have the buggers! :-( 
	*/
	if (function_exists('posix_isatty'))
	{
		if (posix_isatty(STDOUT))
		{
			/* even when seemingly run as cgi/fastcgi, a valid stdout TTY implies an interactive commandline run */
			$mode = 'cli';
		}
	}
	
	if (!empty($_ENV['TERM']) && empty($_SERVER['REMOTE_ADDR']))
	{
		/* even when seemingly run as cgi/fastcgi, a valid stdout TTY implies an interactive commandline run */
		$mode = 'cli';
	}
	
	return $mode;
}


/**
Performs the correct way of transmitting the response status code header: PHP header() must be invoked in different ways
dependent on the way the PHP interpreter has been invoked.

See also:

http://nl2.php.net/manual/en/function.header.php
*/
function send_response_status_header($response_code)
{
	$mode = get_interpreter_invocation_mode();
	switch ($mode)
	{
	default:
	case 'fcgi':
		header('Status: ' . $response_code, true, $response_code);
		break;
		
	case 'server':
		header('HTTP/1.0 ' . $response_code . ' ' . get_response_code_string($reponse_code), true, $response_code);
		break;
	}
}


/**
Return the HTTP response code string for the given response code
*/
function get_response_code_string($reponse_code)
{
	switch (intval($response_code))
	{
	case 100:	return "RFC2616 Section 10.1.1: Continue";
	case 101:	return "RFC2616 Section 10.1.2: Switching Protocols";
	case 200:	return "RFC2616 Section 10.2.1: OK";
	case 201:	return "RFC2616 Section 10.2.2: Created";
	case 202:	return "RFC2616 Section 10.2.3: Accepted";
	case 203:	return "RFC2616 Section 10.2.4: Non-Authoritative Information";
	case 204:	return "RFC2616 Section 10.2.5: No Content";
	case 205:	return "RFC2616 Section 10.2.6: Reset Content";
	case 206:	return "RFC2616 Section 10.2.7: Partial Content";
	case 300:	return "RFC2616 Section 10.3.1: Multiple Choices";
	case 301:	return "RFC2616 Section 10.3.2: Moved Permanently";
	case 302:	return "RFC2616 Section 10.3.3: Found";
	case 303:	return "RFC2616 Section 10.3.4: See Other";
	case 304:	return "RFC2616 Section 10.3.5: Not Modified";
	case 305:	return "RFC2616 Section 10.3.6: Use Proxy";
	case 307:	return "RFC2616 Section 10.3.8: Temporary Redirect";
	case 400:	return "RFC2616 Section 10.4.1: Bad Request";
	case 401:	return "RFC2616 Section 10.4.2: Unauthorized";
	case 402:	return "RFC2616 Section 10.4.3: Payment Required";
	case 403:	return "RFC2616 Section 10.4.4: Forbidden";
	case 404:	return "RFC2616 Section 10.4.5: Not Found";
	case 405:	return "RFC2616 Section 10.4.6: Method Not Allowed";
	case 406:	return "RFC2616 Section 10.4.7: Not Acceptable";
	case 407:	return "RFC2616 Section 10.4.8: Proxy Authentication Required";
	case 408:	return "RFC2616 Section 10.4.9: Request Time-out";
	case 409:	return "RFC2616 Section 10.4.10: Conflict";
	case 410:	return "RFC2616 Section 10.4.11: Gone";
	case 411:	return "RFC2616 Section 10.4.12: Length Required";
	case 412:	return "RFC2616 Section 10.4.13: Precondition Failed";
	case 413:	return "RFC2616 Section 10.4.14: Request Entity Too Large";
	case 414:	return "RFC2616 Section 10.4.15: Request-URI Too Large";
	case 415:	return "RFC2616 Section 10.4.16: Unsupported Media Type";
	case 416:	return "RFC2616 Section 10.4.17: Requested range not satisfiable";
	case 417:	return "RFC2616 Section 10.4.18: Expectation Failed";
	case 500:	return "RFC2616 Section 10.5.1: Internal Server Error";
	case 501:	return "RFC2616 Section 10.5.2: Not Implemented";
	case 502:	return "RFC2616 Section 10.5.3: Bad Gateway";
	case 503:	return "RFC2616 Section 10.5.4: Service Unavailable";
	case 504:	return "RFC2616 Section 10.5.5: Gateway Time-out";
	case 505:	return "RFC2616 Section 10.5.6: HTTP Version not supported";
	default:	return "Unknown Response Code";
	}
}



/**
 Check for authentic request ($cage=md5(SESSION_ID),$host=md5(CURRENT_HOST)) v.s. 'id' and 'host' session variable values.
 
 This is a basic check to protect against some forms of CSRF attacks. An extended check using the additional 'rc1'/'rc2' session
 variables must be used to validate form submissions to ensure the transmission follows such a web form immediately.
*/
function checkAuth()
{
	$canarycage	= md5(session_id());
	$currenthost = md5($_SERVER['HTTP_HOST']);
	
	//if(md5(session_id())==$cage && md5($_SERVER['HTTP_HOST']) == $host) {   // [i_a] bugfix
	if ($canarycage == $_SESSION['id'] && $currenthost == $_SESSION['host']) 
	{
		return true;
	} 
	return false;
}

function SetAuthSafety()
{
	$_SESSION['host'] = md5($_SERVER['HTTP_HOST']);
	$_SESSION['id']	= md5(session_id());
	
	unset($_SESSION['rc1']);
	unset($_SESSION['rc2']);
}


?>