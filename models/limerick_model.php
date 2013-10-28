<?php

class C_Limerick_Model
{
    //get a limerick
    function getEntry($limerickId)
    {
        $result = "";

        $result = new C_Limerick($limerickId);

        return $result;
    }

    //get the ten highest rated limerick entries
    function GetTenHighestRated()
    {
        $result = "";

        //instantiate the Database class
        $database = new C_Database();

        $database->OpenConnection();
        $result =  $database->GetTenHighestRated();
        $database->CloseConnection();

        return $result;
    }


    //get the ten most recently viewed limerick entries
    function GetTenMostRecent()
    {
        $result = "";

        //instantiate the Database class
        $database = new C_Database();

        $database->OpenConnection();
        $result =  $database->GetTenMostRecent();
        $database->CloseConnection();

        return $result;
    }

    //get a random limerick entry
    function GetRandomLimerickId()
    {
        $result = "";

        //instantiate the Database class
        $database = new C_Database();

        $database->OpenConnection();
        $result =  $database->GetRandomLimerickId();
        $database->CloseConnection();

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


    //update the user rating an entry
    function SubmitUserRating($rating, $userId, $limerickId)
    {
        //instantiate the Database class
        $database = new C_Database();

        $database->OpenConnection();
        $result =  $database->SubmitUserRating($rating, $userId, $limerickId);
        $database->CloseConnection();
    }
}

?>
