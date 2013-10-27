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
        $this->linkID = new mysqli($GLOBALS["server"], $GLOBALS["username"], $GLOBALS["password"], $GLOBALS["databaseName"]);
    }

    //close a connection to the database
    function CloseConnection()
    {
        $this->linkID->Close();
    }

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

    //write a file to disk
    function writeFile($path, $text)
    {
        if (file_exists(dirname($path)))
        {
            $fp=fopen($path, "w+");

            foreach($text as $value)
            {
                fwrite($fp, $value ."\r\n");
            }

            fclose($fp);
        }
    }

    //delete a file from disk
    function deleteFile($path)
    {
        if (file_exists($path))
        {
            unlink($path);
        }
    }

    //get the directories of a directory
    function getDirectories($path)
    {
        $result = array("mostrecent");

        if (file_exists($path))
        {
            $directories = scandir($path);
            foreach($directories as $directory) 
            {
                if (is_dir($path . $directory) && $directory != "." &&
                    $directory != "..")
                {
                    array_unshift($result, $directory);
                }
            }
        }

        return $result;
    }

    //create a directory
    function writeDirectory($path)
    {
        mkdir($path);
    }

    //delete a directory
    function deleteDirectory($path)
    {
        if (file_exists($path))
        {
            rmdir($path);
        }
    }

    //get the first line of each blog.txt file
    function getTitles($path)
    {
        $result = array();

        $directories = $this->getDirectories($path);
        foreach($directories as $directory) 
        {
            if (strToLower($directory) == "mostrecent")
            {
                $directory = "0";
            }
            $lines = $this->readAllLines($path . $directory . "/blog.txt");
            array_push($result, $lines[0]);
        }

        return $result;
    }
}

?>
