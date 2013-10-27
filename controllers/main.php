<?php

class C_Controller
{
    private $controller = "main";
    private $view = "";
    private $limerick = "";

    //C_Controller constructor
    function __construct($view, $limerick)
    {
        $this->view = $view;
        //echo "Our view is: " . $this->view . "<br />";

        $this->limerick = $limerick;
        //echo "Our limerick is: " . $this->limerick . "<br />";

        //process the user
        $this->ProcessLoginRequest();

    }

    //run the code to show the page requested
    function showPage()
    {
        //instantiate our limerick model class
        $limerickModel = new C_Limerick_Model();

        //instantiate the Database class
        $database = new C_Database();

        if (strToLower($this->view) == "saveentry")
        {
            $title = $this->getRequestSetting($_REQUEST, "title");
            $text = $this->getRequestSetting($_REQUEST, "text");
            $entryName = "";
            $limerickModel->saveEntry($title, $text, $entryName);
            $this->entry = $entryName;

            $pathToEntries = BASE_DIR . "entries/";
            $this->entries = 
                $fileSystem->getDirectories($pathToEntries);
            $this->view = "loggedin";
        }

        if (strToLower($this->view) == "limerick")
        {
            $data[0] = "Add New Limerick";
        }
        else
        {
            $data[0] = $this->entries;
        }

        //$data[1] = $entryModel->getEntry($this->entry);
        //$data[2] = $this->entry;
        //$data[3] = $fileSystem->getTitles(BASE_DIR . "entries/");
        //print_r($data);

        //load views file 
        $pathToViewsFile = BASE_DIR . "views/" . $this->view . ".php";
        if ($this->loadConfigurationFile($pathToViewsFile))
        {
            //instantiate our view model class
            $viewToShow = new C_View();
            $viewToShow->showPage($data);
        }
        else
        {
            echo $pathToViewsFile . " could not be loaded" . "<br />";
        }
   }

    //get a request setting
    function getRequestSetting($request, $setting)
    {
        $result = "";

        if (isset($request) && is_array($request) &&
            isset($setting) && $setting != "")
        {
            if (array_key_exists(strToLower($setting), $request))
            { 
                $result = $request[strToLower($setting)];
            }
            elseif (array_key_exists(strToUpper($setting), $request))
            { 
                $result = $request[strToUpper($setting)];
            }
        }

        return $result;
    }

    //process the login request
    function ProcessLoginRequest()
    {
         //instantiate our authenticate class
        $authenticate = new C_Authenticate();
        $authenticate->authenticateUser();
    }

    //load a file through require
    function loadConfigurationFile($path)
    {
        $result = FALSE;

        if (file_exists($path))
        {
            require($path);
            $result = TRUE;
        }

        return $result;
    }
}

?>