<?php

require_once 'tweet.class.php';

class Twitter{

	private $options = array(
		'cache' => 'classes/twitter.cache',
		'cache_timer' => 10800,
		'cache_force' => false,
		'url' => 'http://search.twitter.com/search.json',
		'query' => '',
		'user' => 'notch',
		'limit' => 10
	);

	function __construct( array $options ){
		$this->options = array_merge( $this->options, $options );
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
				'message' => $tweet->text,
				'author' => $tweet->from_user,
				'author_id' => $tweet->from_user_id,
				'author_dp' => $tweet->profile_image_url
			));
		}
		return $tweets;
	}

	function fetch(){
		return unserialize( file_get_contents( $this->options['cache'] ) );
	}

	function buildQuery(){
		return 	$this->options['url'] . '?'
				. (isset( $this->options['query'] ) ? 'q=' . $this->options['query'] : '')
				. (isset( $this->options['user'] ) ? '&from=' . $this->options['user'] : '')
				. (isset( $this->options['limit'] ) ? '&rpp=' . $this->options['limit'] : '');
	}

	function __toString(){
		$display = '<ul>';
		foreach ($this->fetch() as $tweet) {
			$display .=
			'<li>
				<ul>
					<img src="' . $tweet->author_dp . '">
					<li>' . $tweet->getAuthorLinked() . ' tweeted: </li>
					<li>' . $tweet->message . '</li>
				</ul>
			</li>';
		}
		return $display . '</ul>';
	}

}