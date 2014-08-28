<?php
namespace AMH\MyBlogBundle\Util;

/**
 * Class GeoDetector
 * @package AMH\MyBlogBundle\Util
 * @author Alexander Horkun mindkilleralexs@gmail.com
 */
class GeoDetector {
    /**
     * @param int $ip IP address.
     * @return null|string
     * @throws \InvalidArgumentException
     */
    public function byIp($ip){
        if(filter_var($ip, FILTER_VALIDATE_IP)){
            $result=file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip);
            if($result){
                $resultJson=json_decode($result, TRUE);
                if(array_key_exists('geoplugin_countryCode', $resultJson)){
                    return strtolower($resultJson['geoplugin_countryCode']);
                }
            }
        }
        else{
            throw new \InvalidArgumentException("Given ip address '$ip' is not a valid IP");
        }
        return null;
    }
} 