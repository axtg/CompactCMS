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


/*MARKER*/require_once(BASE_PATH . '/lib/class/exception_ajax.php');

/*MARKER*/require_once(BASE_PATH . '/lib/includes/email-validator/EmailAddressValidator.php');





/**
Return TRUE when one string matches the 'tail' of the other string
*/
function strmatch_tail($a, $b)
{
	if (strlen($a) < strlen($b))
	{
		$tmp = $a;
		$a = $b;
		$b = $tmp;
	}
	
	$idx = strpos($a, $b);
	if ($idx === false)
		return false;
	return ($idx + strlen($b) == strlen($a));
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
	// except... we have directories which start with an underscore. So I guess a single
	// leading underscore should be okay. And so would a trailing underscore...
	//$src = trim($src, '_');
	
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

	return filterParam4IdOrNumber(rawurldecode($_GET[$name]), $def);
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

	return filterParam4Filename(rawurldecode($_GET[$name]), $def);
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

	return filterParam4CommaSeppedFilenames(rawurldecode($_GET[$name]), $def);
}

function getPOSTparam4CommaSeppedFilenames($name, $def = null) 
{
	if (!isset($_POST[$name]))
		return $def;

	return filterParam4CommaSeppedFilenames($_POST[$name], $def);
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




function getGETparam4FullFilePath($name, $def = null, $accept_parent_dotdot = false) 
{
	if (!isset($_GET[$name]))
		return $def;

	return filterParam4FullFilePath(rawurldecode($_GET[$name]), $def, $accept_parent_dotdot);
}

function getPOSTparam4FullFilePath($name, $def = null, $accept_parent_dotdot = false) 
{
	if (!isset($_POST[$name]))
		return $def;

	return filterParam4FullFilePath($_POST[$name], $def, $accept_parent_dotdot);
}

/**
As filterParam4Filename(), but also accepts '/' directory separators

When $accept_parent_dotdot is TRUE, only then does this filter 
accept '../' directory parts anywhere in the path.

WARNING: setting $accept_parent_dotdot = TRUE can be VERY DANGEROUS
         without further checking the result whether it's trying to
		 go places we don't them to go! 
		 
		 Be vewey vewey caweful!
		 
		 Just to give you an idea:
		     ../../../../../../../../../../../../etc/passwords
		 would be LEGAL *AND* VERY DANGEROUS if the accepted path is not
		 validated further upon return from this function!
*/
function filterParam4FullFilePath($value, $def = null, $accept_parent_dotdot = false)
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
		if ($fns[$i] == ".." && !$accept_parent_dotdot)
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

	return filterParam4boolYN(rawurldecode($_GET[$name]), $def);
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
	$rv = filterParam4boolean($value, null);
	if ($rv === true)
	{
		return 'Y';
	}
	else if ($rv === false)
	{
		return 'N';
	}
	return $def;
}




