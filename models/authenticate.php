<?php

class C_Authenticate
{
    //authenticate the user
    function authenticateUser()
    {
        if (isset($_COOKIE[$GLOBALS["userId"]]))
        {
            $_SESSION[$GLOBALS["userId"]] = $_COOKIE[$GLOBALS["userId"]];
        }
        else
        {
            $database = new C_Database();
            $database->OpenConnection();
            $userId = $database->CreateSessionUser();
            $database->CloseConnection();
            setcookie($GLOBALS["userId"], $userId, time()+ 3600);  /* 1 hour */
            $_SESSION[$GLOBALS["userId"]] = $userId;
        }
    }
}

?>
