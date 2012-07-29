<?php

class Tweet{

	public $message;
	public $author;
	public $author_id;
	public $author_dp;

	function __construct( array $data ){
		$this->message = $this->parseLinks( $data['message'] );
		$this->author = $data['author'];
		$this->author_id = $data['author_id'];
		$this->author_dp = $data['author_dp'];
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