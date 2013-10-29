<?php

class C_View
{
    //show the page
    function showPage($data)
    {
        $this->printHeader();

?>
        <div id="rightPaneOut">
            <table>
                <tr>
                    <td>
                        <a id="lId" href="?c=main&amp;view=limerick" >
                            Create Limerick
                        </a>
                    </td>
                    <td>
                    </td>
                </tr>
                <tr>
                    <td><br /></td><td></td>
                </tr>
                <tr>
                    <td>
                        <label><strong>Ten Highest Rated</strong></label>
                    </td>
                    <td>
                    </td>
                </tr>
<?php
    $count = 0;
    while ($row = $data[1]->fetch_row()) 
    {
?>
                <tr>
                    <td>
                        <a id="tenHigh<?php echo $row[0]; ?>" 
               href="?c=main&amp;view=nonloggedin&amp;l=<?php echo $row[0]; ?>">
                        <?php echo $row[1]; ?></a>
                    </td>
                    <td>
                        <label><?php echo $row[2]; ?></label>
                    </td>
                </tr>
<?php
    }
?>
                <tr>
                    <td><br /></td><td></td>
                </tr>
                <tr>
                    <td>
                        <label><strong>Ten Recently Created</strong></label>
                    </td>
                    <td>
                    </td>
                </tr>
<?php
    $count = 0;
    while ($row = $data[2]->fetch_row()) 
    {
?>
                <tr>
                    <td>
                        <a id="tenMost<?php echo $row[0]; ?>" 
               href="?c=main&amp;view=nonloggedin&amp;l=<?php echo $row[0]; ?>">
                        <?php echo $row[1]; ?></a>
                    </td>
                    <td>
                        <label><?php echo $row[2]; ?></label>
                    </td>
                </tr>
<?php
    }
?>
                <tr>
                    <td><br /></td><td></td>
                </tr>
                <tr>
                    <td>
                        <a id="random" href="?c=main&amp;view=random">
                        View a Random Limerick</a>
                    </td>
                    <td></td>
                </tr>
            </table>
        </div>
        <h1><?php echo $GLOBALS["siteTitle"]; ?></h1>
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
		          <label id='sessionIDLabel' for='sessionID' >Session Id</label>
	                    </td>
                        <td>
                            <input type='text' id='sessionID' name='title' 
                            value='<?php echo $_SESSION[$GLOBALS["userId"]]; ?>'  
                                readonly="readonly"/>
	                    </td>
                    </tr>
                    <tr>
	                    <td>
		                    <label id='titleLabel' for='titleId' >Title</label>
	                    </td>
                        <td>
                            <input type='text' id='titleId' name='title' 
                                value='<?php echo $data[0]->title; ?>'  
                                readonly="readonly"/>
	                    </td>
                    </tr>
                    <tr>
	                    <td>
		                    <label id='authorLabel' 
                                for='authorId' >Author</label>
	                    </td>
                        <td>
                            <input type='text' id='authorId' name='author' 
                                value='<?php echo $data[0]->author; ?>' 
                                readonly="readonly"/>
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
        name='text' readonly="readonly"><?php echo $data[0]->text; ?></textarea>
	                    </td>
                    </tr>
               </table>
            </form>
        </div>
        <div>
<?php
$userRating = floatval($data[0]->userRating);

$left = TRUE;
$count = 0;
$ratingToShow = 0;
$id = $data[0]->id;
$userId = $_SESSION[$GLOBALS["userId"]];
//print yellow halves
for ($i = 0.0; $i < $userRating; $i += 0.5)
{
    $ratingToShow = ceil($i + 0.5);
    if ($left)
    {
        echo "<a id='greyStarA$count' href='?c=main&amp;l=$id&amp;u=$userId" .
            "&amp;view=setuserrating&amp;r=$ratingToShow'>" . 
            "<img id='greyStarI$count' " . 
            "src='Images/YellowStarLeftSide.png' " . 
            "alt='rate it at $ratingToShow' /></a>";
    }
    else
    {
        echo "<a id='greyStarA$count' href='?c=main&amp;l=$id&amp;u=$userId" .
            "&amp;view=setuserrating&amp;r=$ratingToShow'>" . 
            "<img id='greyStarI$count' " . 
            "src='Images/YellowStarRightSide.png' " . 
            "alt='rate it at $ratingToShow' /></a>";
    }
    $left = !$left;
    $count++;
}
//print grey halves
for ($i = $userRating; $i < 5.0; $i += 0.5)
{
    $ratingToShow = ceil($i + 0.5);
    if ($left)
    {
        echo "<a id='greyStarA$count' href='?c=main&amp;l=$id&amp;u=$userId" .
            "&amp;view=setuserrating&amp;r=$ratingToShow'>" . 
            "<img id='greyStarI$count' " . 
            "src='Images/GreyStarLeftSide.png' " . 
            "alt='rate it at $ratingToShow' /></a>";
    }
    else
    {
        echo "<a id='greyStarA$count' href='?c=main&amp;l=$id&amp;u=$userId" .
            "&amp;view=setuserrating&amp;r=$ratingToShow'>" . 
            "<img id='greyStarI$count' " . 
            "src='Images/GreyStarRightSide.png' " . 
            "alt='rate it at $ratingToShow' /></a>";
    }
    $left = !$left;
    $count++;
}

echo "User Rating " . $data[0]->userRating . "<br />";

$totalRating = floatval($data[0]->totalRating);

$left = TRUE;
$count = 0;
$ratingToShow = 0;
//print yellow halves
for ($i = 0.0; $i < $totalRating; $i += 0.5)
{
    $ratingToShow = $i + 0.5;
    if ($left)
    {
        echo "<img id='greyStarG$count' " . 
            "src='Images/YellowStarLeftSide.png' " . 
            "alt='rate it at $ratingToShow' />";
    }
    else
    {
        echo "<img id='greyStarG$count' " . 
            "src='Images/YellowStarRightSide.png' " . 
            "alt='rate it at $ratingToShow' />";
    }
    $left = !$left;
    $count++;
}
//print grey halves
for ($i = $totalRating; $i < 5; $i += 0.5)
{
    $ratingToShow = $i + 0.5;
    if ($left)
    {
        echo "<img id='greyStarG$count' " . 
            "src='Images/GreyStarLeftSide.png' " . 
            "alt='rated at $ratingToShow' />";
    }
    else
    {
        echo "<img id='greyStarG$count' " . 
            "src='Images/GreyStarRightSide.png' " . 
            "alt='rated at $ratingToShow' />";
    }
    $left = !$left;
    $count++;
}
echo "Total Rating " . $data[0]->totalRating . "<br />";
?>
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
