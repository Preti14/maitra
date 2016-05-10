<?php
ini_set('display_errors', 1);
    // using ldap bind
$ldaprdn  = 'cn=admin,dc=cloudapp,dc=net';     // ldap rdn or dn
//$ldaprdn  = 'saladmin@cloudapp.net';
$ldappass = 'sal#1234';  // associated password

$ldahost = '127.0.0.1';
$ldapport = ''; //389

// connect to ldap server
if($ldapport != '') {
    $ldapconn = ldap_connect($ldahost, $ldapport)
    or die("Could not connect to LDAP server.");
} else {
    $ldapconn = ldap_connect($ldahost)
    or die("Could not connect to LDAP server.");
}
ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);

if ($ldapconn) {

    // binding to ldap server
    $ldapbind = ldap_bind($ldapconn, $ldaprdn, $ldappass);
    //$ldapbind = ldap_bind($ldapconn);

    // verify binding
    if ($ldapbind) {
        echo "LDAP bind successful..." . "\n";
    } else {
        //echo "LDAP bind failed...";
        die("LDAP bind failed...");
    }
    
    $dn = "dc=cloudapp,dc=net";
       
    $attributes = array();

    $filter = "(cn=*)";
    //$filter = "(&(employeenumber=0247)(givenname=sharma))";
    
    $result = ldap_search($ldapconn, $dn, $filter, $attributes);

    $entries = ldap_get_entries($ldapconn, $result);
print_r($entries);
    for ($i=0; $i<$entries["count"]; $i++)
    {
        echo $entries[$i]["displayname"]
             [0]."(".$entries[$i]["l"][0].")<br />";
    }
    
    // Close the connection
    ldap_unbind($ldapconn);
}
?>