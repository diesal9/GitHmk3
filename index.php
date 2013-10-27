<?php

session_name("hw3");
session_start();

//setup BASE_DIR
defineBase_Dir();
//echo 'BASE_DIR is: ' . BASE_DIR . '<br />';

//the file we could not load
global $fileWeCouldNotLoad;

if (loadConfigurationFiles())
{
    //instantiate our main class
    $hw3 = new C_Hw3($_REQUEST);
    $hw3->showPage();
}
else
{
    echo $fileWeCouldNotLoad . " could not be loaded" . "<br />";
}

//define the base dir
function defineBase_Dir()
{
    define("BASE_DIR", substr($_SERVER["SCRIPT_FILENAME"], 0, 
        -strlen("index.php")));
}

//load the config file through require
function loadConfigurationFiles()
{
    $result = FALSE;

    //load configuration file
    $GLOBALS["fileWeCouldNotLoad"] = BASE_DIR . "config/config.php";

    if (file_exists($GLOBALS["fileWeCouldNotLoad"]))
    {
        require($GLOBALS["fileWeCouldNotLoad"]);

        //grab the array of files to include
        $arrayOfFilesToInclude = $GLOBALS["filesToInclude"];;

        $result = TRUE;

        foreach ($arrayOfFilesToInclude as $fileToInclude)
        {
            $GLOBALS["fileWeCouldNotLoad"] = BASE_DIR . $fileToInclude;
            if (file_exists($GLOBALS["fileWeCouldNotLoad"]))
            {
                require($GLOBALS["fileWeCouldNotLoad"]);
            }
            else
            {
                $result = FALSE;
                break;
            }
        }
    }

    return $result;
}

?>
