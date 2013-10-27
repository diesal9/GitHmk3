<?php

class C_View
{
    //show the page
    function showPage($data)
    {
        $this->printHeader();

?>
        <div id="rightPaneOut">
            <a id="cId" href="?c=main&amp;view=limerick" >Create Limerick</a>
        </div>
        <h1><?php echo $GLOBALS["siteTitle"]; ?></h1>
<!--        <h1>
            <a id="siteTitleId" 
                href="?c=main&amp;view=nonloggedin&amp;e=mostrecent" >
                <?php echo $GLOBALS["siteTitle"]; ?>
            </a> - <?php echo $data[1]->contents[0]; ?>
        </h1>
        <div>
            <?php 
                echo "<p class='blogEntry'>";
                for($i = 1; $i < count($data[1]->contents); $i++)
                {
                    echo $data[1]->contents[$i] . "<br />"; 
                }
                echo "</p>";
            ?>
            <br />
            <label id='commentsLabel'>Comments</label> 
            <br />
            <?php 
                for($i = 0; $i < count($data[1]->comments); $i++)
                {
                    echo "<p class='comment'>";
                    $dateString = $data[1]->comments[$i][0];
                    date_default_timezone_set('America/Los_Angeles');
                    echo "<br />" . date("r", $dateString) . "<br />"; 
                    for($j = 0; $j < count($data[1]->comments[$i][1]); $j++)
                    {
                        if ($j == 0)
                        {
                            echo "By: ";
                        }
                        echo $data[1]->comments[$i][1][$j] . "<br />"; 
                    }
                    echo "</p>";
                }
            ?>
        </div>
-->
<?php
        $this->printFooter();
    }

    //print the header
    function printHeader()
    {
        echo '<?xml version ="1.0" encoding="utf-8" ?>';
?>

        <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
            "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
        <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" >
            <head>
                <title><?php echo $GLOBALS["siteTitle"]; ?></title>
                <link rel="shortcut icon" href="Images/favicon.ico" />
                <link rel="icon" href="
                    <?php echo BASE_DIR . 'Images/favicon.ico' ?>" />
                <meta http-equiv="Content-Type" content="text/html 
                    charset=UTF-8" />
                <meta name="Author" content="Charles Bocage" />
                <meta name="description" content="This is a page showcasing 
                    a simple looney limerick site" />
                <meta name="keywords" content="blog comment sample" />
                <meta name="ROBOTS" content="NOINDEX,NOFOLLOW" /> 
                <!-- Example commands NOINDEX, NOFOLLOW, NOCACHE, NOSNIPPET, 
                    NOODP, NOYDIR . Some of these commands can also be 
                    specified in a robots.txt file. ROBOTS and these values 
                    are case insensitive.-->
<?php
        $pathToCssFile = BASE_DIR . "css/page.css";
        if ($this->loadConfigurationFile($pathToCssFile))
        {
?>
            </head>
<?php
        }
        else
        {
            //have an error view for this
            echo $pathToCssFile . " could not be loaded" . "<br />";
        }
?>
            <body>
<?php
    }

    //print the footer
    function printFooter()
    {
?>
            </body>
        </html>
<?php
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
