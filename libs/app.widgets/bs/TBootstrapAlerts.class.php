<?php

interface iBootStrapAlerts {
    public function show($message, $type, $caption = "");
}

/**
 * Description of TBootStrapAlerts
 *
 * @author hudsonmartins
 */
class TBootStrapAlerts implements iBootStrapAlerts {
    
    /**
     * 
     * @param string $message
     * @param string $type
     * @param string $caption
     * @return string
     * uso: TBootStrapAlerts::showAlert("mensagem...", "danger")
     * uso: TBootStrapAlerts::showAlert("mensagem...", "danger", "Erro!")
     */
    public function show($message, $type, $caption = "") {
        $c = $caption;
        if (!empty($c)){
            $sAlert = "<div class='alert alert-{$type}' role='alert'><strong>{$c}</strong>&nbsp;{$message}</div>";
        }
        else {
            $sAlert = "<div class='alert alert-{$type}' role='alert'>{$message}</div>";
        }
        return $sAlert;
    }
}
