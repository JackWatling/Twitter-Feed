<?php

include 'classes/twitter.class.php';

$twitter = new Twitter( array( 'cache_force' => true ) );

?>

<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title></title>
</head>
<body>
	<?php echo $twitter ?>
</body>
</html>