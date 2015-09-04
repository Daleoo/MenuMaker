<?php
namespace App;

use App\App;
use Psr\Log\AbstractLogger;

class Logger extends AbstractLogger {

    public function log($level, $message, array $context = array()) {
        $file = fopen("Logs".DS.$level.".log", "a+");
        fputs($file,date("d-m-Y H:i:s")." ");
        fputs($file,$_SERVER['REMOTE_ADDR']." ");
        fputs($file,$message);
        fputs($file,"\r\n");
        fclose($file);
    }
}

?>
