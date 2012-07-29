<?php

require_once 'tweet.class.php';

class Twitter{

	private $options = array(
		'cache' => 'classes/twitter.cache',
		'cache_timer' => 1,
		// 'cache_timer' => 10800,
		'url' => 'http://search.twitter.com/search.json',
		'query' => '',
		'user' => ''
	);

	function __construct( array $options ){
		$this->options = array_merge( $this->options, $options );
		if ( !$this->isCached() )
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
				'message' => $tweet->text,
				'author' => $tweet->from_user,
				'author_id' => $tweet->from_user_id
			));
		}
		return $tweets;
	}

	function fetch(){
		return unserialize( file_get_contents( $this->options['cache'] ) );
	}

	function buildQuery(){
		return $this->options['url'] . '?q=' . $this->options['query'] . '&from=' . $this->options['user'];
	}

	function display(){
		foreach ($this->fetch() as $tweet) {
			echo '<ul>';
			echo '	<li>' . $tweet->author . ' tweeted: </li>';
			echo '	<li>' . $tweet->message . '</li>';
			echo '</ul>';
		}
	}

}