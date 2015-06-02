<?php

defined('INTERNAL') || die();

$string['addauthority'] = 'Add an Authority';
$string['application'] = 'Application';
$string['authname'] = 'Authority name';
$string['cannotremove'] = 'We can\'t remove this auth plugin, as it\'s the only 
plugin that exists for this institution.';
$string['cannotremoveinuse'] = 'We can\'t remove this auth plugin, as it\'s being used by some users.
You must update their records before you can remove this plugin.';
$string['cantretrievekey'] = 'An error occurred while retrieving the public key from the remote server.<br>Please ensure that the Application and WWW Root fields are correct, and that networking is enabled on the remote host.';
$string['changepasswordurl'] = 'Password-change URL';
$string['editauthority'] = 'Edit an Authority';
$string['errnoauthinstances'] = 'We don\'t seem to have any authentication plugin instances configured for the host at %s';
$string['errnoxmlrpcinstances'] = 'We don\'t seem to have any XMLRPC authentication plugin instances configured for the host at %s';
$string['errnoxmlrpcwwwroot'] = 'We don\'t have a record for any host at %s';
$string['errorcertificateinvalidwwwroot'] = 'This certificate claims to be for %s, but you are trying to use it for %s.';
$string['errorcouldnotgeneratenewsslkey'] = 'Could not generate a new SSL key. Are you sure that both openssl and the PHP module for openssl are installed on this machine?';
$string['errornotvalidsslcertificate'] = 'This is not a valid SSL Certificate';
$string['host'] = 'Hostname or address';
$string['hostwwwrootinuse'] = 'WWW root already in use by another institution (%s)';
$string['ipaddress'] = 'IP Address';
$string['name'] = 'Site name';
$string['noauthpluginconfigoptions'] = 'There are no configuration options associated with this plugin';
$string['nodataforinstance'] = 'Could not find data for auth instance ';
$string['parent'] = 'Parent authority';
$string['port'] = 'Port number';
$string['protocol'] = 'Protocol';
$string['requiredfields'] = 'Required profile fields';
$string['requiredfieldsset'] = 'Required profile fields set';
$string['saveinstitutiondetailsfirst'] = 'Please save the institution details before configuring authentication plugins.';
$string['shortname'] = 'Short name for your site';
$string['ssodirection'] = 'SSO direction';
$string['theyautocreateusers'] = 'They auto-create users';
$string['theyssoin'] = 'They SSO in';
$string['unabletosigninviasso'] = 'Unable to sign in via SSO';
$string['updateuserinfoonlogin'] = 'Update user info on login';
$string['updateuserinfoonlogindescription'] = 'Retrieve user info from the remote server and update your local user record each time the user logs in.';
$string['weautocreateusers'] = 'We auto-create users';
$string['weimportcontent'] = 'We import content';
$string['weimportcontentdescription'] = '(some applications only)';
$string['wessoout'] = 'We SSO out';
$string['wwwroot'] = 'WWW root';
$string['xmlrpccouldnotlogyouin'] = 'Sorry, could not log you in :(';
$string['xmlrpcserverurl'] = 'XML-RPC Server URL';
