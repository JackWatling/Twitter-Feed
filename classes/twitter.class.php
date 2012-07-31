<?php

require_once 'tweet.class.php';

class Twitter{

	private $options = array(
		'debug' => false,
		'cache' => 'classes/twitter.cache',
		'cache_timer' => 10800,
		'cache_force' => false,
		'url' => 'http://search.twitter.com/search.json',
		'query' => '',
		'user' => '',
		'type' => 'recent',
		'ignore' => '',
		'limit' => 50,
		'standardise_time' => true
	);

	function __construct( array $options ){
		$this->options = array_merge( $this->options, $options );
		Tweet::$standardise_time = $this->options['standardise_time'];
		if ( !$this->isCached() || $this->options['cache_force'] )
			$this->recache();
	}

	function isCached(){
		return file_exists( $this->options['cache'] ) && time() - filemtime( $this->options['cache'] ) < $this->options['cache_timer'] && filesize( $this->options['cache'] ) > 0;
	}

	function recache(){
		file_put_contents( $this->options['cache'] , serialize( $this->remap( json_decode( file_get_contents( $this->buildQuery() ) )->results ) ) );
	}

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

	function fetch(){
		return unserialize( file_get_contents( $this->options['cache'] ) );
	}

	function invalidQuery(){
		return $this->options['user'] == '' && $this->options['query'] == '';
	}

	function buildQuery(){
		if ( $this->invalidQuery() ){
			echo '<center><p>Invalid query supplied, ensure that a query or user is supplied, or both. Search defaulted to \'hello world\'.</p></center>';
			$this->options['query'] = 'hello%20world';
		}

		$queries = array(
			'q' => $this->options['query'],
			'-' => $this->options['ignore'],
			'from' => $this->options['user'],
			'results_type' => $this->options['type'],
			'rpp' => $this->options['limit']);

		$query_string = $this->options['url'];
		$query_first = true;

		foreach ($queries as $key => $query) {
			if ( $query != '' ){
				$query_string .= ( $query_first ? '?' : '&' ) . (($key != '-' ) ? $key . '=' . $query : ( $queries['q'] == '' ? 'q=' : '' ) . $key . $query);
				$query_first = false;
			}
				
		}
		return str_replace(' ', '%20', $query_string );
	}

	function __toString(){
		$display = '<ul class="twitter">';
		foreach ($this->fetch() as $tweet) {
			$display .= $tweet;
		}
		return $display . '</ul>';
	}

}