<?php

class TweetTicker_UserConfig {

    public static $CONSUMER_KEY = 'JEKA6OPJxoX7yzqqDMSculNM7';
    public static $CONSUMER_SECRET = 'Gv0Hfr8ErWroPK1cU0FIwK878XDPpGP21jIlayaboarA4f9sSS';
    public static $ACCESS_TOKEN = '';
    public static $ACCESS_SECRET = '';
    public static $CACHE_LENGTH = 60; // Time in seconds
    public static $WHITELIST_TIMELINE = array(
        // List of user accounts, enclosed in quotations and separated by commas
        'jaydhameliya10',
        'AlferovaYulya',
        'twitter',
        'twitterapi',
        'codecanyon',
    );
    public static $WHITELIST_SEARCH = array(
        // List of search queries, enclosed in quotations and separated by commas
        '#twitter',
        'a more #specific search term',
    );

}
