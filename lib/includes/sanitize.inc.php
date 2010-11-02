<?php

/* make sure no-one can run anything here if they didn't arrive through 'proper channels' */
if(!defined("COMPACTCMS_CODE")) { die('Illegal entry point!'); } /*MARKER*/


/////////////////////////////////////////////
// input sanitizer function - 10/2009 by LDM

/*
From another place and time, someone else has been wondering as well:

---

In PHPMailer-FE version 4.0.5, we added the ability to sanitize or clean up
user-submitted form data.

The file responsible for this is: _lib/inc.sanitize.php

This script is not entirely of our making. The core of the script is authored
by someone else ... and we have no idea who. We would like to attribute this
excellent work to the rightful author. If anyone recognizes who the author is
please contact us so that we can attribute this work and recognize the author.
All we have is one single line in the script that says:
// input sanitizer function - LDM 2008

We have modified this script to function with PHPMailer-FE.

In essence, it will "clean-up" or sanitize the data users type into the form.

The specific functionality is (in no specific order):

- will remove hex values
- will stop directory traversal
- will stop MySQL injections and MySQL comments
- will stop base64 encoding
- will remove null characters
- will do basic HTML entities checks and conversion
- will convert all tabs to spaces
- will convert all PHP tags to safe HTML entities
- will convert all XML tags to safe HTML entities
- will convert all Javascript (and other script) tags to safe HTML entities
- will compact all exploded words
- will remove all Javascript (and other scripts) from links and images
- will sanitize all bad HTML code
- will sanitize all bad script code

Essentially, if enabled, it will eliminiate and/or minimize the impact of
hacker access to forms to generate cross site scripting attacks, database
injection or attacks, and javascript/vbscript (etc) malicious use.

The sanitize utility is not intended to be used for data validation or
formatting.

Enjoy!
Andy Prevost (codeworxtech)

----

Postscript: 'LDM' is still an unknown entity apparently.
*/

