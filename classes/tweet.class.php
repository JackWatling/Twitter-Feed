<?php

class Tweet{

	public $message;
	public $message_id;
	public $message_time;
	public $author;
	public $author_id;
	public $author_dp;
	public $permalink;

	function __construct( array $data ){
		$this->message = $this->parseLinks( $data['message'] );
		$this->message_id = $data['message_id'];
		$this->message_time = $data['message_time'];
		$this->author = $data['author'];
		$this->author_id = $data['author_id'];
		$this->author_dp = $data['author_dp'];
		$this->author_link = $this->authorlink();
		$this->permalink = $this->permalink();
	}

	function time(){
		$time = time() - strtotime( $this->message_time );
		$days = $time / (24 * 60 * 60) % 7;
		$hours = $time / (60 * 60) % 24;
		$minutes = $time / 60 % 60;
		$seconds = $time % 60;


		if ( $days > 0 )
			return date( 'd/m/Y @ h:i', strtotime( $this->message_time ) );
		else if ( $hours > 0 )
			return $hours . ' hour' . ($hours != 1 ? 's' : '') . ' ago';
		else if ( $minutes > 0 )
			return $minutes . ' minute' . ($minutes != 1 ? 's' : '') . ' ago';
		else if ( $seconds > 0 )
			return $seconds . ' second' . ($seconds != 1 ? 's' : '') . ' ago';
	}

	function permalink(){
		return 'https://www.twitter.com/' . $this->author . '/status/' . $this->message_id;
	}

	function authorlink(){
		return 'https://www.twitter.com/' . $this->author;
	}

	function parseLinks( $message ){
		$message = preg_replace( '/(http:\/\/[\w\/\-\.\?\&\#]+)/', '<a href="$1">$1</a>', $message );
		$message = preg_replace( '/@([\w]+)/', '<a href="https://www.twitter.com/$1">@$1</a>', $message );
		$message = preg_replace( '/#([\w]+)/', '<a href="https://www.twitter.com/search/%23$1">#$1</a>', $message );
		return $message;
	}

}