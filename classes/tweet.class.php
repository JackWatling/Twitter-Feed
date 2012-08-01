<?php

class Tweet{

	
	public static $base_url = 'https://www.twitter.com/';

	public static $show_display_picture = true;
	public static $standardise_time;

	public $tweet;
	public $tweet_id;
	public $tweet_time;
	public $author;
	public $author_id;
	public $author_dp;
	public $author_link;
	public $permalink;

	function __construct( array $data ){
		$this->tweet = $this->parseLinks( $data['tweet'] );
		$this->tweet_id = $data['tweet_id'];
		$this->tweet_time = $data['tweet_time'];
		$this->author = $data['author'];
		$this->author_id = $data['author_id'];
		$this->author_dp = $data['author_dp'];
		$this->author_link = $this->authorlink();
		$this->permalink = $this->permalink();
	}

	function time( $static = false ){
		$time = time() - strtotime( $this->tweet_time );

		if ( $time / (24 * 60 * 60) % 7 > 0 || $static  )
			return date( 'd/m/Y @ H:i', strtotime( $this->tweet_time ) );

		$time_string = '';
		$time_period = array(
			'hour' => $time / (60 * 60) % 24,
			'minute' => $time / 60 % 60,
			'second' => $time % 60);
		
		foreach ($time_period as $period => $v) {
			if ($v > 0){
				$time_string .= $v . ' ' . ( $v > 1 ? $period . 's' : $period );
				break;
			}
		}

		return $time_string . ' ago';
	}

	function permalink(){
		return self::$base_url . $this->author . '/status/' . $this->tweet_id;
	}

	function authorlink(){
		return self::$base_url . $this->author;
	}

	function parseLinks( $tweet ){
		$tweet = preg_replace( '/(http:\/\/[\w\/\-\.\?\&\#]+)/', '<a href="$1">$1</a>', $tweet );
		$tweet = preg_replace( '/@([\w]+)/', '<a href="https://www.twitter.com/$1">@$1</a>', $tweet );
		$tweet = preg_replace( '/#([\w]+)/', '<a href="https://www.twitter.com/search/%23$1">#$1</a>', $tweet );
		return $tweet;
	}

	function __toString(){
		return '<li class="tweet">
					<section class="info">
						<a href="' . $this->author_link . '">' . ( self::$show_display_picture ? '<img src="' . $this->author_dp . '">' : '' ) . $this->author . '</a>
						<a class="date" href="' . $this->permalink . '">' . $this->time( self::$standardise_time ) . '</a>
					</section>
					<p>' . $this->tweet . '</p>
				</li>';
	}

}