function sanitize($dtype, $dlen, $data, $charset = "UTF-8"){

// dtype 1: allow numbers, space, and '-' 
// dtype 2: allow alpha and spaces only
// dtype 3: allow alphanumeric, spaces, period, and '-'
// dtype 4: allow alphanumeric w/ all punctuation 
// dtype 5: email validation chars 
// dlen: data length limit, 0 = no length limit 

	// special cleanups
	$data = preg_replace("/x1a/",'', $data);
	$data = preg_replace("/x00/",'', $data);

	// the 2 tests above may not be needed due to this more complete test
	$data = preg_replace('/([\x00-\x08][\x0b-\x0c][\x0e-\x20])/', '', $data);

	$data = preg_replace("|\.\./|",'', $data); // stop directory traversal
	$data = preg_replace("/--/",' - ', $data); // stop mySQL comments
	$data = preg_replace("/%3A%2F%2F/",'', $data); // stop B64 encoded '://'

	
// new, added 8-31-2008 /////////////////////////////////
////////// START NEW TESTS 08-31-2008 ////////////////////////////////////////

// Remove Null Characters
// This prevents sandwiching null characters
// between ascii characters, like Java\0script.
    $data = preg_replace('/\0+/', '', $data);
    $data = preg_replace('/(\\\\0)+/', '', $data);

 
// Validate standard character entities
// Add a semicolon if missing.  We do this to enable
// the conversion of entities to ASCII later.
    $data = preg_replace('#(&\#*\w+)[\x00-\x20]+;#u',"\\1;",$data);
		
// Validate UTF16 two byte encoding (x00)
// Just as above, adds a semicolon if missing.
    $data = preg_replace('#(&\#x*)([0-9A-F]+);*#iu',"\\1\\2;",$data);


// URL Decode
// Just in case stuff like this is submitted:
// <a href="http://%77%77%77%2E%67%6F%6F%67%6C%65%2E%63%6F%6D">Google</a>
// Note: Normally urldecode() would be easier but it removes plus signs
//
// See also RFC 3986 / http://en.wikipedia.org/wiki/Percent-encoding
// about the _rejected_ %uxxxx encoding checked for here; can't be sure 
// browsers/servers don't accept it, so better keep it here:
    $data = preg_replace("/%u([a-f0-9]{4})/i", "&#x\\1;", $data);
    $data = preg_replace("/%([a-f0-9]{2})/i", "&#x\\1;", $data);		
				

// Convert character entities to ASCII
// This permits our tests below to work reliably.
// We only convert entities that are within tags since
// these are the ones that will pose security problems.
    if (preg_match_all("/<(.+?)>/si", $data, $matches)) {		
        for ($i = 0; $i < count($matches[0]); $i++) {
            $data = str_replace($matches[1][$i],
                html_entity_decode($matches[1][$i], ENT_COMPAT, $charset), $data);
        }
    }
	

// Convert all tabs to spaces
// This prevents strings like this: ja	vascript
// Note: we deal with spaces between characters later.	
    $data = preg_replace("#\t+#", " ", $data);
	

// Makes PHP tags safe
// Note: XML tags are inadvertently replaced too:
//	<?xml
// But it doesn't seem to pose a problem, 
// and only terrorists use XML anyway, right?
    $data = str_replace(array('<?php', '<?PHP', '<?', '?>'),  array('&lt;?php', '&lt;?PHP', '&lt;?', '?&gt;'), $data);
	

// Compact any exploded words
// This corrects words like:  j a v a s c r i p t
// These words are compacted back to their correct state.	
    $words = array('javascript', 'vbscript', 'script', 'applet', 'alert', 'document', 'write', 'cookie', 'window');
    foreach ($words as $word) {
        $temp = '';
        for ($i = 0; $i < strlen($word); $i++) {
            $temp .= substr($word, $i, 1)."\s*";
        }
	
        $temp = substr($temp, 0, -3);
        $data = preg_replace('#'.$temp.'#s', $word, $data);
        $data = preg_replace('#'.ucfirst($temp).'#s', ucfirst($word), $data);
    }


// Remove disallowed Javascript in links or img tags	
    $data = preg_replace("#<a.+?href=.*?(alert\(|alert&\#40;|javascript\:|window\.|document\.|\.cookie|<script|<xss).*?\>.*?</a>#si", "", $data);
    $data = preg_replace("#<img.+?src=.*?(alert\(|alert&\#40;|javascript\:|window\.|document\.|\.cookie|<script|<xss).*?\>#si","", $data);
    $data = preg_replace("#<(script|xss).*?\>#si", "", $data);

// Remove JavaScript Event Handlers
// Note: This code is a little blunt.  It removes
// the event handler and anything up to the closing >,
// but it's unlikely to be a problem.

	$data = preg_replace('#(<[^>]+.*?)(onabort|onactivate|onafterprint|onafterupdate|onbeforeactivate|onbeforecopy|onbeforecut|onbeforedeactivate|onbeforeeditfocus|onbeforepaste|onbeforeprint|onbeforeunload|onbeforeupdate|onblur|onbounce|oncellchange|onchange|onclick|oncontextmenu|oncontrolselect|oncopy|oncut|ondataavailable|ondatasetchanged|ondatasetcomplete|ondblclick|ondeactivate|ondrag|ondragend|ondragenter|ondragleave|ondragover|ondragstart|ondrop|onerror|onerrorupdate|onfilterchange|onfinish|onfocus|onfocusin|onfocusout|onhelp|onkeydown|onkeypress|onkeyup|onlayoutcomplete|onload|onlosecapture|onmousedown|onmouseenter|onmouseleave|onmousemove|onmouseout|onmouseover|onmouseup|onmousewheel|onmove|onmoveend|onmovestart|onpaste|onpropertychange|onreadystatechange|onreset|onresize|onresizeend|onresizestart|onrowenter|onrowexit|onrowsdelete|onrowsinserted|onscroll|onselect|onselectionchange|onselectstart|onstart|onstop|onsubmit|onunload)[^>]*>#iU',"\\1>",$data);


// Sanitize naughty HTML elements
// If a tag containing any of the words in the list
// below is found, the tag gets converted to entities.   
// So this: <blink>
// Becomes: &lt;blink&gt;	
    $data = preg_replace('#<(/*\s*)(alert|vbscript|javascript|applet|basefont|base|behavior|bgsound|blink|body|embed|expression|form|frameset|frame|head|html|ilayer|iframe|input|layer|link|meta|object|plaintext|style|script|textarea|title|xml|xss|lowsrc)([^>]*)>#is', "&lt;\\1\\2\\3&gt;", $data);
            

// Sanitize naughty scripting elements
// Similar to above, only instead of looking for
// tags it looks for PHP and JavaScript commands
// that are disallowed.  Rather than removing the
// code, it simply converts the parenthesis to entities
// rendering the code un-executable.
// For example:	eval('some code')
// Becomes:		eval&#40;'some code'&#41;
    $data = preg_replace('#(alert|cmd|passthru|eval|exec|system|fopen|fsockopen|file|file_get_contents|readfile|unlink)(\s*)\((.*?)\)#si', "\\1\\2&#40;\\3&#41;", $data);
                                            
// Final clean up
// This adds a bit of extra precaution in case
// something got through the above filters
    $bad = array(
            'document.cookie'	=> '',
            'document.write'	=> '',
            'window.location'	=> '',
            "javascript\s*:"	=> '',
            "Redirect\s+302"	=> '',
            '<!--'			=> '&lt;!--',
            '-->'			=> '--&gt;'
    );
    
    foreach ($bad as $key => $val)	{
            $data = preg_replace("#".$key."#i", $val, $data);
    }

////////// END NEW TESTS /////////////////////////////////////////////////////
// final character stripping & length limiting

	if($dlen != 0){
		$data = substr($data, 0, $dlen);
	}

	if($dtype == 1){
		// allow only numeric characters, space, period, and '-' 
		$data = preg_replace("/[^0-9\-\ \.]/",'', $data);
	}
	
	if($dtype == 2){
		// allow only alpha characters, '_' and space 
		$data = preg_replace("/[^a-zA-Z~\ \_]/",'', $data);
	}
	
	if($dtype == 3){
		// allow only alphanumeric characters, space, '_', period, colon, and '-'
		$data = preg_replace("/[^0-9a-zA-Z~\-\ \.\:\_]/",'', $data);
	}
	
	if($dtype == 4){
		// allow only alphanumeric characters w/ punctuation + carriage returns
		$data = preg_replace("|[^0-9a-zA-Z~@#$%=:;_, \\n\\\!\^&\*\(\)\-\+\.\?\/\'\"]|",'', $data);
	}

	if($dtype == 5){
		// specifically for email validation 
		$data = preg_replace("|[^0-9a-zA-Z@_\-\.]|",'', $data);
	}

	$data = trim($data);

	return $data;
}
?>