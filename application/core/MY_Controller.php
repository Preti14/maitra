<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * MY_Controller
 *
 * Project specific controller
 *
 * @author		Rathina
 * @copyright	2012
 * @version		1.0
 * @access		public
 */
class MY_Controller extends CI_Controller {

    // html header variables
    public $title;
    public $keywords;
    public $description;
    // template specific dynamic variables
    public $current_menu;
    public $current_inner_menu;
    public $doc_heading;
    public $breadcrumbs;
    public $template_msg;
    public $template_err;
    public $content;
    public $login_content;
    public $print_view = FALSE;
    // js and css specific variables
    public $onload_js;
    public $internal_css;
    public $internal_js;
    public $_menuArray;
    public $nav;

    /**
     * MY_Controller::MY_Controller()
     *
     * Constructor
     *
     */
    public function __construct() {

        parent::__construct();
        $this->ci = & get_instance();

        $this->ci->load->helper('url');
        $this->ci->load->helper('date');

        $this->ci->load->helper('common');
        $this->load->helper('form');
        $this->fetch_divisions();
        $this->current_settings();
        $this->get_address_count();
        
        $search_subj = $this->session->userdata('search_subj');
        if (isset($_GET['c']) && $_GET['c'] != 'search' && isset($search_subj)) {
            $this->session->unset_userdata('search_subj');
        }
    }

    /**
     * MY_Controller::template_view()
     *
     * Loads the given view into the content variable, and
     * loads the template given
     *
     * @access	protected
     * @param 	string	view		filename of the view you want to load in the template
     * @param	string	template	filename of the template you want to load. defaults to template
     * @return	void
     */
    protected function template_view($view, $template = "template") {



        $this->content = $this->load->view($view, $this, true);

        $subMenu = array("Home" => array("Main" => "home/"));


        $this->nav_list = $this->_menuArray;


        $this->load->view($template, $this);
    }

    /**
     * MY_Controller::inner_template_view()
     *
     * Loads the given view into the content variable, and
     * loads the template given
     *
     * @access	protected
     * @param 	string	view
     * @param	string	inner_template
     * @return	void
     */
    protected function login_template_view($view,
            $inner_template = "login_template") {


        $this->content = $this->load->view($view, $this, true);
        $this->template_view($inner_template, "login_template");
    }

    public function object_to_array($data) {
        if (is_array($data) || is_object($data)) {
            $result = array();
            foreach ($data as $key => $value) {
                $result[$key] = $this->object_to_array($value);
            }
            return $result;
        }
        return $data;
    }

    function fetch_divisions() {
        $this->load->model('division', 'dv');
        $this->load->model('inmails', 'inm');
        $this->divisions = $this->dv->fetchdivision();
        $usertype = $this->session->userdata('check_login');
		/*//        if ($usertype["login_type"] != 2) { */
        if ($usertype["login_type"] == 1 || $usertype["login_type"] == 3 || $usertype["login_type"] == 4) {
            $this->search_history = $this->inm->list_today_search();
        }
      	//  $searchTemplateNameArray = 
         /*       do_action('list_all_search_template_name.plugin', $usertype);
		if(isset($searchTemplateNameArray['result'])){
        $this->search_fts_templates = $searchTemplateNameArray['result'];
		}*/
    }

    function current_settings() {
        $this->load->model('settings', 'set');
        $this->settings = $this->set->current_settings();
    }

    function get_address_count() {
        $this->load->model('settings', 'set');
        $row = $this->set->get_address_count();
        if ($row['temporary_addr_count'] != '')
            define('TEMP_ADDRESS_COUNT', $row['temporary_addr_count']);
        else
            define('TEMP_ADDRESS_COUNT', 50);
    }

}

?>
