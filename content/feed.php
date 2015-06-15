<!--
<style>
	iframe[id^='twitter-widget-']
	{
		width: 100% !important;
		margin: 0px 10px 0px 100px;
	}
</style>
<section>
	<a class="twitter-timeline"
	 href="https://twitter.com/gorzsony"
	 data-widget-id="605770186646421504"
	 data-chrome="noheader nofooter noborders transparent"
	 data-tweet-limit="10">
	Tweets by @gorzsony
	</a>
</section>
<script>
	!function(d,s,id)
	{
		var js;
		var fjs = d.getElementsByTagName(s)[0];
		var p = /^http:/.test(d.location) ? 'http' : 'https';
		if(!d.getElementById(id))
		{
			js = d.createElement(s);
			js.id = id;
			js.src = p + "://platform.twitter.com/widgets.js";
			fjs.parentNode.insertBefore(js, fjs);
		}

		if (typeof(twttr) != 'undefined')
			twttr.widgets.load();
	}
	(document, "script", "twitter-wjs");
</script>
-->
<?php
ob_start();
include '../core/tweets.php';
$twitter_feed = json_decode(ob_get_clean(), true);
	
function linkifyTweet($tweet)
{
	//Convert urls to <a> links
	$tweet = preg_replace("/([\w]+\:\/\/[\w-?&;#~=\.\/\@]+[\w\/])/",
		"<a href=\"$1\" target=\"_blank\">$1</a>",
		$tweet);

	//Convert hashtags to twitter searches in <a> links
	$tweet = preg_replace("/#([A-Za-z0-9\/\.]*)/",
		"<a href=\"http://twitter.com/search?q=$1\" target=\"_blank\">#$1</a>",
		$tweet);

	//Convert attags to twitter profiles in &lt;a&gt; links
	$tweet = preg_replace("/@([A-Za-z0-9\/\.]*)/",
		"<a href=\"http://www.twitter.com/$1\" target=\"_blank\">@$1</a>",
		$tweet);
	
	return $tweet;
}

if(isset($twitter_feed['errors']))
{
    foreach($twitter_feed['errors'] as $error)
	{
        echo "({$error['code']}) {$error['message']}<br />";
    }
}
else
{
    foreach($twitter_feed as $tweet)
	{
		$text = linkifyTweet($tweet['text']);
		
        echo "
		<div class=\"tweet\">
			<img src=\"{$tweet['user_profile_image_url']}\" alt=\"Avatar\" class=\"avatar\" />
			<span>{$tweet['user_name']}</span>
			<a href=\"http://twitter.com/{$tweet['user_screen_name']}\" target=\"_blank\" class=\"action\">@{$tweet['user_screen_name']}</a>
			<p>{$text}</p>
			<a href=\"http://twitter.com/intent/tweet?in_reply_to={$tweet['id']}\" target=\"_blank\" class=\"action\">
				<img src=\"image/twitter_reply.png\" alt=\"Reply\" />
				Reply
			</a>
			<a href=\"http://twitter.com/intent/retweet?tweet_id={$tweet['id']}\" target=\"_blank\" class=\"action\">
				<img src=\"image/twitter_retweet.png\" alt=\"Retweet\" />
				Retweet
			</a>
			<a href=\"http://twitter.com/intent/favorite?tweet_id={$tweet['id']}\" target=\"_blank\" class=\"action\">
				<img src=\"image/twitter_favorite.png\" alt=\"Favorite\" />
				Favorite
			</a>
		</div>";
    }
}
?>
