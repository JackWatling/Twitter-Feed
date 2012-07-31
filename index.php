<?php

include 'classes/twitter.class.php';

// $twitter = new Twitter( array( 'cache_force' => true, 'ignore' => 'lol' ) );
$twitter = new Twitter( array( 'cache_force' => true ) );

?>

<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title>My Tweets</title>
	<link rel="stylesheet" href="fonts/stylesheet.css">
	<link rel="stylesheet" href="css/style.css">
</head>
<body>

	<h1>My Tweets</h1>
	<?php echo $twitter ?>

</body>
</html>