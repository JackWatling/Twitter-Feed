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

	function time( $static = false ){
		$time = time() - strtotime( $this->message_time );

		if ( $time / (24 * 60 * 60) % 7 > 0 || $static  )
			return date( 'd/m/Y @ H:i', strtotime( $this->message_time ) );

		$increments = array(
				'hour' => $time / (60 * 60) % 24,
				'minute' => $time / 60 % 60,
				'second' => $time % 60);

		$time_string = '';
		foreach ($increments as $inc => $val) {
			if ($val > 0){
				$time_string .= $val . ' ' . ( $val > 1 ? $inc . 's' : $inc );
				break;
			}
		}

		return $time_string . ' ago';
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