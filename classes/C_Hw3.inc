<?php

class C_Hw3
{
    private $controller = "";
    private $view = "";
    private $limerick = "";

    // the C_Hw3 constructor
    function __construct($request)
    {
        //process the controller
        $this->controller = $this->getControllerRequested($request);
        //echo "Our controller is: " . $this->controller . "<br />";

        $this->view = $this->getViewRequested($request);
        //echo "Our view is: " . $this->view . "<br />";

        $this->limerick = $this->getLimerickRequested($request);
        //echo "Our limerick is: " . $this->limerick . "<br />";
    }

    //show the page
    function showPage()
    {
        //load controller file
        $pathToControllerFile = BASE_DIR . "controllers/" . $this->controller . 
            ".php";
        if ($this->loadConfigurationFile($pathToControllerFile))
        {
            $c_controller = new C_Controller($this->view, $this->limerick);
            $c_controller->showPage();
        }
        else
        {
            echo $pathToControllerFile . " could not be loaded" . "<br />";
        }
    }

    //get the controller requested
    function getControllerRequested($request)
    {
        $result = "";

        $controllerRequested = $this->getRequestSetting($request, "c");
        $controllersSupported = 
            $this->getConfigurationSetting("controllersSupported");

        return $result = 
            $this->confirmValue
                ($controllerRequested, $controllersSupported) == "" 
            ? $controllersSupported[0]
            : $this->confirmValue($controllerRequested, $controllersSupported);
    }

    //get the view requested
    function getViewRequested($request)
    {
        $result = "";

        $viewsSupported = $this->getConfigurationSetting("viewsSupported");

        $viewRequested = $this->getRequestSetting($request, "view");

        $result = 
            $this->confirmValue($viewRequested, $viewsSupported) == "" 
            ? $viewsSupported[0]
            : $this->confirmValue($viewRequested, $viewsSupported);

        return $result;
    }

    //get the limerick requested
    function getLimerickRequested($request)
    {
        $result = "";

        $limericks = $this->getConfigurationSetting("limericks");

        $limerickRequested = $this->getRequestSetting($request, "l");

        $result = 
            $this->confirmValue($limerickRequested, $limericks) == "" 
            ? NULL
            : $this->confirmValue($limerickRequested, $limericks);

        return $result;
    }

    //make sure the value passed is valid
    function confirmValue($settingToConfirm, $arrayToSearch)
    {
        $result = "";

        if (isset($arrayToSearch) && is_array($arrayToSearch) &&
            isset($settingToConfirm) && $settingToConfirm != "")
        {
            foreach($arrayToSearch as $confirmedSetting) 
            {
                if (strToLower($settingToConfirm) == 
                    strToLower($confirmedSetting))
                {
                    $result = strToLower($settingToConfirm);
                    break;
                }
            }
        }

        return $result;
    }

    //get the value from the request string
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

    // get a configuration setting
    function getConfigurationSetting($setting)
    {
        $result = "";

        if ($setting != "")
        {
            switch(strToLower($setting))
            {
                case "controllerssupported":
                    //comes from the config file
                    $result = $GLOBALS["controllersSupported"];
                    break;
                case "viewssupported":
                    //comes from the config file
                    $result = $GLOBALS["viewsSupported"];
                    break;
                case "limericks":
                    //instantiate the database class
                    $database = new C_Database();

                    $database->OpenConnection();
                    $result = $database->getLimerickIds();
                    $database->CloseConnection();
                    break;
            }
        }

        return $result;
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
