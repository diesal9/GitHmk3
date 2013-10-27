<?php

class C_BlogEntry
{
    public $contents = array();
    public $comments = array();

    //C_BlogEntry constructor
    function __construct($pathToEntry)
    {
        $this->contents = $this->getContents($pathToEntry);
        $this->comments = $this->getComments($pathToEntry);
    }

    //get the contents of the blog entry
    function getContents($pathToEntry)
    {
        $result = array();

        $fullPathToEntry = $pathToEntry . "blog.txt";
        if (file_exists($fullPathToEntry))
        {
            //instantiate the file system class
            $fileSystem = new C_FileSystem();

            //get an array of lines from the most recent blog contents
            $result = $fileSystem->readAllLines($fullPathToEntry);
        }

        return $result;
    }

    //get the comments of the blog entry
    function getComments($pathToEntry)
    {
        $result = array();

        if (file_exists($pathToEntry))
        {
            //instantiate the file system class
            $fileSystem = new C_FileSystem();

            //get list of all of the comment files
            $files = $fileSystem->getFiles($pathToEntry);

            foreach ($files as $file)
            {
                $fileContents = $fileSystem->readAllLines($file);
                $fileNameWithoutExtension = basename($file);
                $fileNameWithoutExtension = 
                    basename($fileNameWithoutExtension, ".txt");
                array_push($result, 
                    array($fileNameWithoutExtension, $fileContents));
            }
        }
        return $result;
    }

    //add a comment to a blog entry
    function addComment($pathToEntry, $name, $text)
    {
        $result = FALSE;

        if (file_exists($pathToEntry))
        {
            $fileName = time() . ".txt";
            $textToWrite = array($name, $text);

            //instantiate the file system class
            $fileSystem = new C_FileSystem();

            $files = $fileSystem->
                writeFile($pathToEntry . $fileName, $textToWrite);

            $result = file_exists($pathToEntry . $fileName);
        }

        return $result;
    }

    //delete a comment from the blog entry
    function deleteComment($pathToEntry, $commentToDelete)
    {
        $result = FALSE;

        if (file_exists($pathToEntry))
        {
            $fileName = $commentToDelete . ".txt";

            //instantiate the file system class
            $fileSystem = new C_FileSystem();

            //delete the file
            $fileSystem->deleteFile($pathToEntry . $fileName);

            $result = !file_exists($pathToEntry . $fileName);
        }

        return $result;
    }

    //create a new blog entry
    function saveEntry($pathToEntry, $title, $text, &$entryNameOut)
    {
        $result = FALSE;

        if (file_exists($pathToEntry))
        {
            $fileName = "blog.txt";
            $time = time();
            $entryNameOut = $time;
            $pathToEntry = $pathToEntry . $time . "/";

            //instantiate the file system class
            $fileSystem = new C_FileSystem();

            //create the directory
            $fileSystem->writeDirectory($pathToEntry);

            //write the blog.txt file
            $textToWrite = array($title, $text);
            $fileSystem->writeFile($pathToEntry . $fileName, $textToWrite);

            $result = !file_exists($pathToEntry . $fileName);
        }

        return $result;
    }

    //delete a blog entry and all of its contents
    function deleteEntry($pathToEntry)
    {
        $result = FALSE;

        if (file_exists($pathToEntry))
        {
            //instantiate the file system class
            $fileSystem = new C_FileSystem();

            $files = $fileSystem->getFiles($pathToEntry);
            array_push($files, $pathToEntry . "blog.txt");

            foreach ($files as $file)
            {
                //delete the file
                $fileSystem->deleteFile($file);
            }

            //delete the directory
            $fileSystem->deleteDirectory($pathToEntry);

            $result = !file_exists($pathToEntry);
        }

        return $result;
    }
}

?>
