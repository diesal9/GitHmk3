<?php

class C_View
{
    //show the page
    function showPage($data)
    {
        $this->printHeader();

?>
        <h1>Clicking on the [<?php echo $GLOBALS["siteTitle"]; ?>] 
            portion of this should also be a link that points back to BASEURL.
        </h1>
        <h1>
            <a id="siteTitleId" 
                href="?c=main&amp;view=loggedin&amp;e=mostrecent" >
                <?php echo $GLOBALS["siteTitle"]; ?>
            </a> - <?php echo $data[0]; ?>
        </h1>
        <div>
            <form id='newEntryFormId' name='newEntryForm' method='get' 
                action='index.php' >
                <div>
                <input type='hidden' id='viewId' name='view' 
                    value='saveentry' />
                <input type='hidden' id='cId' name='c' value='main' />
                </div>
                <table>
                    <tr>
	                    <td>
		                    <label id='titleLabel' for='titleId' >Title</label>
	                    </td>
                        <td>
                            <input type='text' id='titleId' name='title' 
                                value='' />
	                    </td>
                    </tr>
                    <tr>
                        <td>
                            <label id='blogTextAreaLabel' for='blogTextArea' >
                                Blog Text
                            </label>
                        </td>
	                    <td>
		                    <textarea rows="4" cols="50" id='blogTextArea' 
                                name='text'></textarea>
	                    </td>
                    </tr>
                    <tr>
	                    <td>
		                    <input type='submit' id='submitId' 
                                name='submit' value='submit' />
	                    </td>
                        <td>
		                </td>
                    </tr>
                </table>
            </form>
        </div>

<?php
        $this->printFooter();
   }

    //print the header
    function printHeader()
    {
?>
<?xml version ="1.0" encoding="utf-8" ?>
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
                    a simple blog site" />
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
