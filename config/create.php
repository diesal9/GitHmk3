<?php

//the file we could not load
global $fileWeCouldNotLoad;

//setup BASE_DIR
defineBase_Dir();
//echo 'BASE_DIR is: ' . BASE_DIR . '<br />';

if (loadConfigurationFiles())
{
    $server = $GLOBALS["server"];
    $username = $GLOBALS["username"];
    $password = $GLOBALS["password"];
    $databaseName = $GLOBALS["databaseName"];

    $linkID = new mysqli($server, $username, $password);


    $sql = "DROP DATABASE `$databaseName`;";
    $linkID->query($sql);

    $sql="CREATE DATABASE `$databaseName` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;";
    if ($linkID->query($sql))
    {
        $successful = TRUE;
        $errorCompartment = 0;
        echo "The database $databaseName was created. <br />";

        $errorMessage = array();
        $sqlQueries = array();

        array_push($errorMessage, "PROCEDURE `CreateLimerick`");
        $sql = "CREATE DEFINER=`$username`@`$server` PROCEDURE `CreateLimerick`" .
            "(IN `m_LimerickText` VARCHAR(255), IN `m_LimerickTitle` " .
            "VARCHAR(255), IN `m_Author` VARCHAR(255), IN `m_SessionUserID` " .
            "INT) " . 
            "NO SQL " . 
            "BEGIN " . 
            "INSERT INTO Limerick (text, title, author) VALUES " . 
            "(m_LimerickText, m_LimerickTitle, m_Author);" . 
            "SELECT MAX(id) AS limericId FROM Limerick;" . 
            "INSERT INTO Creates (limerickId, sessionUserId) VALUES " .
            "((SELECT MAX(id) AS limericId FROM Limerick), m_SessionUserId);" .
            "END";
        array_push($sqlQueries, $sql);

        array_push($errorMessage, "PROCEDURE `CreateSessionUser`");
        $sql = "CREATE DEFINER=`$username`@`$server` PROCEDURE " . 
            "`CreateSessionUser`()" . 
            "NO SQL " . 
            "BEGIN " . 
            "INSERT INTO SessionUser (name) VALUES ('newSessionUser');" .
            "SELECT MAX(id) AS userId FROM SessionUser;" . 
            "END";
        array_push($sqlQueries, $sql);

        array_push($errorMessage, "PROCEDURE `GetAverageRating`");
        $sql = "CREATE DEFINER=`$username`@`$server` PROCEDURE " .
            "`GetAverageRating`(IN `m_LimerickID` INT)" .
            "NO SQL " .
            "SELECT round(AVG(U.rating)/0.5) * 0.5 from " .
            "Limerick L, RatingOf R, UserRating U, Submits S, SessionUser E " .
            "WHERE " .
            "L.id = R.limerickID AND " .
            "U.id = R.userRatingID AND " .
            "U.id = S.userRatingID AND " .
            "E.id = S.sessionUserId AND " .
            "L.id = m_LimerickID;";
        array_push($sqlQueries, $sql);

        array_push($errorMessage, "PROCEDURE `GetFeaturedLimerick`");
        $sql = "CREATE DEFINER=`$username`@`$server` PROCEDURE " .
            "`GetFeaturedLimerick`()" .
            "NO SQL " .
            "SELECT id, text, title, author, featuredDate FROM Limerick " .
            "WHERE featured = 1;";
        array_push($sqlQueries, $sql);

        array_push($errorMessage, "PROCEDURE `GetLimerick`");
        $sql = "CREATE DEFINER=`$username`@`$server` PROCEDURE " .
            "`GetLimerick`(IN `m_LimerickID` INT)" .
            "NO SQL " .
            "SELECT * from " .
            "Limerick L " .
            "WHERE " .
            "L.id = m_LimerickID;";
        array_push($sqlQueries, $sql);

        array_push($errorMessage, "PROCEDURE `GetLimerickIds`");
        $sql = "CREATE DEFINER=`$username`@`$server` PROCEDURE " .
            "`GetLimerickIds`()" .
            "NO SQL " .
            "SELECT id from Limerick L;";
        array_push($sqlQueries, $sql);

        array_push($errorMessage, "PROCEDURE `GetRandomLimerick`");
        $sql = "CREATE DEFINER=`$username`@`$server` PROCEDURE " . 
            "`GetRandomLimerick`()" .
            "NO SQL " .
            "BEGIN " .
            "DECLARE m_Random INT;" .
            "set m_Random = (SELECT FLOOR(0 + (RAND() * (select count(*) " .
            "from limerick))) from limerick limit 0, 1);" .
            "SELECT leftSide.id, leftSide.text, leftSide.title, " . 
            "leftSide.author, leftSide.featuredDate FROM Limerick " .
            "AS leftSide " .
            "LIMIT m_Random, 1;" .
            "END";
        array_push($sqlQueries, $sql);

        array_push($errorMessage, "PROCEDURE `GetSessionUserRating`");
        $sql = "CREATE DEFINER=`$username`@`$server` PROCEDURE " .
            "`GetSessionUserRating`(IN `m_LimerickID` INT, " . 
            "IN `m_SessionUserId` INT)" .
            "NO SQL " .
            "SELECT round(AVG(U.rating)/0.5) * 0.5 from " .
            "Limerick L, RatingOf R, UserRating U, Submits S, SessionUser E " .
            "WHERE " .
            "L.id = R.limerickID AND " .
            "U.id = R.userRatingID AND " .
            "U.id = S.userRatingID AND " .
            "E.id = S.sessionUserId AND " .
            "L.id = m_LimerickID AND " .
            "E.id = m_SessionUserId;";
        array_push($sqlQueries, $sql);

        array_push($errorMessage, "PROCEDURE `GetTenHighestRated`");
        $sql = "CREATE DEFINER=`$username`@`$server` PROCEDURE " .
            "`GetTenHighestRated`()" .
            "NO SQL " .
            "SELECT L.id, L.title, (round(AVG(U.rating)/0.5) * 0.5) as " .
            "Average from " .
            "Limerick L, RatingOf R, UserRating U, Submits S, SessionUser E " .
            "WHERE " .
            "L.id = R.limerickID AND " .
            "U.id = R.userRatingID AND " .
            "U.id = S.userRatingID AND " .
            "E.id = S.sessionUserId " .
            "GROUP BY L.id " .
            "ORDER BY Average DESC " .
            "LIMIT 0, 10;";
        array_push($sqlQueries, $sql);

        array_push($errorMessage, "PROCEDURE `GetTenMostRecent`");
        $sql = "CREATE DEFINER=`$username`@`$server` PROCEDURE " .
            "`GetTenMostRecent`()" .
            "NO SQL " .
            "SELECT id, title, submittedDate from " .
            "Limerick " .
            "ORDER BY submittedDate DESC " .
            "LIMIT 0, 10;";
        array_push($sqlQueries, $sql);

        array_push($errorMessage, "PROCEDURE `SubmitUserRating`");
        $sql = "CREATE DEFINER=`$username`@`$server` PROCEDURE " .
            "`SubmitUserRating`(IN `m_Rating` DECIMAL(10,1), " .
            "IN `m_SessionUserId` INT, IN `m_LimerickID` INT)" .
            "NO SQL " .
            "BEGIN " .
            "INSERT INTO UserRating (rating) VALUES (m_Rating);" .
            "SELECT MAX(id) FROM UserRating;" .
            "INSERT INTO Submits (userRatingID, sessionUserId) VALUES " .
            "((SELECT MAX(id) FROM UserRating), m_SessionUserId);" .
            "INSERT INTO RatingOf (limerickID, userRatingID) VALUES " .
            "(m_LimerickID, (SELECT MAX(id) FROM UserRating));" .
            "END";
        array_push($sqlQueries, $sql);

        array_push($errorMessage, "PROCEDURE `UpdateFeaturedLimerick`");
        $sql = "CREATE DEFINER=`$username`@`$server` PROCEDURE " .
            "`UpdateFeaturedLimerick`(IN `m_OldLimerickID` INT, " .
            "IN `m_NewLimerickID` INT)" .
            "NO SQL " .
            "BEGIN " .
            "Update Limerick SET featuredDate = now(), featured = 1 WHERE " . 
            "id = m_NewLimerickID;" .
            "Update Limerick SET featured = 0 WHERE id = m_OldLimerickID;" .
            "END";
        array_push($sqlQueries, $sql);

        array_push($errorMessage, "PROCEDURE `UpdateViewedLimerick`");
        $sql = "CREATE DEFINER=`$username`@`$server` PROCEDURE " .
            "`UpdateViewedLimerick`(IN `m_LimerickID` INT)" .
            "NO SQL " .
            "Update Limerick SET submittedDate = now() WHERE " .
            "id = m_LimerickID;";
        array_push($sqlQueries, $sql);

        array_push($errorMessage, "CREATE TABLE `creates`");
        $sql = "CREATE TABLE IF NOT EXISTS `creates` (" .
            "`limerickId` int(11) NOT NULL, " .
            "`sessionUserId` int(11) NOT NULL, " .
            "PRIMARY KEY (`limerickId`,`sessionUserId`)" .
            ") ENGINE=InnoDB DEFAULT CHARSET=latin1;";
        array_push($sqlQueries, $sql);

        array_push($errorMessage, "CREATE TABLE `limerick`");
        $sql = "CREATE TABLE IF NOT EXISTS `limerick` (" .
            "`id` int(11) NOT NULL AUTO_INCREMENT, " .
            "`text` varchar(255) NOT NULL, " .
            "`title` varchar(255) NOT NULL, " .
            "`author` varchar(255) NOT NULL, " .
            "`featured` int(11) NOT NULL DEFAULT '0', " .
            "`submittedDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP, " .
            "`featuredDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP, " .
            "PRIMARY KEY (`id`)" .
            ") ENGINE=InnoDB  DEFAULT CHARSET=latin1;";
        array_push($sqlQueries, $sql);

        array_push($errorMessage, "CREATE TABLE `ratingof`");
        $sql = "CREATE TABLE IF NOT EXISTS `ratingof` (" .
            "`limerickId` int(11) NOT NULL, " .
            "`userRatingId` int(11) NOT NULL, " .
            "PRIMARY KEY (`limerickId`,`userRatingId`)" .
            ") ENGINE=InnoDB DEFAULT CHARSET=latin1;";
        array_push($sqlQueries, $sql);

        array_push($errorMessage, "CREATE TABLE `sessionuser`");
        $sql = "CREATE TABLE IF NOT EXISTS `sessionuser` (" .
            "`id` int(11) NOT NULL AUTO_INCREMENT, " .
            "`name` varchar(255) NOT NULL, " .
            "PRIMARY KEY (`id`)" .
            ") ENGINE=InnoDB  DEFAULT CHARSET=latin1;";
        array_push($sqlQueries, $sql);

        array_push($errorMessage, "CREATE TABLE `submits`");
        $sql = "CREATE TABLE IF NOT EXISTS `submits` (" .
            "`userRatingId` int(11) NOT NULL, " .
            "`sessionUserId` int(11) NOT NULL, " .
            "PRIMARY KEY (`userRatingId`,`sessionUserId`)" .
            ") ENGINE=InnoDB DEFAULT CHARSET=latin1;";
        array_push($sqlQueries, $sql);

        array_push($errorMessage, "CREATE TABLE `userrating`");
        $sql = "CREATE TABLE IF NOT EXISTS `userrating` (" .
            "`id` int(11) NOT NULL AUTO_INCREMENT, " .
            "`rating` decimal(10,1) NOT NULL, " .
            "PRIMARY KEY (`id`)" .
            ") ENGINE=InnoDB  DEFAULT CHARSET=latin1;";
        array_push($sqlQueries, $sql);

        array_push($errorMessage, "INSERT INTO `limerick`");
        $sql = "INSERT INTO `limerick` (`text`, `title`, `author`, " .
            "`featured`, `submittedDate`, `featuredDate`) VALUES " .
            "('I wanted to be\r\na black and yellow bee\r\nI loved the " . 
            "rainy weather\r\nno matter what or whether\r\nI was a flying " . 
            "bee', 'Flying Bee', 'Michael Jordan', 1, '2013-10-28 00:36:59', " .
            "'2013-10-28 01:12:24'), " .
            "('anybody who is going where\r\npeople want to see were\r\nthe " .
            "large ugly boar\r\nused a drill to bore\r\na hole that show " . 
            "where', 'Boar With  a Drill', 'Chris Mullins', 0, " . 
            "'2013-10-28 00:40:06', '2013-10-28 00:47:40');";
        array_push($sqlQueries, $sql);

        for ($i = 0; $i < count($sqlQueries); $i++)
        {
            $linkID = new mysqli($server, $username, $password , $databaseName);

            if (!$linkID->query($sqlQueries[$i]))
            {echo $sqlQueries[$i];
                $successful = FALSE;
                $errorCompartment = $i;
                break;
            }
        }

        if ($successful)
        {
          echo "The tables, stored procedures and initial data have be created";
        }
        else
        {
            echo $errorMessage[$errorCompartment] . 
            " has failed and was not created." .
            "The database is not completely created";
        }
    }
    else
    {
        echo "The database " . $GLOBALS["databaseName"] . " was not created.";
    }
}
else
{
    echo $fileWeCouldNotLoad . " could not be loaded" . "<br />";
}

//define the base dir
function defineBase_Dir()
{
    define("BASE_DIR", substr($_SERVER["SCRIPT_FILENAME"], 0, 
        -strlen("create.php")));
}

//load the config file through require
function loadConfigurationFiles()
{
    $result = FALSE;

    //load configuration file
    $GLOBALS["fileWeCouldNotLoad"] = BASE_DIR . "config.php";

    if (file_exists($GLOBALS["fileWeCouldNotLoad"]))
    {
        require($GLOBALS["fileWeCouldNotLoad"]);

        $result = TRUE;
    }

    return $result;
}

?>
