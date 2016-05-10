<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once __DIR__ . '/tcpdf/tcpdf_barcodes_2d.php';

class Tcpdfqrcode extends TCPDF2DBarcode {
    public function __construct($params) {
        $code = $params['code'];
        $type = $params['type'];
        parent::__construct($code, $type);
    }
}
