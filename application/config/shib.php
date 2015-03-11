<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|------Configuration option for shibboleth authentication
*/

$config['shibVarPrefix'] = '';
$config['shibAdminGroup'] = '';
$config['shibActive'] = false;

/*
 *------Array of users authorized to change pw suffix
 */

 $config['can_pw'] = array();
 
 /*
  *-----Array of user glids that can view/use admin functions of sidebar
  */
 
 $config['can_admin_sidebar'] = array();
