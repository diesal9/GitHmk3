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

    //create a blog entry
    function saveEntry($title, $text, &$entryNameOut)
    {
        $result = FALSE;

        //instantiate our blog entry class
        $blogEntryPath = BASE_DIR . "entries/";
        $blogEntry = new C_BlogEntry($blogEntryPath);
        $result = 
            $blogEntry->saveEntry($blogEntryPath, $title, $text, $entryNameOut);

        return $result;
    }
}

?>
