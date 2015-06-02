<?php

defined('INTERNAL') || die();

$string['accessdenied'] = 'Access Denied';
$string['accessdeniedexception'] = 'You do not have access to view this page';
$string['artefactnotfound'] = 'Artefact with id %s not found';
$string['artefactnotfoundmaybedeleted'] = 'Artefact with id %s not found (maybe it has been deleted already?)';
$string['artefactpluginmethodmissing'] = 'Artefact plugin %s must implement %s and doesn\'t';
$string['artefacttypeclassmissing'] = 'Artefact types must all implement a class.  Missing %s';
$string['artefacttypemismatch'] = 'Artefact type mismatch, you are trying to use this %s as a %s';
$string['artefacttypenametaken'] = 'Artefact type %s is already taken by another plugin (%s)';
$string['blockconfigdatacalledfromset'] = 'Configdata should not be set directly, use PluginBlocktype::instance_config_save instead';
$string['blockinstancednotfound'] = 'Block instance with id %s not found';
$string['blocktypelibmissing'] = 'Missing lib.php for block %s in artefact plugin %s';
$string['blocktypemissingconfigform'] = 'Block type %s must implement instance_config_form';
$string['blocktypenametaken'] = 'Block type %s is already taken by another plugin (%s)';
$string['blocktypeprovidedbyartefactnotinstallable'] = 'This will be installed as part of the installation of artefact plugin %s';
$string['classmissing'] = 'class %s for type %s in plugin %s was missing';
$string['couldnotmakedatadirectories'] = 'For some reason some of the core data directories could not be created. This should not happen, as Mahara previously detected that the dataroot directory was writable. Please check the permissions on the dataroot directory.';
$string['curllibrarynotinstalled'] = 'Your server configuration does not include the curl extension. Mahara requires this for Moodle integration and to retrieve external feeds. Please make sure that curl is loaded in php.ini, or install it if it is not installed.';
$string['datarootinsidedocroot'] = 'You have set up your data root to be inside your document root. This is a large security problem, as then anyone can directly request session data (in order to hijack other peoples\' sessions), or files that they are not allowed to access that other people have uploaded. Please configure the data root to be outside of the document root.';
$string['datarootnotwritable'] = 'Your defined data root directory, %s, is not writable. This means that neither session data, user files nor anything else that needs to be uploaded can be saved on your server. Please make the directory if it does not exist, or give ownership of the directory to the web server user if it does.';
$string['dbconnfailed'] = 'Mahara could not connect to the application database.

 * If you are using Mahara, please wait a minute and try again
 * If you are the administrator, please check your database settings and make sure your database is available

The error received was:
';
$string['dbnotutf8'] = 'You are not using a UTF-8 database. Mahara stores all data as UTF-8 internally. Please drop and re-create your database using UTF-8 encoding.';
$string['dbversioncheckfailed'] = 'Your database server version is not new enough to successfully run Mahara. Your server is %s %s, but Mahara requires at least version %s.';
$string['domextensionnotloaded'] = 'Your server configuration does not include the dom extension. Mahara requires this in order to parse XML data from a variety of sources.';
$string['gdextensionnotloaded'] = 'Your server configuration does not include the gd extension. Mahara requires this in order to perform resizes and other operations on uploaded images. Please make sure that it is loaded in php.ini, or install it if it is not installed.';
$string['interactioninstancenotfound'] = 'Activity instance with id %s not found';
$string['invaliddirection'] = 'Invalid direction %s';
$string['invalidviewaction'] = 'Invalid view control action: %s';
$string['jsonextensionnotloaded'] = 'Your server configuration does not include the JSON extension. Mahara requires this in order to send some data to and from the browser. Please make sure that it is loaded in php.ini, or install it if it is not installed.';
$string['magicquotesgpc'] = 'You have dangerous PHP settings, magic_quotes_gpc is on. Mahara is trying to work around this, but you should really fix it';
$string['magicquotesruntime'] = 'You have dangerous PHP settings, magic_quotes_runtime is on. Mahara is trying to work around this, but you should really fix it';
$string['magicquotessybase'] = 'You have dangerous PHP settings, magic_quotes_sybase is on. Mahara is trying to work around this, but you should really fix it';
$string['missingparamblocktype'] = 'Try selecting a block type to add first';
$string['missingparamcolumn'] = 'Missing column specification';
$string['missingparamid'] = 'Missing id';
$string['missingparamorder'] = 'Missing order specification';
$string['mysqldbextensionnotloaded'] = 'Your server configuration does not include the mysql extension. Mahara requires this in order to store data in a relational database. Please make sure that it is loaded in php.ini, or install it if it is not installed.';
$string['notartefactowner'] = 'You do not own this artefact';
$string['notfound'] = 'Not Found';
$string['notfoundexception'] = 'The page you are looking for could not be found';
$string['onlyoneblocktypeperview'] = 'Cannot put more than one %s blocktype into a view';
$string['onlyoneprofileviewallowed'] = 'You are only allowed one profile view';
$string['parameterexception'] = 'A required parameter was missing';
$string['pgsqldbextensionnotloaded'] = 'Your server configuration does not include the pgsql extension. Mahara requires this in order to store data in a relational database. Please make sure that it is loaded in php.ini, or install it if it is not installed.';
$string['phpversion'] = 'Mahara will not run on PHP < 5.1.3. Please upgrade your PHP version, or move Mahara to a different host.';
$string['registerglobals'] = 'You have dangerous PHP settings, register_globals is on. Mahara is trying to work around this, but you should really fix it';
$string['safemodeon'] = 'Your server appears to be running safe mode. Mahara does not support running in safe mode. You must turn this off in either the php.ini file, or in your apache config for the site.

If you are on shared hosting, it is likely that there is little you can do to get safe_mode turned off, other than ask your hosting provider. Perhaps you could consider moving to a different host.';
$string['sessionextensionnotloaded'] = 'Your server configuration does not include the session extension. Mahara requires this in order to support users logging in. Please make sure that it is loaded in php.ini, or install it if it is not installed.';
$string['unknowndbtype'] = 'Your server configuration references an unknown database type. Valid values are "postgres8" and "mysql5". Please change the database type setting in config.php.';
$string['unrecoverableerror'] = 'A nonrecoverable error occurred.  This probably means that you have encountered a bug in the system.';
$string['unrecoverableerrortitle'] = '%s - Site Unavailable';
$string['versionphpmissing'] = 'Plugin %s %s is missing version.php!';
$string['viewnotfound'] = 'View with id %s not found';
$string['viewnotfoundexceptionmessage'] = 'You tried to access a view that didn\'t exist!';
$string['viewnotfoundexceptiontitle'] = 'View not found';
$string['xmlextensionnotloaded'] = 'Your server configuration does not include the %s extension. Mahara requires this in order to parse XML data from a variety of sources. Please make sure that it is loaded in php.ini, or install it if it is not installed.';
$string['youcannotviewthisusersprofile'] = 'You cannot view this user\'s profile';
