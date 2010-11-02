<?php 

/* make sure no-one can run anything here if they didn't arrive through 'proper channels' */
if(!defined("COMPACTCMS_CODE")) { die('Illegal entry point!'); } /*MARKER*/

// Check whether this is a send request
$action_type = getGETparam4IdOrNumber('do');

// If the action type is equal to send, then continue
if($action_type=='send' && !empty($_POST) && $_POST['verification']==$_SESSION['ccms_captcha']) 
{
	$subject = $_POST['subject'];
	$message = $_POST['message'];
	$headers = 'From: '.$_POST['name'].' <'.$_POST['email'].'>' . "\r\n";
	if(mail("<YOUR_ADDRESS_HERE>",$subject,$message,$headers)) 
	{
		echo "<p class=\"success center\">Your message has been sent. Thanks!</p>";
		exit();
	} 
	else 
		die("<p class=\"error center\">Error while processing your e-mail</p>");
}
else 
{
	$_SESSION['ccms_captcha'] = mt_rand('123456','987654'); 
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
			update: 'status',
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
				scroll.toElement('status');
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