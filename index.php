<?php

include 'classes/twitter.class.php';

$twitter = new Twitter( array( 'query' => 'test' ) );

?>

<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title></title>
</head>
<body>
	<ul>
		<?php $twitter->display() ?>
	</ul>
</body>
</html>