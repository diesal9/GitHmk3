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

        $this->limerick = $limerick;

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
            $valid = FALSE;
            $formText = $this->getRequestSetting($_REQUEST, "text");
            $formTextLastWords = "";
            $formTextSplit = explode("\n", $formText);

            for ($i = 0; $i < count($formTextSplit); $i++)
            {
                if (strlen($formTextSplit[$i]) > 0)
                {
                    $fSplit = explode(" ", $formTextSplit[$i]);

                    if (strlen($formTextLastWords) == 0)
                    {
                        $formTextLastWords = 
                            $fSplit[count($fSplit) - 1];
                    }
                    else
                    {
                        $formTextLastWords = $formTextLastWords . " " .
                            $fSplit[count($fSplit) - 1];
                    }
                }
            }

            $formTextLastWordsSplit = explode(" ", $formTextLastWords);
            if (count($formTextLastWordsSplit) == 5)
            {
                if (metaphone($formTextLastWordsSplit[0]) == 
                    metaphone($formTextLastWordsSplit[1]) &&
                    metaphone($formTextLastWordsSplit[0]) == 
                    metaphone($formTextLastWordsSplit[4]) &&
                    metaphone($formTextLastWordsSplit[1]) == 
                    metaphone($formTextLastWordsSplit[4]) &&
                    metaphone($formTextLastWordsSplit[2]) == 
                    metaphone($formTextLastWordsSplit[3]))
                    {
                        $valid = TRUE;
                    }
            }

            if ($valid)
            {
                $title = $this->getRequestSetting($_REQUEST, "title");
                $text = $this->getRequestSetting($_REQUEST, "text");
                $author = $this->getRequestSetting($_REQUEST, "author");
                $limerickId = $limerickModel->saveEntry($text, $title, $author);
                $this->limerick = $limerickId;
                $this->view = "nonloggedin";
            }
            else
            {
                echo '<script type="text/javascript"> alert("' . 
                    'The limerick is not in the correct form of AABBA' . 
                    '") </script>';
                $this->view = "limerick";
            }
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
            $data[1] = $this->getRequestSetting($_REQUEST, "title");
            $data[2] = $this->getRequestSetting($_REQUEST, "text");
            $data[3] = $this->getRequestSetting($_REQUEST, "author");
        }
        else
        {
            $data[0] = $limerickModel->getEntry($this->limerick);
            $data[1] = $limerickModel->GetTenHighestRated();
            $data[2] = $limerickModel->GetTenMostRecent();
        }

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
