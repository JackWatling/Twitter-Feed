<!-- Author: Jack Watling -->
<!--   Date: July, 2012   -->

<?php

include 'classes/twitter.class.php';

$twitter = new Twitter( array( 'cache_force' => true, 'query' => 'hello world' ) );

?>

<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title>Hello World Tweets</title>
	<link rel="stylesheet" href="fonts/stylesheet.css">
	<link rel="stylesheet" href="css/style.css">
</head>
<body>

	<h1>Hello World Tweets</h1>
	<?php echo $twitter ?>

	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>

	<script type="text/javascript">
	(function(){

		$('section.meta').hide();

		$('span.break').on( 'click', function(){
			$(this).next().slideToggle( 300 );
		});

	})();
	</script>

</body>
</html>