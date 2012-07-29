<?php

class Tweet{

	public $message;
	public $author;
	public $author_id;

	function __construct( array $data ){
		$this->message = $data['message'];
		$this->author = $data['author'];
		$this->author_id = $data['author_id'];
	}

}