<?php

class C_Database
{
    private $linkID = "";
    private $dbSelected = "";
    private $limerick = "";

    //C_Database constructor
    function __construct() { }

    //open a connection to the database
    function OpenConnection()
    {
        $this->linkID = new mysqli($GLOBALS["server"], $GLOBALS["username"], 
            $GLOBALS["password"], $GLOBALS["databaseName"]);
    }

    //close a connection to the database
    function CloseConnection()
    {
        $this->linkID->Close();
    }

    //create the user record in the database
    function CreateSessionUser()
    {
        $result = "";

        $query = "CALL CreateSessionUser()";
        $tempResult = $this->linkID->query($query);
        $result = $tempResult->fetch_row()[0];

        return $result;
    }

    //get a list of the limerick ids
    function getLimerickIds()
    {
        $result = array();

        $query = "CALL GetLimerickIds()";
        $tempResult = $this->linkID->query($query);
        while ($row = $tempResult->fetch_row()) 
        {
            array_push($result, $row[0]);
        }

        return $result;
    }

    //write a limerick to the database
    function saveEntry($text, $title, $author)
    {
        $result = "";

        $sessionId = intval($_SESSION[$GLOBALS["userId"]]);
        $text = mysqli_escape_string($this->linkID, $text);
        $title = mysqli_escape_string($this->linkID, $title);
        $author = mysqli_escape_string($this->linkID, $author);
        $query = 
            "CALL CreateLimerick('$text', '$title', '$author', $sessionId)";
        $tempResult = $this->linkID->query($query);
        $row = $tempResult->fetch_row(); 
        $result = $row[0];

        return $result;
    }

    //write a file to disk
    function getFeaturedLimerickID()
    {
        $result = "";

        $query = 
            "CALL GetFeaturedLimerick()";
        $tempResult = $this->linkID->query($query);
        $row = $tempResult->fetch_row();
        date_default_timezone_set('America/Los_Angeles');
        $featuredDateTime = new DateTime($row[4]);
        $tenMinutesAgo = new DateTime("now");
        $tenMinutesAgo->sub(new DateInterval('PT10M'));
        $result = intval($row[0]);

        if ($featuredDateTime < $tenMinutesAgo)
        {
            $oldID = $newID = intval($row[0]);
            while($oldID == $newID)
            {
                $query = 
                    "CALL GetRandomLimerick()";
                $this->OpenConnection();
                $tempResult = $this->linkID->query($query);
                $row = $tempResult->fetch_row();
                date_default_timezone_set('America/Los_Angeles');
                $featuredDateTime = new DateTime($row[4]);
                $tenMinutesAgo = new DateTime("now");
                $tenMinutesAgo->sub(new DateInterval('PT10M'));
                $newID = $result = intval($row[0]);
            }

            $query = 
                "CALL UpdateFeaturedLimerick($oldID, $newID)";
            $this->OpenConnection();
            $this->linkID->query($query);
        }

        return $result;
    }

    //get a limerick by Id
    function getLimerickById($limerickId)
    {
        $result = "";

        $limerickId = intval($limerickId);
        $query = "CALL GetLimerick($limerickId)";
        $result = $this->linkID->query($query);

        return $result;
    }

    //update the viewed date of an entry
    function UpdateViewedLimerick($limerickId)
    {
        $limerickId = intval($limerickId);
        $query = "CALL UpdateViewedLimerick($limerickId)";
        $result = $this->linkID->query($query);
    }

    //Get total average rating
    function GetAverageRating($limerickId)
    {
        $result = "";

        $limerickId = intval($limerickId);
        $query = "CALL GetAverageRating($limerickId)";
        $tempResult = $this->linkID->query($query);

        if ($tempResult->num_rows == 0)
        {
            $result = "0";
        }
        else
        {
            $row = $tempResult->fetch_row();

            if ($row[0] == NULL)
            {
                $result = "0";
            }
            else
            {
                $result = $row[0];
            }
        }

        return $result;
    }

    //Get total average rating
    function GetGetSessionUserRating($limerickId)
    {
        $result = "";

        $limerickId = intval($limerickId);
        $userId = intval($_SESSION[$GLOBALS["userId"]]);
        $query = "CALL GetSessionUserRating($limerickId, $userId)";
        $tempResult = $this->linkID->query($query);

        if ($tempResult->num_rows == 0)
        {
            $result = "0";
        }
        else
        {
            $row = $tempResult->fetch_row();

            if ($row[0] == NULL)
            {
                $result = "0";
            }
            else
            {
                $result = $row[0];
            }
        }

        return $result;
    }

    //get the ten highest rated limerick entries
    function GetTenHighestRated()
    {
        $result = "";

        $query = "CALL GetTenHighestRated()";
        $result = $this->linkID->query($query);

        return $result;
    }


    //get the ten most recently viewed limerick entries
    function GetTenMostRecent()
    {
        $result = "";

        $query = "CALL GetTenMostRecent()";
        $result = $this->linkID->query($query);

        return $result;
    }

    //get a random limerick entry
    function GetRandomLimerickId()
    {
        $result = "";

        $query = "CALL GetRandomLimerick()";
        $tempResult = $this->linkID->query($query);
        $row = $tempResult->fetch_row();
        $result = $row[0];

        return $result;
    }

    //update the user rating an entry
    function SubmitUserRating($rating, $userId, $limerickId)
    {
        $rating = floatval($rating);
        $userId = intval($userId);
        $limerickId = intval($limerickId);
        $query = "CALL SubmitUserRating($rating, $userId, $limerickId)";
        $result = $this->linkID->query($query);
    }
}

?>
