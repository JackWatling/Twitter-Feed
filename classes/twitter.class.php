<!-- Author: Jack Watling -->
<!--   Date: July, 2012   -->

<?php

require_once 'tweet.class.php';

class Twitter{

	//Base Twitter search rl
	const URL = 'http://search.twitter.com/search.json';

	//Twitter options
	private $options = array(
		'cache' => 'classes/twitter.cache',
		'cache_timer' => 10800,
		'cache_force' => false,
		'query' => '',
		'user' => '',
		'type' => 'recent',
		'ignore' => '',
		'limit' => 50,
		'standardise_time' => true,
		'show_display_picture' => true,
		'show_tweet_meta' => false
	);

	//Constructs a Twitter object
	function __construct( array $options ){
		$this->options = array_merge( $this->options, $options );

		Tweet::$standardise_time = $this->options['standardise_time'];
		Tweet::$show_display_picture = $this->options['show_display_picture'];
		Tweet::$show_tweet_meta = $this->options['show_tweet_meta'];

		if ( !$this->isCached() || $this->options['cache_force'] )
			$this->recache();
	}

	//Checks to see if the file exists, and is its creation is under the cache_timer value
	function isCached(){
		return file_exists( $this->options['cache'] ) && time() - filemtime( $this->options['cache'] ) < $this->options['cache_timer'] && filesize( $this->options['cache'] ) > 0;
	}

	//Query the Twitter Search API, remap the values to Tweet objects, and store them in a cache file
	function recache(){
		file_put_contents( $this->options['cache'] , serialize( $this->remap( json_decode( file_get_contents( $this->buildQuery() ) )->results ) ) );
	}

	//Loops by reference to replace the result objects supplied by the API with custom Tweet objects
	function remap( $tweets ){
		foreach ($tweets as &$tweet) {
			$tweet = new Tweet( array(
				'tweet' => $tweet->text,
				'tweet_id' => $tweet->id_str,
				'tweet_time' => $tweet->created_at,
				'author' => $tweet->from_user,
				'author_id' => $tweet->from_user_id_str,
				'author_dp' => $tweet->profile_image_url
			));
		}
		return $tweets;
	}

	//Fetches the Tweet objects from the cache file
	function fetch(){
		return unserialize( file_get_contents( $this->options['cache'] ) );
	}

	//An invalid query is present when both a query and user are omitted
	function invalidQuery(){
		return $this->options['user'] == '' && $this->options['query'] == '';
	}

	//Loops through the options to build a query string, which is then supplied to the Twitter Search API
	function buildQuery(){
		if ( $this->invalidQuery() )
			$this->options['query'] = 'hello%20world';

		$queries = array(
			'q' => $this->options['query'],
			'-' => $this->options['ignore'],
			'from' => $this->options['user'],
			'results_type' => $this->options['type'],
			'rpp' => $this->options['limit']);

		$query_string = self::URL;
		$query_first = true;

		foreach ($queries as $key => $query) {
			if ( $query != '' ){
				$query_string .= ( $query_first ? '?' : '&' ) . (($key != '-' ) ? $key . '=' . $query : ( $queries['q'] == '' ? 'q=' : '' ) . $key . $query);
				$query_first = false;
			}
				
		}
		return str_replace(' ', '%20', $query_string );
	}

	//A magic function, overrides the default toString property, this will be called when a Twitter object is echoed to the screen
	function __toString(){
		$display = '<ul class="twitter">';
		foreach ($this->fetch() as $tweet) {
			$display .= $tweet;
		}
		return $display . '</ul>';
	}

}