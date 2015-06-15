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
    foreach($twitter_feed as $data)
	{
		$id = $data['id_str'];
		$created = $data['created_at'];
		$retweeted = isset($data['retweeted_status']);
		if ($retweeted)
			$data = $data['retweeted_status'];

		$tweet = array(
			'id' => $id,
			'text' => $data['text'],
			'created_at' => $created,
			'user_name' => $data['user']['name'],
			'user_screen_name' => $data['user']['screen_name'],
			'user_profile_image_url' => $data['user']['profile_image_url'],
		);

		if ($retweeted)
			$tweet['retweeted'] = true;

		if (isset($data['extended_entities']))
			$tweet['extended_entities'] = $data['extended_entities'];

		array_push($result, $tweet);
    }
}

echo json_encode($result);
?>
