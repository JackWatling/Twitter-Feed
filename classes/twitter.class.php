<?php

class Twitter{

	private $options = array(
		'cache' => 'classes/twitter.cache',
		'cache_timer' => 10800,
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
		return file_exists( $this->options['cache'] ) && time() - filemtime( $this->options['cache'] ) < $this->options['cache_timer'];
	}

	function recache(){
		file_put_contents( $this->options['cache'] , file_get_contents( $this->buildQuery() ) );
	}

	function fetch(){
		return json_decode( file_get_contents( $this->options['cache'] ) )->results;
	}

	function buildQuery(){
		return $this->options['url'] . '?q=' . $this->options['query'] . '&from=' . $this->options['user'];
	}

	function display(){
		foreach ($this->fetch() as $tweet) {
			echo '<li>' . $tweet->text . '</li>';
		}
	}

}