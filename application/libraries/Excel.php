<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 *  =======================================
 *  Author     : Salzer
 *  License    : Protected
 *  Email      : salzer@gmail.com
 *
 *  
 *  =======================================
 */
require_once APPPATH."/third_party/PHPExcel.php"; 
 
class Excel extends PHPExcel {
    public function __construct() {
        parent::__construct();
    }
}
?>