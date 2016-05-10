<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class ad {
    var $basedn = 'dc=cloudapp,dc=net';
    var $ldaprdn  = '';     // ldap rdn or dn //cn=admin,dc=cloudapp,dc=net
    var $ldappass = '';  // associated password //sal#1234

    var $ldahost = ''; //127.0.0.1
    var $ldapport = ''; //389
    var $ldapconn;
    
    function connect() {
        // connect to ldap server
        if($this->ldapport != '') {
            $this->ldapconn = ldap_connect($this->ldahost, $this->ldapport);
        } else {
            $this->ldapconn = ldap_connect($this->ldahost);
        }
        if ($this->ldapconn) {
            ldap_set_option($this->ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
            ldap_set_option($this->ldapconn, LDAP_OPT_REFERRALS, 0);
            
            return 1;
        }
        log_message('debug', "Could not connect to LDAP server.");
        return 0;
    }
    
    function bind() {
        // binding to ldap server
		if(!empty($this->ldaprdn)){
        $ldapbind = ldap_bind($this->ldapconn, $this->ldaprdn, $this->ldappass); ///

        // verify binding
        if ($ldapbind) {
            return 1;
        } else {
            log_message('debug', "LDAP bind failed...");
            return 0;
        }
		}
	
    }
    
    function searchAD($data) {
        $attributes = array();

        //$filter = "(cn=*)";
        $filter = '(&(employeenumber=' . $data['username'] . ')(userpassword=' . $data['password'] . '))';
        //$filter = "(cn=admin)";

        $result = ldap_search($this->ldapconn, $this->basedn, $filter, $attributes);

        $entries = ldap_get_entries($this->ldapconn, $result);
    //print_r($entries);
        if($entries["count"] > 0) {
            $cn = $entries[0]['cn'][0];
            $empno = ((isset($entries[0]['employeenumber']))?$entries[0]['employeenumber'][0]:'');

            $newdata = array(
                'login_name' => $cn,
                'login_id' => $empno,
                'login_ad' => TRUE, //ad - Active Directory
                'logged_in' => TRUE
            );
            
            return $newdata;
        } else {
            log_message('debug', "User not found...");
            return array();
        }
    }
}

?>