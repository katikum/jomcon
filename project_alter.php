<?php
include ("admin.php");

class AlterProject extends Admin {
    public $data = array (); public $prows = array (); public $crows = array ();

    function __construct ($title, $admin, $PID=0) {
        parent::__construct ($title, $admin);
        $this->data = getRow("projects", "*", ["ID", $PID]);
        $this->prows = getRows ("projecttypes", "ID, name", [], "name");
        $this->crows = getRows ("counties", "*", [], "name");
    }
}
?>
