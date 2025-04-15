<?php
include("db.php");

class Jobs {
    public $info = array ();

    function __construct ($order_by) {
        $rows = getRows ("jobs", "*", [], $order_by);
        if ($order_by == "ID") {
            $rows = getRows2 ("jobs", "*", [], $order_by);
        }

        if (count($rows) > 1) {
            foreach ($rows as $row) {
                $type = getRow ("jobtypes", "name", ["ID", $row["type"]])["name"];
                $proj = getRow ("projects", "*", ["ID", $row["PID"]]);
                $this->info[] = new Job ($row, $type, $proj);
            }
            if ($order_by == "type") {
                $this->info = sortByName ("jobtypes", $order_by, $this->info);
            }
            if ($order_by == "PID") {
                $rows = getRows ("counties", "ID", [], "name");
                $sorted = array ();
                foreach ($rows as $row) {
                    foreach ($this->info as $job) {
                        if ($job->proj["county"] == $row["ID"]) {
                            $sorted[] = $job;
                        }
                    }
                }
                $this->info = $sorted;                
            }
        } 
    }
}
?>