<?php
/*
    PufferPanel - A Minecraft Server Management Panel
    Copyright (c) 2013 Dane Everitt
 
    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.
 
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
 
    You should have received a copy of the GNU General Public License
    along with this program.  If not, see http://www.gnu.org/licenses/.
 */
/*
 * Core PufferPanel API
 * Built for WHMCS Integration
 */

if(!isset($_POST)){ exit('Invalid API Call.'); }

/*
 * Include Core PufferPanel Functions
 */
require_once('../../core/framework/framework.core.php');
require_once('../../core/framework/api/api.core.php');

/*
 * Include API Functions
 */
include('functions/funct_coreServer.php');
include('functions/funct_provisionServer.php'); //funct=pserver
include('functions/funct_suspendServer.php'); //funct=suspend
include('functions/funct_terminateServer.php'); //funct=delserver

$api->function = new api_main();
$api->function->p = new api_provision();
$api->function->s = new api_suspend();

/*
 * Verify Connection is Allowed
 */
if(in_array($_SERVER['REMOTE_ADDR'], $_API_INFO['API_ALLOW_CONNECTION_IPS'])){ 

	/*
	 * API Authentication
	 */
	if(isset($_POST['uname']) && $_POST['uname'] == API_USERNAME){
	
		/*
		 * Check Password
		 */
		if(isset($_POST['pword']) && $_POST['pword'] == API_PASSWORD_HASH){
		
			if(isset($_POST['function'])){
			
				switch($_POST['function']){
				
					case 'provision':
						echo $api->function->p->provision($_POST['vars']); #This data is JSON Encoded
						break;
					case 'suspend':
						echo $api->function->s->suspend($_POST['vars']);
						break;
					case 'unsuspend':
						echo $api->function->s->unsuspend($_POST['vars']);
						break;
					case 'terminate':
						echo $api->function->terminate($_POST['vars']);
						break;
					default:
						echo json_encode(array('success' => false, 'error' => 'No valid function provided.'));
						break;
				
				}
			
			}else{
			
				exit(json_encode(array('success' => false, 'error' => 'No function provided.')));
			
			}
		
		}else{
		
			exit(json_encode(array('success' => false, 'error' => 'No password supplied or password was incorrect.')));
		
		}
	
	}else{
	
		exit(json_encode(array('success' => false, 'error' => 'No username supplied or username was incorrect.')));
		
	}
	
}else{

	exit(json_encode(array('success' => false, 'error' => 'This IP is not authorized to access the PufferPanel API.')));

}

?>