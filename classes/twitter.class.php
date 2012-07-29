<?php

class Twitter{

	private $options = array(
		'url' => 'http://search.twitter.com/search.json',
		'query' => '',
		'user' => ''
	);

	function __construct( array $options ){
		$this->options = array_merge( $this->options, $options );
	}

	function fetch(){
		return json_decode( file_get_contents( $this->buildQuery() ) )->results;
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