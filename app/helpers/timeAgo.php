<?php


// Definindo o fuso horário
date_default_timezone_set('America/Sao_Paulo');

function last_seen($datetime) {
    $timestamp = strtotime($datetime);   
    $strTime = array("segundo", "minuto", "hora", "dia", "mês", "ano");
    $length = array("60","60","24","30","12","10");

    $currentTime = time();
    if ($currentTime >= $timestamp) {
        $diff = $currentTime - $timestamp;
        for ($i = 0; $diff >= $length[$i] && $i < count($length)-1; $i++) {
            $diff = $diff / $length[$i];
        }

        $diff = round($diff);
        if ($diff == 0) {
            return 'Ativo Agora';
        } else if ($diff > 1) {
            return $diff . " " . $strTime[$i] . "s atrás";
        } else {
            return $diff . " " . $strTime[$i] . " atrás";
        }
    }
}

// Exemplo de uso:
// echo last_seen('2024-04-14 19:42:00');
?>