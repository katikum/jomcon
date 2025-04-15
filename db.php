<?php
class Project {
    public $info, $county, $type;
    
    function __construct ($row, $county, $type) {
        $this->info = $row;
        $this->county = $county;
        $this->type = $type;
    }
}

class Job {
    public $info, $type, $proj;
    
    function __construct ($row, $type, $proj) {
        $this->info = $row;
        $this->type = $type;
        $this->proj = $proj;
    }
}

class App {
    public $info, $job, $proj;
    
    function __construct ($row, $job, $proj) {
        $this->info = $row;
        $this->job = $job;
        $this->proj = $proj;
    }
}

class Service {
    public $info, $image, $wowdelay;
    
    function __construct ($row, $wowdelay) {
        $this->info = $row;
        $this->image = "../site/img/serv-".$this->info["ID"].".jpg";
        $this->wowdelay = $wowdelay;
    }
}

class DB {
    public $conn = null;

    function __construct ($host, $user, $pw, $db) {
        $this->conn = new mysqli ($host, $user, $pw, $db);

        if($this->conn->connect_error) {
            die("Connection failure, CALL: +254708793131 to resolve, ".$this->conn->connect_error);
        }
    }
    function close() {
        if($this->conn) {
            $this->conn->close();
        }
    }
}

function getRows ($table, $cols, $where = array (), $order_by = "") {
    $rows = array ();
    $db = new DB("localhost", "root", "", "jomcon");
    $sql = "select ".$cols." from ".$table;
    if (count($where) > 0) {
        $sql .= " where ".$where[0]." = ".$where[1];
    }
    if ($order_by != "") {
        $sql .= " order by ".$order_by." asc";
    }
    $res = $db->conn->query($sql);
    while ($row = $res->fetch_assoc()) {
        $rows[] = $row;
    }
    $db->conn->close();
    return $rows;
}

function getRows2 ($table, $cols, $where = array (), $order_by = "") {
    $rows = array ();
    $db = new DB("localhost", "root", "", "jomcon");
    $sql = "select ".$cols." from ".$table;
    if (count($where) > 0) {
        $sql .= " where ".$where[0]." = ".$where[1];
    }
    if ($order_by != "") {
        $sql .= " order by ".$order_by." desc";
    }
    $res = $db->conn->query($sql);
    while ($row = $res->fetch_assoc()) {
        $rows[] = $row;
    }
    $db->conn->close();
    return $rows;
}

function getRow ($table, $cols, $where) {
    $row = array ();
    $db = new DB("localhost", "root", "", "jomcon");
    $res = $db->conn->query("select ".$cols." from ".$table." where ".$where[0]." = ".$where[1]);
    while ($r = $res->fetch_assoc()) {
        $row = $r;
    }
    $db->conn->close();
    return $row;
}

function writeQuery ($q) {
    $db = new DB("localhost", "root", "", "jomcon");
    $db->conn->query($q);
    $db->conn->close();
}

function updateRow ($table, $set, $where) {
    writeQuery ("update ".$table." set ".$set." where ".$where[0]." = ".$where[1]);
}

function insertRow ($table, $query) {
    writeQuery ("insert into ".$table." ".$query);
}

function deleteRow ($table, $ID) {
    writeQuery ("delete from ".$table." where ID = ".$ID);
}

function sortByName ($table, $ID, $items) {
    $rows = getRows ($table, "ID", [], "name");
    $sorted = array ();

    foreach ($rows as $row) {
        foreach ($items as $item) {
            if ($item->info[$ID] == $row["ID"]) {
                $sorted[] = $item;
            }
        }
    }
    return $sorted;
}


function sortJobsByFK ($table, $fk) {
    $trows = getRows ($table, "ID", [], "name");
    $jrows = getRows ("jobs", "ID, ".$fk);
    $sjrows = array ();
    foreach ($trows as $trow) {
        foreach ($jrows as $jrow) {
            if ($jrow[$fk] == $trow["ID"]) {
                $sjrows[] = $jrow;
            }
        }
    }
    return $sjrows;
}

function uploader ($ID) {
    return getRow ("admin", "name, phone", ["ID", $ID]);
}

function getEdu () {
    return array (1=>"None", 2=>"KCPE", 3=>"KCSE", 4=>"Certificate", 5=>"Diploma", 6=>"Bachelors Degree");
}
?>