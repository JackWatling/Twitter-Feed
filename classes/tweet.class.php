<?php

class Tweet{

	public $message;
	public $author;
	public $author_id;

	function __construct( array $data ){
		$this->message = $data['message'];
		$this->author = $data['author'];
		$this->author_id = $data['author_id'];

		$this->parseLinks();
	}

	function parseLinks(){
		$this->message = preg_replace( '/(http:\/\/[\w\/\-\.\?\&\#]+)/', '<a href="$1">$1</a>', $this->message );
		$this->message = preg_replace( '/@([\w]+)/', '<a href="https://www.twitter.com/$1">@$1</a>', $this->message );
		$this->message = preg_replace( '/#([\w]+)/', '<a href="https://www.twitter.com/search/%23$1">#$1</a>', $this->message );
	}

}