<?php
namespace Nanothink\Tinder;
/**
 * 
 * @author Nam Hoang <nam@nano-think.com>
 * @link http://github.com/namhoang/tinder-api
 * @package Nanothink\Tinder
 *
 */
class TinderApi
{
    const API_URL = 'https://api.gotinder.com';
    
    /**
     * Tinder API authentication token
     * @var string
     */
    private $authToken = '';
    
    /**
     * Constructor
     * 
     * @param string $facebookId
     * @param string $facebookToken
     * @return void
     */
    public function __construct($facebookId, $facebookToken)
    {
        $this->requestAuthToken($facebookId, $facebookToken);
    }
    
    /**
     * Request authorization token
     * @return void
     */
    private function requestAuthToken($facebookId, $facebookToken)
    {
        $options  = array(CURLOPT_URL => self::API_URL . '/auth',
                          CURLOPT_POST => 1,
                          CURLOPT_POSTFIELDS => '{"facebook_token": "' . $facebookToken . '", "facebook_id": "' . $facebookId . '"}');
        
        $response = $this->curlExec($options);
        
        if(!$response->token){
            throw new Exception('Authentication token denied.  Please check facebook credentials.');
        }
        
        $this->authToken = $response->token;
    }
    
    /**
     * Set Auth Token
     * @param string $authToken
     * @return void
     */
    public function setAuthToken ($authToken)
    {
        $this->authToken = $authToken;
    }

	/**
     * Get Auth Token
     * 
     * @return string
     */
    private function getAuthToken()
    {
        return $this->authToken;
    }
    
    /**
     * Get list of recommended user matches
     * 
     * @return array
     */
    public function getRecs()
    {
        $options = array(CURLOPT_URL => self::API_URL . '/user/recs');
        
        $response = $this->curlExec($options);
        
        return $response->results;
     }
    
    /**
     * Like a user
     * 
     * @param string $userId
     * @return string
     */
    public function like($userId)
    {
        $options = array(CURLOPT_URL => self::API_URL . "/like/$userId");
        
        return $this->curlExec($options);
    }
    
    /**
     * Get user profile
     * 
     * @param string $userId
     * @return string
     */
    public function getUserProfile($userId)
    {
        $options = array(CURLOPT_URL => self::API_URL . "/user/$userId");
        
        return $this->curlExec($options);
    }
    
    /**
     * Execute curl request
     * 
     * @param array $options
     * @return stdClass $response
     */
    private function curlExec($options = array())
    {
        $ch = curl_init();
        curl_setopt($ch,
                    CURLOPT_HTTPHEADER,
                    array('X-Auth-Token: ' . $this->getAuthToken(),
                          'Content-type: application/json',
                          'app_version: 3',
                          'platform: ios',
                          'User-Agent: Tinder/3.0.4 (iPhone; iOS 7.1; Scale/2.00)',
                          'os_version: 700001'));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
        
        // set additional options
        curl_setopt_array($ch, $options);
        
        $response = json_decode(curl_exec($ch));

        curl_close($ch);
        
        return $response;
    }
}