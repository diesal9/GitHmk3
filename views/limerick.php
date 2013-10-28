<?php

class C_View
{
    //show the page
    function showPage($data)
    {
        $this->printHeader();

?>
        <h1>
            <a id="siteTitleId" 
                href="?c=main&amp;view=nonloggedin" >
                <?php echo $GLOBALS["siteTitle"]; ?>
            </a> - <?php echo $data[0]; ?>
        </h1>
        <div>
            <form id='newEntryFormId' name='newEntryForm' method='get' 
                action='index.php' onsubmit="return ValidateForm();" >
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
                                value='<?php echo $data[1] ?>' />
	                    </td>
                    </tr>
                    <tr>
	                    <td>
		                    <label id='authorLabel' 
                                for='authorId' >Author</label>
	                    </td>
                        <td>
                            <input type='text' id='authorId' name='author' 
                                value='<?php echo $data[3] ?>' />
	                    </td>
                    </tr>
                    <tr>
                        <td>
                            <label id='limerickTextAreaLabel' 
                                for='limerickTextArea' >
                                Limerick Text
                            </label>
                        </td>
	                    <td>
		                    <textarea rows="5" cols="50" id='limerickTextArea' 
                                name='text'><?php echo $data[2] ?></textarea>
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
                <meta name="keywords" content="limerick comment sample" />
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
                <script type="text/javascript">
                    function ValidateForm()
                    {
                        var result = false;
                        var formTitle = document.getElementById("titleId");

                        var formAuthor = document.getElementById("authorId");

                        var formText = 
                            document.getElementById("limerickTextArea");

                        var formTextThirtyCharacters = true;
                        var formTextSplit = formText.value.split("\n");
                        for (var i = 0; i < formTextSplit.length; i++)
                        {
                            if (formTextSplit[i].length > 30)
                            {
                                formTextThirtyCharacters = false;
                                break;
                            }
                        }

                        if (formTitle.value == "")
                        {
                            alert("Please fill in the title");
                        }
                        else if(formTitle.value.length > 30)
                        {
                            alert("The title cannot be longer than 30 " +
                                "characters");
                        }
                        else if (formAuthor.value == "")
                        {
                            alert("Please fill in the author");
                        }
                        else if(formAuthor.value.length > 30)
                        {
                            alert("The author cannot be longer than 30 " +
                                "characters");
                        }
                        else if(formTextSplit.length != 5)
                        {
                            alert("The limerick needs to have 5 lines");
                        }
                        else if(!formTextThirtyCharacters)
                        {
                            alert("The limerick cannot have a line length " + 
                                "longer than 30 characters");
                        }
                        else
                        {
                            result = true;
                        }

                        return result;
                    }

                </script>
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
