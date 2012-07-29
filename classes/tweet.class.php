<?php

class Tweet{

	public $message;
	public $message_id;
	public $author;
	public $author_id;
	public $author_dp;
	public $permalink;

	function __construct( array $data ){
		$this->message = $this->parseLinks( $data['message'] );
		$this->message_id = $data['message_id'];
		$this->author = $data['author'];
		$this->author_id = $data['author_id'];
		$this->author_dp = $data['author_dp'];
		$this->permalink = $this->permalink();
	}

	function permalink(){
		return 'https://www.twitter.com/' . $this->author . '/status/' . $this->message_id;
	}

	function parseLinks( $message ){
		$message = preg_replace( '/(http:\/\/[\w\/\-\.\?\&\#]+)/', '<a href="$1">$1</a>', $message );
		$message = preg_replace( '/@([\w]+)/', '<a href="https://www.twitter.com/$1">@$1</a>', $message );
		$message = preg_replace( '/#([\w]+)/', '<a href="https://www.twitter.com/search/%23$1">#$1</a>', $message );
		return $message;
	}

	function getAuthorLinked(){
		return '<a href="https://www.twitter.com/' . $this->author . '">' . $this->author . '</a>';
	}

}