<!-- Author: Jack Watling -->
<!--   Date: July, 2012   -->

<?php

class Tweet{

	//Base Twitter url
	public static $base_url = 'https://www.twitter.com/';

	//Options for tweet displaying
	public static $standardise_time = true;
	public static $show_display_picture = true;
	public static $show_tweet_meta = true;

	//Passed to constructor
	private $tweet;
	private $tweet_id;
	private $tweet_time;
	private $author;
	private $author_id;
	private $author_dp;

	//Generated within class
	private $author_link;
	private $permalink;

	//Constructs a Tweet object, no data can be omitted
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

	//Returns either a 'static' time in the format, DD/MM/YYYY @ HH:MM; or a contextual time in the format, 4 hours ago
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

	//Creates a permalink to the Tweet
	function permalink(){
		return self::$base_url . $this->author . '/status/' . $this->tweet_id;
	}

	//Creates a permalink to the author of the Tweet
	function authorlink(){
		return self::$base_url . $this->author;
	}

	//Uses regex to parse the Tweet message and replace links, user mentions and hashtags with the appropriate links
	function parseLinks( $tweet ){
		$tweet = preg_replace( '/(http:\/\/[\w\/\-\.\?\&\#]+)/', '<a href="$1">$1</a>', $tweet );
		$tweet = preg_replace( '/@([\w]+)/', '<a href="https://www.twitter.com/$1">@$1</a>', $tweet );
		$tweet = preg_replace( '/#([\w]+)/', '<a href="https://www.twitter.com/search/%23$1">#$1</a>', $tweet );
		return $tweet;
	}

	//A magic function, overrides the toString method, and allows the tweet to display itself
	function __toString(){
		return '<li class="tweet">
					<section class="info">
						<a href="' . $this->author_link . '">' . ( self::$show_display_picture ? '<img src="' . $this->author_dp . '">' : '' ) . $this->author . '</a>
						<a class="date" href="' . $this->permalink . '">' . $this->time( self::$standardise_time ) . '</a>
					</section>
					<p>' . $this->tweet . '</p>
					' . ( !self::$show_tweet_meta ? '' :
					'<span class="break">...</span>
					<section class="meta">
						<ul>
							<li>Author: <a href="' . $this->author_link . '">' . $this->author . '</a></li>
							<li>Posted: <a href="' . $this->permalink . '">' . $this->time( true ) . '</a></li>
							<li>Perma: <a href="' . $this->permalink . '">' . $this->permalink . '</a></li>
						</ul>
					</section>' ) .
					'</li>';
	}

}