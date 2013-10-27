<?php

//the name of the db user
global $username;
$username = "xxxxx";

//the password of the db user
global $password;
$password = "xxxxx";

//the name of the db server
global $server;
$server = "localhost";

//the name of the database on the server
global $databaseName;
$databaseName = "cbocage_hmk3";

//the name of the login session variable
global $userId;
$userId = "userId";

//an array of supported controllers
global $controllersSupported;
$controllersSupported = array("main");

//an array of supported views
global $viewsSupported;
$viewsSupported = array("nonloggedin", "limerick", "saveentry");

//the title of the blog site
global $siteTitle;
$siteTitle = "Looney Limericks";

//an array of files to include
global $filesToInclude;
$filesToInclude = array("classes/C_Hw3.inc", "classes/C_Database.inc", 
    "models/authenticate.php", "models/limerick_model.php", 
   "classes/C_Limerick.inc");

?>
