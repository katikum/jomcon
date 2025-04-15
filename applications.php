<?php
include ("admin.php");

class Apps extends Admin {
    public $applied = array (); 

    function __construct ($admin, $order_by) {
        parent::__construct ("Jobs", $admin);
        $where = array ();

        if ($this->admin["ID"] != 1) {
            $where = ["AID", $this->admin["ID"]];
        }
        if ($order_by == "type" || $order_by == "JID" || $order_by == "PID") {
            $rows = getRows ("japplications", "*", $where, "");
        } else {
            $rows = getRows ("japplications", "*", $where, $order_by);
        }
        foreach ($rows as $row) {
            $job = getRow ("jobs", "*", ["ID", $row["JID"]]);
            $proj = getRow ("projects", "*", ["ID", $job["PID"]]);
            $this->applied[] = new App ($row, $job, $proj);
        } 
        if ($order_by == "JID") {
            $this->applied = sortByName ("jobs", $order_by, $this->applied);
        }
        if ($order_by == "type") {
            $this->applied = $this->sortByJob (sortJobsByFK ("jobtypes", $order_by));
        }
        if ($order_by == "PID") {    
            $this->applied = $this->sortByJob (sortJobsByFK ("projects", $order_by));
        }
    }

    function sortByJob ($sjrows) {
        $sorted = array ();
        foreach ($sjrows as $sjrow) {
            foreach ($this->applied as $app) {
                if ($app->info["JID"] == $sjrow["ID"]) {
                    $sorted[] = $app;
                }
            }
        }
        return $sorted;
    }

    function printBody () {
        ?>        
        <!-- My Applications Start -->
        <div class="contact wow fadeInUp">
            <div class="container">
                <div class="section-header text-center">
                    <p></p>
                    <h3><u>Job Applications 2</u></h3>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover table-condensed">            
                        <?php 
                        $caption = "<strong>NB:</strong> Click <u>table headings</u> to sort by column. ";
                        if (count($this->applied) < 1) {
                            $caption = 'There are no job applications yet.';
                        }
                        echo ('<caption>'.$caption."</caption>"); 
                        ?>              
                        <thead id = "thead">
                            <tr>
                                <th><u>#</u></th>
                                <th><a href = "applications.php?order_by=JID" class = "thlink"><u>Title</u></a></th>
                                <th><a href = "applications.php?order_by=type" class = "thlink"><u>Type</u></a></th>
                                <th><a href = "applications.php?order_by=PID" class = "thlink"><u>Project</u></a></th>
                                <th><a href = "applications.php?order_by=name" class = "thlink"><u>Name</u></a></th>
                                <th><a href = "applications.php?order_by=phone" class = "thlink"><u>Phone</u></a></th>
                                <th><a href = "applications.php?order_by=edu" class = "thlink"><u>Edu.</u></a></th>
                                <th><a href = "applications.php?order_by=ID" class = "thlink"><u>Applied</u></a></th>
                                <th><a href = "applications.php?order_by=accept" class = "thlink"><u>Hired</u></a></th>                                
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $i = 0;
                            foreach ($this->applied as $app) {
                                echo ('<tr style = "color: #030f27">');
                                    echo ('<td style = "font-family:verdana;">'.++$i.".</td>");
                                    echo ('<td><a href = "job_edit.php?id='.$app->job["ID"].'" style = "font-size:14px; color:blue"><u>'.$app->job["name"]."</u></td>");
                                    $type = getRow ("jobtypes", "name", ["ID", $app->job["type"]])["name"];
                                    echo ("<td>".$type."</td>"); 
                                    $county = getRow ("counties", "name", ["ID", $app->proj["county"]])["name"]; 
                                    echo ('<td><a href = "project_edit.php?id='.$app->proj["ID"].'" style = "font-size:14px; color:blue"><u>'.$app->proj["name"].' in '.$county.' for '.$app->proj["client"]."</u></a></td>");
                                    echo ("<td>".$app->info["name"]."</td>");
                                    echo ("<td>".$app->info["phone"]."</td>");
                                    echo ('<td style = "font-size:12px; font-family:verdana; color: blue">'.getEdu ()[$app->info["edu"]]."</td>");
                                    echo ('<td style = "font-size:12px; font-family:verdana; ">'.$app->info["applydate"]."</td>");                                 
                                    if ($app->info["accept"] == 1) {
                                        echo ('<td style = "font-size:11px; color:#9c0404">Hired</td>');
                                    } else {
                                        echo ('<td><a href = "applications.php?id='.$app->info["ID"].'" style = "font-size:11px; color:green"><u>Accept</u></a></td>');
                                    }                                                                      
                                echo ("</tr>");
                            }
                            ?>                               
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- My Applications End -->
        <?php
    }  
}

session_start();

if (!isset($_SESSION["admin"])) {
    header ("Location: index.php"); exit;
}

$order_by = "";

if (isset($_GET["order_by"]) && isValidKey ($_GET["order_by"])) {
    $order_by = $_GET["order_by"];
}
if (isset($_GET["id"])) {
    $aid = (int) $_GET["id"];
    if ($aid > 0) {
        $app = getRow ("japplications", "ID, accept", ["ID", $aid]);
        if (count ($app) > 0) {
            if ($app["accept"] == 0) {
                $set = 'accept = 1';
                updateRow ("japplications", $set, ["ID", $app["ID"]]);
            }
        }
    }
    header ("Location: applications.php"); exit;
}
$apps = new Apps($_SESSION["admin"], $order_by);
$apps->printPage();

function isValidKey ($order_by) {
    return $order_by == "JID" || $order_by == "type" || $order_by == "PID" || $order_by == "wages" 
            || $order_by == "phone" || $order_by == "accept" || $order_by == "ID" || $order_by == "name"
            || $order_by == "edu";
}
?>