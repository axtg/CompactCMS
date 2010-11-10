<?php 

/************************************************

               PHP INIT SECTION

************************************************/

/* make sure no-one can run anything here if they didn't arrive through 'proper channels' */
if(!defined("COMPACTCMS_CODE")) { define("COMPACTCMS_CODE", 1); } /*MARKER*/

/*
We're only processing form requests / actions here, no need to load the page content in sitemap.php, etc. 
*/
if (!defined('CCMS_PERFORM_MINIMAL_INIT')) { define('CCMS_PERFORM_MINIMAL_INIT', true); }


// Define default location
if (!defined('BASE_PATH'))
{
	$base = str_replace('\\','/',dirname(dirname(__FILE__)));
	define('BASE_PATH', $base);
}

// Load basic configuration
/*MARKER*/require_once(BASE_PATH . '/lib/config.inc.php');

// Load basic configuration
/*MARKER*/require_once(BASE_PATH . '/lib/includes/common.inc.php');




/************************************************

              START OF LOCAL CODE 

   (support functions have been loaded now)

************************************************/

/*
 * Load session if not already done by CCMS: this is mandatory for the form 
 * to work as it won't have the benefit of CCMS doing its prep work for it 
 * on form submission!
 */
if (empty($_SESSION))
{
	session_start();
}

// Check whether this is a send request
$action_type = getGETparam4IdOrNumber('do');

function POST2str($var, $def = '')
{
	// prevent PHP barfing a hairball in E_STRICT:
	if (!isset($_POST) || empty($_POST[$var]))
		return $def;
	return strval($_POST[$var]);
}

function SESSION2str($var, $def = '')
{
	// prevent PHP barfing a hairball in E_STRICT:
	if (!isset($_SESSION) || empty($_SESSION[$var]))
		return $def;
	return strval($_SESSION[$var]);
}

echo "<p>$action_type=='send' + ".!empty($_POST)." + ".!empty($_SESSION)." + ".POST2str('verification', 'x')==SESSION2str('ccms_captcha', 'y')."</p>"; 

// If the action type is equal to send, then continue
if($action_type=='send' 
	&& $_SERVER['REQUEST_METHOD'] == "POST" 
	&& POST2str('verification', 'x')==SESSION2str('ccms_captcha', 'y')) 
{
	$subject = getPOSTparam4EmailSubjectLine('subject');
	$message = getPOSTparam4EmailBody('message');
	$sender = getPOSTparam4HumanName('name');
	$emailaddress = getPOSTparam4Email('email');
	if (empty($emailaddress) || strcspn($emailaddress, '<"\'') != strlen($emailaddress))
	{
		// email filter allows quoted prefix before the '<' ; we DO NOT as we have both parts separated here...
		die("<p class=\"error center\">You specified an invalid email address</p>");
	}
	if (empty($sender) || strpos($sender, '"') !== false )
	{
		// ... nor do we allow a double-quote inside the 'human name' preceeding part of the address.
		die("<p class=\"error center\">You specified an invalid email sender name</p>");
	}
	/*
	We REQUIRE a FILLED subject line and message body as well:
	*/
	if (!empty($sender) && !empty($emailaddress) && !empty($subject) && !empty($message))
	{
		$headers = 'From: "' . $sender . '" <' . $emailaddress . ">\r\n";
		// To send HTML mail, the Content-type header must be set
		$headers .= 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

		$message = "<html>\n<head>\n<title>Email</title>\n</head>\n<body>\n" . $message . "\n</body>\n</html>\n";

		// See http://php.net/manual/en/function.mail.php Warnings
		$message = str_replace("\n.", "\n..", $message);
		
		ob_start();
			$result = mail("<YOUR_ADDRESS_HERE>",$subject,$message,$headers);
			$content = ob_get_contents();
		ob_end_clean();
		if($result) 
		{
			echo '<p class="success center">Your message has been sent. Thanks!</p>';
		} 
		else 
		{
			echo '<div class="error center"><p>Error while processing your e-mail:</p><p style="font-size:0.825em; 	line-height: 1.2em;">'.$content.'</p></div>';
		}
		exit();
	}
	else
	{
		die("<p class=\"error center\">You haven't specified either a valid email sender or a subject line or message body</p>");
	}
}
else 
{
	// destroy the session if it existed before: start a new session
	session_unset();
	session_destroy();
	session_regenerate_id();
	session_start();

	$_SESSION['ccms_captcha'] = mt_rand('123456','987654'); 
}

if($_SERVER['REQUEST_METHOD'] != "GET") 
{
	die("<p class=\"error center\">Invalid data. Nothing sent!</p>");
}

?>
<script type="text/javascript" charset="utf-8">
window.addEvent('domready', function(){
	// Do: set-up form send functionality
	function sendForm() {
		var myFx 	= 	new Fx.Tween($('status'), { duration:500 });
		var scroll	= 	new Fx.Scroll(window, { 
							wait: false, 
							duration: 500, 
							transition: Fx.Transitions.Quad.easeInOut 
						});
		var contactForm = new Request.HTML({
			url: './content/contact.php?do=send',
			method: 'post',
			update: 'response',
			data: $('contactForm'),
			onRequest: function() {
				myFx.start('opacity', 0,1);
				$('status').set('text','Form is being processed');
				$('status').set('class','notice');
			},
			onComplete: function(response) {
				myFx.start('opacity', 1,0.8);
				$('status').set('text','Form submitted');
				$('status').set('class','success');
				scroll.toElement('response');
			}
		}).send();
	}
	
	// Do: send contact form
	new FormValidator.Inline($('contactForm'), {
		onFormValidate: function(passed, form, event){
			event.stop();
			if (passed) sendForm();
		}
	});
});
</script>
<p>This is a simple contact form to show how you are able to code e.g. PHP code directly within the CCMS back-end. Feel free to modify the styling of this basic form to suit your websites' look &amp; feel. Don't forget to adjust the &lt;YOUR_ADDRESS_HERE&gt; line to your own e-mail address.</p>

<div id="status"><!-- spinner --></div>
<div id="response"><!-- spinner --></div>

<form action="" id="contactForm" method="post" accept-charset="utf-8">	
	<fieldset id="contact_form" class="">
		<legend>Contact form</legend>
		
		<label for="name">Your name</label><input type="text" name="name" value="" id="name" class="text required" /><br/><br/>
		<label class="clear" for="email">Your e-mail</label><input type="text" name="email" value="" id="email" class="text required validate-email" /><br/><br/>
		<label class="clear" for="subject">Subject</label><input type="text" name="subject" value="" id="subject" class="text required" /><br/><br/>
		<label class="clear" for="message">Message content</label><textarea name="message" id="message" class="minLength:10" rows="8" cols="40"></textarea>
		<p>And to check that this message isn't automated... Please re-enter <span style="font-weight:bold;color: #f00;"><?php echo $_SESSION['ccms_captcha']; ?></span>.</p>
		<label for="verification">Verification</label><input type="text" name="verification" maxlength="6" value="" id="verification" class="required validate-match matchInput:'captcha_check' matchName:'captcha' text"/><br/><br/>
		<input type="hidden" name="captcha_check" value="<?php echo $_SESSION['ccms_captcha']; ?>" id="captcha_check" />
		
		<p class="prepend-7"><button type="submit">Send e-mail &rarr;</button></p>
	</fieldset>
</form>																		