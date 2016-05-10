<?php
class MY_Loader extends CI_Loader {


    function  __construct() {
        parent::__construct();
        $CI =& get_instance();
        $CI->load = $this;
    }

    public function ext_view($view, $vars = array(), $return = FALSE){

       $_ci_ext = pathinfo($view, PATHINFO_EXTENSION);
       $view = ($_ci_ext == '') ? $view.'.php' : $view;

       return $this->_ci_load(array(
               '_ci_vars' =>   $this->_ci_object_to_array($vars),
               '_ci_path' => $view, '_ci_return' => $return));

    }
	function myview($folder, $view, $vars = array(), $return = FALSE) {
        $this->_ci_view_paths = array_merge($this->_ci_view_paths, array(APPPATH . '../' . $folder . '/' => TRUE));
        return $this->_ci_load(array(
                '_ci_view' => $view,
                '_ci_vars' => $this->_ci_object_to_array($vars),
                '_ci_return' => $return
        ));
}

}