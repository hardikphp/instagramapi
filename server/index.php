<?php

header('Content-Type: application/json');

if (!defined('OPENSSL_VERSION_NUMBER')) {
    tickerError('PHP does not have OpenSSL enabled.');
}

if (!function_exists('curl_version')) {
    tickerError('PHP does not have cURL enabled.');
}

if (!is_file('config.php')) {
    tickerError('Could not find configuration file');
}

require_once 'vendor/abraham/twitteroauth/twitteroauth/twitteroauth.php';
require_once 'src/ScrollingTweetTicker/FileCache.php';
require_once 'config.php';

function tickerError($error) {
    exit('{"errors": [{"message":"' . $error . '"}]}');
}

function getFromApi($theApi, $theParams) {
    $endpoint = null;

    if ($theApi === 'timeline') {
        $endpoint = 'statuses/user_timeline';
    } else if ($theApi === 'search') {
        $endpoint = 'search/tweets';
    } else {
        tickerError('Something went wrong');
    }

    $connection = new TwitterOAuth(
            TweetTicker_UserConfig::$CONSUMER_KEY, TweetTicker_UserConfig::$CONSUMER_SECRET, TweetTicker_UserConfig::$ACCESS_TOKEN, TweetTicker_UserConfig::$ACCESS_SECRET
    );

    $connection->host = "https://api.twitter.com/1.1/";

    return $connection->oAuthRequest($endpoint, 'GET', $theParams);
}

function getCachePath() {
    return dirname(__FILE__) . '/cache';
}

$cachePath = getCachePath();

if (!is_dir($cachePath)) {
    tickerError('The cache directory does not exist');
}

if (!is_writable($cachePath)) {
    tickerError('The cache directory is not writable');
}

if (!isset($_GET['api'])) {
    tickerError('Missing API');
}

$api = htmlspecialchars($_GET['api']);
$params = array();
$cacheKey = null;

if ($api === 'timeline') {
    if (!isset($_GET['screen_name'])) {
        tickerError('Missing screen name');
    }

    $screenName = $_GET['screen_name'];

    if (!in_array($screenName, TweetTicker_UserConfig::$WHITELIST_TIMELINE)) {
        tickerError('Screen name not allowed: ' . $screenName);
    }

    $params['screen_name'] = htmlspecialchars($screenName);

    $cacheKey = 'timeline_' . $params['screen_name'];
} else if ($api === 'search') {
    if (!isset($_GET['q'])) {
        tickerError('Missing search query');
    }

    $query = $_GET['q'];

    if (!in_array($query, TweetTicker_UserConfig::$WHITELIST_SEARCH)) {
        tickerError('Search query not allowed: ' . $query);
    }

    $params['q'] = htmlspecialchars($query);

    $cacheKey = 'search_' . $params['q'];
} else {
    tickerError('Invalid API: ' . $api);
}

$cache = new ScrollingTweetTicker_FileCache($cachePath);
$cached = $cache->get($cacheKey);

if ($cached === false) {
    $cached = getFromApi($api, $params);

    $cache->set($cacheKey, $cached, time() + TweetTicker_UserConfig::$CACHE_LENGTH);
}

header('Content-Type: application/json');
header('Expires: ' . gmdate('D, d M Y H:i:s', time() + TweetTicker_UserConfig::$CACHE_LENGTH) . ' GMT');
header("Pragma: cache");
header("Cache-Control: max-age=" . TweetTicker_UserConfig::$CACHE_LENGTH);

echo $cached;
