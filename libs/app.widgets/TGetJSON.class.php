<?php
/**
 * Description of TGetJSON
 *
 * @author hudsonl
 */

interface iGetJSON {
    public static function getJSON($nameResult, $data = array());
    public static function getJSONData($nameResult, $data = array());
    public static function toJSON($code, $message);
}

class TGetJSON implements iGetJSON {
    /**
     * 
     * @param string $nameResult
     * @param array $data
     * @return json
     */
    public static function getJSON($nameResult, $data = array()) {
        return json_encode(array($nameResult => array($data)));
    }
    
    /**
     * 
     * @param string $nameResult
     * @param array $data
     * @return json
     * 
     */
    public static function getJSONData($nameResult, $data = array()) {
        return json_encode(array($nameResult => $data));
    }
    
    /**
     * 
     * @param int $code
     * @param string $message
     * @return json
     */
    public static function toJSON($code, $message){
        $msg = array("COD"=>$code, "MSG"=>$message);
        return json_encode($msg);
    }
    
}

?>
