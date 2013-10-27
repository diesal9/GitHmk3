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

        if (strToLower($this->view) == "saveentry")
        {
            $title = $this->getRequestSetting($_REQUEST, "title");
            $text = $this->getRequestSetting($_REQUEST, "text");
            $author = $this->getRequestSetting($_REQUEST, "author");
            $limerickId = $limerickModel->saveEntry($text, $title, $author);
            $this->limerick = $limerickId;
            //echo "Limerick ID [" . $this->limerick . "]";
            $this->view = "nonloggedin";
        }

        if (strToLower($this->view) == "random")
        {
            $limerickId = $limerickModel->GetRandomLimerickId();
            $this->limerick = $limerickId;
            $this->view = "nonloggedin";
        }

        if (strToLower($this->view) == "setuserrating")
        {
            $rating = $this->getRequestSetting($_REQUEST, "r");
            $userId = $this->getRequestSetting($_REQUEST, "u");
            $limerickModel->
                SubmitUserRating($rating, $userId, $this->limerick);
            $this->view = "nonloggedin";
        }

        if (strToLower($this->view) == "limerick")
        {
            $data[0] = "Add New Limerick";
        }
        else
        {
            $limerickModel->UpdateViewedLimerick($this->limerick);
            $data[0] = $limerickModel->getEntry($this->limerick);
            $data[1] = $limerickModel->GetTenHighestRated();
            $data[2] = $limerickModel->GetTenMostRecent();




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
