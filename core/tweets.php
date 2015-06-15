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
		$tweet = array(
			'id' => $data['id_str'],
			'text' => $data['text'],
			'created_at' => $data['created_at'],
			'user_name' => $data['user']['name'],
			'user_screen_name' => $data['user']['screen_name'],
			'user_profile_image_url' => $data['user']['profile_image_url'],
		);
		
		array_push($result, $tweet);
    }
}

echo json_encode($result);
?>
