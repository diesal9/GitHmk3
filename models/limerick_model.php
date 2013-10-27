<?php

class C_Limerick_Model
{
    //get a limerick
    function getEntry($entry)
    {
        $result = "";

        $limerick = new C_Database($entry);
        $result = $limerick;

        return $result;
    }

    //create a limerick entry
    function saveEntry($text, $title, $author)
    {
        $result = FALSE;

        //instantiate the Database class
        $database = new C_Database();

        $database->OpenConnection();
        $result =  $database->saveEntry($text, $title, $author);
        $database->CloseConnection();

        return $result;
    }
}

?>
