<?php 

// Define default location
if (!defined('BASE_PATH')) die('BASE_PATH not defined!');

send_response_status_header(403); 
?>
<p>You are not allowed to access <strong><?php echo $ccms['pagereq']; ?>.html</strong> at this moment.</p>