function getGETparam4boolean($name, $def = null)
{
	if (!isset($_GET[$name]))
		return $def;

	return filterParam4boolean(rawurldecode($_GET[$name]), $def);
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

	return filterParam4Number(rawurldecode($_GET[$name]), $def);
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





function getGETparam4DisplayHTML($name, $def = null)
{
	if (!isset($_GET[$name]))
		return $def;

	return filterParam4DisplayHTML(rawurldecode($_GET[$name]), $def);
}

function getPOSTparam4DisplayHTML($name, $def = null)
{
	if (!isset($_POST[$name]))
		return $def;

	return filterParam4DisplayHTML($_POST[$name], $def);
}

/*
Accepts any non-aggressive HTML
*/
function filterParam4DisplayHTML($value, $def = null)
{
	if (!isset($value))
		return $def;

	$value = trim(strval($value)); // force cast to string before we do anything
	if (empty($value))
		return $def;
	
	// TODO: use HTMLpurifier to strip undesirable content. sanitize.inc.php is not an option as it's a type of blacklist filter and we WANT a whitelist approach for future-safe processing.
	
	// convert the input to a string which can be safely printed as HTML; no XSS through JS or 'smart' use of HTML tags:
	$value = htmlentities($value, ENT_NOQUOTES, "UTF-8");
	
	return $value;
}









function getGETparam4Email($name, $def = null)
{
	if (!isset($_GET[$name]))
		return $def;

	return filterParam4Email(rawurldecode($_GET[$name]), $def);
}

function getPOSTparam4Email($name, $def = null)
{
	if (!isset($_POST[$name]))
		return $def;

	return filterParam4Email($_POST[$name], $def);
}

/*
Accepts any valid email address.

Uses the email validator from:

    http://code.google.com/p/php-email-address-validation/
	

*/
function filterParam4Email($value, $def = null)
{
	if (!isset($value))
		return $def;

	$value = trim(strval($value)); // force cast to string before we do anything
	if (empty($value))
		return $def;
	
	$validator = new EmailAddressValidator;
	if ($validator->check_email_address($value))
	{
		// Email address is technically valid
		return $value;
	}
	return $numval;
}









function getGETparam4HumanName($name, $def = null)
{
	if (!isset($_GET[$name]))
		return $def;

	return filterParam4HumanName(rawurldecode($_GET[$name]), $def);
}

function getPOSTparam4HumanName($name, $def = null)
{
	if (!isset($_POST[$name]))
		return $def;

	return filterParam4HumanName($_POST[$name], $def);
}

/*
Accepts any text
*/
function filterParam4HumanName($value, $def = null)
{
	if (!isset($value))
		return $def;

	$value = trim(strval($value)); // force cast to string before we do anything
	if (empty($value))
		return $def;
	
	return htmlentities($value, ENT_NOQUOTES, "UTF-8");
}









function getGETparam4EmailSubjectLine($name, $def = null)
{
	if (!isset($_GET[$name]))
		return $def;

	return filterParam4EmailSubjectLine(rawurldecode($_GET[$name]), $def);
}

function getPOSTparam4EmailSubjectLine($name, $def = null)
{
	if (!isset($_POST[$name]))
		return $def;

	return filterParam4EmailSubjectLine($_POST[$name], $def);
}

/*
Accepts any text except HTML specials cf. RFC2047

Is NOT suitable for direct display within a HTML context (i.e. on a page showing some
sort of feedback after you've entered a mail through a form, etc.); apply
	htmlspecialchars()
before you do so!
*/
function filterParam4EmailSubjectLine($value, $def = null)
{
	if (!isset($value))
		return $def;

	$value = trim(strval($value)); // force cast to string before we do anything
	if (empty($value))
		return $def;
	
	// TODO: real RFC2047 filter.
	$value = str2USASCII($value);
	$value = str_replace('=', '~', $value);
	
	return $value;
}




function getGETparam4EmailBody($name, $def = null)
{
	if (!isset($_GET[$name]))
		return $def;

	return filterParam4EmailBody(rawurldecode($_GET[$name]), $def);
}

function getPOSTparam4EmailBody($name, $def = null)
{
	if (!isset($_POST[$name]))
		return $def;

	return filterParam4EmailBody($_POST[$name], $def);
}

/*
Accepts any text; ready it for HTML display ~ HTML email
*/
function filterParam4EmailBody($value, $def = null)
{
	if (!isset($value))
		return $def;

	$value = trim(strval($value)); // force cast to string before we do anything
	if (empty($value))
		return $def;

	// TODO: real email message body filter?
	return htmlspecialchars($value);
}











function getGETparam4URL($name, $def = null)
{
	if (!isset($_GET[$name]))
		return $def;

	return filterParam4URL(rawurldecode($_GET[$name]), $def);
}

function getPOSTparam4URL($name, $def = null)
{
	if (!isset($_POST[$name]))
		return $def;

	return filterParam4URL($_POST[$name], $def);
}

/*
Accepts any 'fully qualified' URL, i.e. proper domain name, etc.
*/
function filterParam4URL($value, $def = null)
{
	if (!isset($value))
		return $def;

	$value = trim(strval($value)); // force cast to string before we do anything
	if (empty($value))
		return $def;
	
	if (!regexUrl($value, true)) // the ENTIRE string must be a URL, nothing else allowed 'at the tail end'!
		return $def;

	return $value;
}





function getGETparam4DateTime($name, $def = null)
{
	if (!isset($_GET[$name]))
		return $def;

	return filterParam4DateTime(rawurldecode($_GET[$name]), $def);
}

function getPOSTparam4DateTime($name, $def = null)
{
	if (!isset($_POST[$name]))
		return $def;

	return filterParam4DateTime($_POST[$name], $def);
}

/*
Accepts a date/time stamp
*/
function filterParam4DateTime($value, $def = null)
{
	if (!isset($value))
		return $def;

	$value = trim(strval($value)); // force cast to string before we do anything
	if (empty($value))
		return $def;

	$dt = strtotime($value);
	/* 
	time == 0 ~ 1970-01-01T00:00:00 is considered an INVALID date here, 
	because it can easily result from parsing arbitrary input representing 
	the date eqv. of zero(0)... 
	
	time == -1 was the old error signaling return code (pre-PHP 5.1.0)
	*/
	if (!is_int($dt) || $dt <= 0)
	{
		return $def;
	} 
	return $dt;
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
function regexUrl($data, $match_entire_string = false) 
{
	$regex = "((https?|ftp)\:\/\/)?"; // SCHEME 
	$regex .= "([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?"; // User and Pass 
	$regex .= "([a-z0-9-.]*)\.([a-z]{2,3})"; // Host or IP 
	$regex .= "(\:[0-9]{2,5})?"; // Port 
	$regex .= "(\/([a-z0-9+\$_-]\.?)+)*\/?"; // Path 
	$regex .= "(\?[a-z+&\$_.-][a-z0-9;:@&%=+\/\$_.-]*)?"; // GET Query 
	$regex .= "(#[a-z_.-][a-z0-9+\$_.-]*)?"; // Anchor 
	
	if(preg_match('/^' . $regex . ($match_entire_string ? '$' : '') . '/i', $data)) 
	{
		return true;
	}
	return false;
}



function DetermineTemplateName($name = null, $printing = 'N')
{
	global $cfg, $ccms;
	
	if (!empty($name))
	{
		$name = $name . ($printing != 'Y' ? '' : '/print');
		
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
		$name = $ccms['template_collection'][0] . ($printing != 'Y' ? '' : '/print');
		
		// Set the template variable for current page
		$templatefile = BASE_PATH . '/lib/templates/' . $name . '.tpl.html';
	
		// Check whether template exists, specify default or throw "no templates" error.
		if(is_file($templatefile)) 
		{
			return $name;
		}
	}
	
	// for printing ONLY, see if the 'ccms' template exists anyway.
	if ($printing == 'Y')
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
		header('HTTP/1.0 ' . $response_code . ' ' . get_response_code_string($response_code), true, $response_code);
		break;
	}
}


/**
Return the HTTP response code string for the given response code
*/
function get_response_code_string($response_code)
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



/*
http://nadeausoftware.com/node/79
*/
function path_remove_dot_segments($path)
{
    // multi-byte character explode
    $inSegs  = preg_split( '!/!u', $path);
    $outSegs = array();
    foreach ($inSegs as $seg)
    {
        if ($seg == '' || $seg == '.')
            continue;
        if ($seg == '..')
            array_pop($outSegs);
        else
            array_push($outSegs, $seg);
    }
    $outPath = implode('/', $outSegs);
    if ($path[0] == '/')
        $outPath = '/' . $outPath;
    // // compare last multi-byte character against '/'
    // if ($outPath != '/' && (mb_strlen($path)-1) == mb_strrpos($path, '/', 'UTF-8'))
    //     $outPath .= '/';
    return $outPath;
}


			  
/*
Convert any path (absolute or relative) to a fully qualified URL
*/			  
function makeAbsoluteURI($path)
{
	$reqpage = filterParam4FullFilePath($_SERVER["PHP_SELF"]);
	
	$page = array();
	if (strpos($path, '://'))
	{
		if (strpos($path, '?') === false || strpos($path, '://') < strpos($path, '?'))
		{
			/*
			parse_url can only parse URLs, not relative paths. 
			
			http://bugs.php.net/report.php?manpage=function.parse-url#Notes
			*/
			$page = parse_url($path);
			if (isset($page[PHP_URL_SCHEME]))
				return $path;

			/*
			We do NOT accept 'URL's like
			
			   www.example.com/path.ext
			   
			as input: we treat the entire string as a path (and a relative one at that)!
			*/
		}
	}

	/*
	Expect input which is a subset of
	
	   /path/file.exe?query#fragment
	
	with either absolute or relative path/file.ext as the mandatory part.
	*/   
	$idx = strpos($path, '?');
	if ($idx !== false)
	{
		$page[PHP_URL_PATH] = substr($path, 0, $idx);
		
		$path = substr($path, $idx + 1);
		$idx = strpos($path, '#');
		if ($idx !== false)
		{
			$page[PHP_URL_QUERY] = substr($path, 0, $idx);
			$page[PHP_URL_FRAGMENT] = substr($path, $idx + 1);
		}
		else
		{
			$page[PHP_URL_QUERY] = $path;
		}
	}
	else
	{
		$page[PHP_URL_PATH] = $path;
	}
	$path = $page[PHP_URL_PATH];

	if (strpos($path, '/') === 0)
	{
		//already absolute
	} 
	else 
	{
		/*
		Convert relative path to absolute by prepending the current request path 
		(which is absolute) and a '../' basedir-similar. 
		
		This way also provides for relative paths which don't start with './' but
		simply say something like
		  relpath/file.ext
		which will produce a dotted absolute path like this:
		  /current_request_path/reqfile.php/../relpath/file.ext
		which is fine: the ../ will remove the reqfile.php component and we're left 
		with a neatly formatted absolute path!
		*/
		$page[PHP_URL_PATH] = $_SERVER['PHP_SELF'] . '/../' . $path;
	}
	$page[PHP_URL_PATH] = path_remove_dot_segments($page[PHP_URL_PATH]);
	
	// fill in the holes... assume defaults from the current request.
	if (empty($page[PHP_URL_SCHEME]))
	{
		if ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
			|| strpos(strtolower($_SERVER['SERVER_PROTOCOL']), 'https') === 0
			|| intval($_SERVER["SERVER_PORT"]) == 443)
		{
			$page[PHP_URL_SCHEME] = 'https';
		}
		else
		{
			$page[PHP_URL_SCHEME] = 'http';
		}
	}
	if (empty($page[PHP_URL_HOST]))
	{
		$page[PHP_URL_HOST] = $_SERVER["SERVER_NAME"];
	}
	if (empty($page[PHP_URL_PORT]))
	{
		/*
		Only set the port number when it is non-standard:
		*/
		$portno = intval($_SERVER["SERVER_PORT"]);
		if ($portno != 0
			&& ($page[PHP_URL_SCHEME] == 'http' && $portno == 80)
			&& ($page[PHP_URL_SCHEME] == 'https' && $portno == 443))
		{
			$page[PHP_URL_PORT] = $portno;
		}
	}
	
	$url = '';
	if(!empty($page[PHP_URL_SCHEME]))
	{
		$url = $page[PHP_URL_SCHEME] . '://';
	}
	if(!empty($page[PHP_URL_USER]))
	{
		$url .= $page[PHP_URL_USER];
		if(!empty($page[PHP_URL_PASS]))
		{
			$url .= ':' . $page[PHP_URL_PASS];
		}
		$url .= '@';
	}
	if(!empty($page[PHP_URL_HOST]))
	{
		$url .= $page[PHP_URL_HOST];
	}
	$url .= $page[PHP_URL_PATH];
	if (!empty($page[PHP_URL_QUERY]))
	{
		$url .= '?' . $page[PHP_URL_QUERY];
	}
	if (!empty($page[PHP_URL_FRAGMENT]))
	{
		$url .= '#' . $page[PHP_URL_FRAGMENT];
	}
	return $url;
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
	if (!empty($_SESSION['id']) && $canarycage == $_SESSION['id'] 
		&& !empty($_SESSION['host']) && $currenthost == $_SESSION['host']) 
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