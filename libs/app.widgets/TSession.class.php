<?php

/**
 * Description of TSession
 *
 * @author hudson
 * @version 1-17.10.2015
 */

class TSession {

    function __construct() {
        session_start();
    }

    /**
     * método setSValue
     * armazena a variavel de sessão.
     * @param array $sKey nome da variavel
     */
    function setSValue($sKey) {
        foreach ($sKey as $key => $value) {
            $_SESSION[$key] = $value;
        }
    }

    /**
     * método getSValue
     * retorna o valor da variavel da sessão
     * @param string $sKey nome da variavel
     * @return string
     */
    function getSValue($sKey) {
        foreach ($sKey as $key) {
            if (isset($_SESSION[$key])){
                echo $_SESSION[$key];
            }
        }
    }
    
    /**
     * método getSValue
     * retorna o valor da variavel da sessão
     * @param string $sKey nome da variavel
     * @return string
     */
    function get($sKey) {
        if (isset($_SESSION[$sKey])){
            return $_SESSION[$sKey];
        }
    }
    
    function getAll() {
        print_r($_SESSION);
    }

    function freeSession($sKey) {
        foreach ($sKey as $key) {
            if (isset($_SESSION[$key])) {
                unset($_SESSION[$key]);
            }
        }
    }

    /**
     * método closeSession
     * destroi os dados da sessão e a encerra. 
     */
    function closeSession() {
        unset($_SESSION);
        //$_SESSION = array();
        session_destroy();
    }
    
    /**
     * método getIdSession
     * Retorna o id da session
     */
    function getIdSession(){
        echo session_id();
    }
    
}

?>
