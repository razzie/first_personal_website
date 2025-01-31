<?php
function relativeTime($time)
{
	$past = time() - strtotime($time);

	$minute = 60;
	$hour = $minute * 60;
	$day = $hour * 24;
	$week = $day * 7;
	$month = $day * 30;
	$year = $day * 365;

	if(!is_numeric($past) || $past < 0)
		return 'unknown time';

	if ($past < 3)
		return 'right now';
	else if ($past < $minute)
		return floor($past) . ' seconds ago';
	else if ($past < $minute * 2)
		return 'about one minute ago';
	else if ($past < $hour)
		return floor($past / $minute) . ' minutes ago';
	else if ($past < $hour * 2)
		return 'about one hour ago';
	else if ($past < $day)
		return floor($past / $hour) . ' hours ago';
	else if ($past < $day * 2)
		return 'yesterday';
	else if ($past < $week)
		return floor($past / $day) . ' days ago';
	else if ($past < $week * 2)
		return 'about a week ago';
	else if ($past < $month)
		return floor($past / $week) . ' weeks ago';
	else if ($past < $month * 2)
		return 'about a month ago';
	else if ($past < $year)
		return floor($past / $month) . ' months ago';
	else if ($past < $year * 2)
		return 'about a year ago';
	else
		return floor($past / $year) . ' years ago';
}


const UPDATE_AFTER_HOURS = 0.5;
const TWEETS_JSON = '../content/tweets_cache.json';

// time to update tweets
if (!file_exists(TWEETS_JSON) ||
	(time() - filemtime(TWEETS_JSON)) > (3600 * UPDATE_AFTER_HOURS))
{
	ob_start();
	include '../core/tweets.php';
	file_put_contents(TWEETS_JSON, ob_get_clean());
}

// read timeline from json
$twitter_feed = json_decode(file_get_contents(TWEETS_JSON), true);

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
		$text = str_replace("\n", " <br />", $tweet['text']);
		foreach($tweet['entities'] as $entity)
		{
			$text = str_ireplace($entity['pattern'],
				"<a href=\"{$entity['url']}\" target=\"_blank\">{$entity['display_text']}</a>", $text);
		}
		$timestamp = relativeTime($tweet['timestamp']);
		$flags = '';
		$mediabox = '';

		if (in_array('retweeted', $tweet['flags']))
		{
			$flags .= '<img src="image/twitter_retweet.png" alt="Retweet" /> ';
		}

		if (count($tweet['media']))
		{
			$mediabox = '<div>';
			foreach($tweet['media'] as $media)
			{
				$mediabox .= "
				<a href=\"{$media['url']}\" class=\"img-box\">
					<img src=\"{$media['url']}\" alt=\"Media\" class=\"media\"
						width=\"{$media['width']}\" height=\"{$media['height']}\" />
				</a>";
			}
			$mediabox .= '</div>';
		}

		echo "
		<div class=\"tweet\">
			<img src=\"{$tweet['user_avatar']}\" alt=\"Avatar\" class=\"avatar\" />
			<b>{$flags}{$tweet['user_name']}</b>
			<a href=\"http://twitter.com/{$tweet['user_id']}\" target=\"_blank\" class=\"action\">@{$tweet['user_id']}</a>
			<span>{$timestamp}</span>
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
			{$mediabox}
		</div>";
	}
}
?>
