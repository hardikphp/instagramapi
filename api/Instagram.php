<?php
namespace milan\api\instagram;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Instagram
 *
 * @author Jay Dhameliya < Milan Solution >
 */
class Instagram {

    /**
     * The API base URL
     */
    const API_URL = 'https://api.instagram.com/v1/';

    private $userId;
    private $accessToken;

    public function __construct($config = array()) {
        if (is_array($config)) {
            // if you want to access user data
            $this->setUserId($config['userID']);
            $this->setAccessToken($config['accessToken']);
        } else {
            throw new InstagramException("Error: __construct() - Configuration data must be in array");
        }
    }

    private function __callurl($url) {
        $url = self::API_URL . $url;
//        $url = "https://api.instagram.com/v1/users/1835595469/media/recent/?count=4&access_token=1835595469.1677ed0.119131d5ce8c43b7b6041ebb4a6b2d30";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    public function getUserMedia($count = 5) {
        $url = "users/{$this->getUserId()}/media/recent/?count={$count}&access_token={$this->getAccessToken()}";
        $result = $this->__callurl($url);
        return json_decode($result);
    }

    /**
     * @param string $tag 
     * @return Object Object of response
     */
    public function getTagMedia($tag){
        $url = "tags/{$tag}/media/recent/?access_token={$this->getAccessToken()}";
        $result = $this->__callurl($url);
        return json_decode($result);
        
    }
    
    public function getUserId() {
        return $this->userId;
    }

    public function getAccessToken() {
        return $this->accessToken;
    }

    public function setUserId($userId) {
        $this->userId = $userId;
    }

    public function setAccessToken($accessToken) {
        $this->accessToken = $accessToken;
    }

}
