<?php
require_once("twitteroauth.php"); //Path to twitteroauth library

$twitteruser = "gorzsony";
$notweets = 15;
$consumerkey = "";
$consumersecret = "";
$accesstoken = "";
$accesstokensecret = "";
$connection = new TwitterOAuth($consumerkey, $consumersecret, $accesstoken, $accesstokensecret);
$twitter_feed = $connection->get("https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=".$twitteruser."&count=".$notweets);
$result = array();

if(isset($twitter_feed['errors']))
{
	array_push($result, $twitter_feed);
}
else
{
	foreach($twitter_feed as $tweet_data)
	{
		$id = $tweet_data['id_str'];
		$timestamp = $tweet_data['created_at'];
		$entities = array();
		$media = array();
		$flags = array();
		
		// getting data of the original tweet
		if (isset($tweet_data['retweeted_status']))
		{
			array_push($flags, 'retweeted');
			$tweet_data = $tweet_data['retweeted_status'];
		}

		// # hashtag entities
		foreach($tweet_data['entities']['hashtags'] as $hashtag_data)
		{
			$new_entity = array(
				'pattern' => "#{$hashtag_data['text']}",
				'display_text' => "#{$hashtag_data['text']}",
				'url' => "http://twitter.com/hashtag/{$hashtag_data['text']}?src=hash",
			);
			array_push($entities, $new_entity);
		}
		
		// $ symbol entities
		foreach($tweet_data['entities']['symbols'] as $symbol_data)
		{
			$new_entity = array(
				'pattern' => "${$symbol_data['text']}",
				'display_text' => "${$symbol_data['text']}",
				'url' => "http://twitter.com/hashtag/{$symbol_data['text']}?src=ctag",
			);
			array_push($entities, $new_entity);
		}
		
		// @ user mentions
		foreach($tweet_data['entities']['user_mentions'] as $user_mention_data)
		{
			$new_entity = array(
				'pattern' => "@{$user_mention_data['screen_name']}",
				'display_text' => "@{$user_mention_data['screen_name']}",
				'url' => "http://twitter.com/{$user_mention_data['screen_name']}",
			);
			array_push($entities, $new_entity);
		}
		
		// URLs
		foreach($tweet_data['entities']['urls'] as $url_data)
		{
			$new_entity = array(
				'pattern' => $url_data['url'],
				'display_text' => $url_data['display_url'],
				'url' => $url_data['expanded_url'],
			);
			array_push($entities, $new_entity);
		}
		
		// media URLs
		if (isset($tweet_data['entities']['media']))
		{
			foreach($tweet_data['entities']['media'] as $url_data)
			{
				$new_entity = array(
					'pattern' => $url_data['url'],
					'display_text' => $url_data['display_url'],
					'url' => $url_data['expanded_url'],
				);
				array_push($entities, $new_entity);
			}
		}
		
		// media
		if (isset($tweet_data['extended_entities']) &&
			isset($tweet_data['extended_entities']['media']))
		{
			foreach($tweet_data['extended_entities']['media'] as $media_data)
			{
				$new_media = array(
					'id' => $media_data['id_str'],
					'type' => $media_data['type'],
					'url' => $media_data['media_url'],
					'width' => $media_data['sizes']['small']['w'],
					'height' => $media_data['sizes']['small']['h'],
				);
				array_push($media, $new_media);
			}
		}

		$tweet = array(
			'id' => $id,
			'text' => $tweet_data['text'],
			'timestamp' => $timestamp,
			'user_name' => $tweet_data['user']['name'],
			'user_id' => $tweet_data['user']['screen_name'],
			'user_avatar' => $tweet_data['user']['profile_image_url'],
			'entities' => $entities,
			'media' => $media,
			'flags' => $flags,
		);

		array_push($result, $tweet);
	}
}

echo json_encode($result);
/*
ini_set('xdebug.var_display_max_depth', 10);
var_dump($twitter_feed);
*/
?>
