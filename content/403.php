<?php 

// Define default location
if (!defined('BASE_PATH')) die('BASE_PATH not defined!');

send_response_status_header(403); 
?>
<p>You are not allowed to access <strong><?php echo $_GET['page']; ?>.html</strong> at this moment.</p>