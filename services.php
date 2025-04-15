<?php
include("db.php");

class Services {
    public $info = array ();

    function __construct () {
        $rows = getRows ("services", "*");
        $wowdelay = 1;

        foreach ($rows as $row) {
            $this->info[] = new Service($row, "0.".$wowdelay."s");
            $wowdelay++;
            if ($wowdelay > 3) {
                $wowdelay = 1;
            }
        }
    }
}
?>