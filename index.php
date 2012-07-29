<?php

include 'classes/twitter.class.php';

$twitter = new Twitter( array( 'cache_force' => true ) );

?>

<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title>Twitter Feed</title>
</head>
<body>
	<?php echo $twitter ?>
	<div class="clearfix"></div>
</body>
</html>