<?php 

// Define default location
if (!defined('BASE_PATH')) die('BASE_PATH not defined!');

send_response_status_header(404); 

?>
<p>The requested file <strong><?php echo $_GET['page']; ?>.html</strong> could not be found.</p>