<?php

class C_Limerick
{
    public $id = "";
    public $text = "";
    public $title = "";
    public $author = "";
    public $userRating = "";
    public $totalRating = "";

    //C_Limerick constructor
    function __construct($limerickId)
    {
        $database = new C_Database();
        $database->OpenConnection();
        $result = $database->getLimerickById($limerickId);
        $row = $result->fetch_row();
        $this->id = $row[0];
        $this->text = $row[1];
        $this->title = $row[2];
        $this->author = $row[3];
        $database->CloseConnection();

        $database->OpenConnection();

        $this->userRating = $database->GetGetSessionUserRating($limerickId);

        $database->CloseConnection();

        $database->OpenConnection();

        $this->totalRating = $database->GetAverageRating($limerickId);

        $database->CloseConnection();
    }
}

?>
