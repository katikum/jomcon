<?php
include ("admin.php");

class Projects extends Admin {
    public $projs = array (); 

    function __construct ($admin, $order_by) {
        parent::__construct ("My Projects", $admin);
        $where = array ();

        if ($this->admin["ID"] != 1) {
            $where = ["AID", $this->admin["ID"]];
        }
        $rows = getRows ("projects", "*", $where, $order_by);

        foreach ($rows as $row) {
            $county = getRow ("counties", "name", ["ID", $row["county"]])["name"];
            $type = getRow ("projecttypes", "name", ["ID", $row["type"]])["name"];
            $this->projs[] = new Project ($row, $county, $type);
        }
        if ($order_by == "county") {
            $this->projs = sortByName ("counties", $order_by, $this->projs);
        }
        if ($order_by == "type") {
            $this->projs = sortByName ("projecttypes", $order_by, $this->projs);
        }
    }

    function printBody () {
        ?> 
        
        <!-- My Projects Start -->
        <div class="contact wow fadeInUp">
            <div class="container">
                <div class="section-header text-center">
                    <p></p>
                    <h3><u>Project Uploads</u></h3>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover table-condensed" style = "font-size:14px;">            
                        <?php 
                        $caption = '<strong>NB:</strong> Click <u>Table Headings</u> to sort by column. OR Click a <u style = "color: blue">Project Name</u> to add a job to it.';
                        if (count($this->projs) < 1) {
                            $caption = 'You have no project uploads yet. 
                                <a href = "project_upload.php" style = "font-size:14px; font-family:verdana; color:#9c0404">
                                <u>Click to upload</u></a>';
                        }
                        echo ('<caption style = "color:#030f27; font-size:14px">'.$caption."</caption>"); 
                        ?>              
                        <thead id = "thead" style="background-color: #9c0404">
                            <tr>
                                <th>#</th>
                                <th><a href = "my_projects.php?order_by=name" class = "thlink"><u>Project Name</u></a></th>
                                <th><a href = "my_projects.php?order_by=type" class = "thlink"><u>Category</u></a></th>
                                <th><a href = "my_projects.php?order_by=client" class = "thlink"><u>Client</u></a></th>
                                <th><a href = "my_projects.php?order_by=county" class = "thlink"><u>County</u></a></th>
                                <th><a href = "my_projects.php?order_by=ID" class = "thlink"><u>Uploaded</u></a></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $i = 0;
                            foreach ($this->projs as $proj) {
                                echo ('<tr style = "color: #030f27">');
                                    echo ('<td style = "font-size:14px; font-family:verdana; ">'.++$i.".</td>");
                                    echo ('<td><a href = "job_upload.php?id='.$proj->info["ID"].'" style = "font-size:14px; color:blue"><u>'.$proj->info["name"]."</u></td>");
                                    echo ("<td>".$proj->type."</td>");                        
                                    echo ("<td>".$proj->info["client"]."</td>");
                                    echo ("<td>".$proj->county."</td>");
                                    echo ('<td style = "font-size:12px; font-family:verdana; ">'.$proj->info["uploaddate"]."</td>");
                                    echo ('<td><a href = "project_edit.php?id='.$proj->info["ID"].'" style = "font-size:11px; color:green"><u>Edit</u></a></td>');
                                    $td = '<td><a href = "project_delete.php?id='.$proj->info["ID"].'" style = "font-size:10px; color:red"><u>Delete</u></a></td>';
                                    if ($proj->info["AID"] != $this->admin["ID"]) {
                                        $phone = uploader($proj->info["AID"])["phone"];
                                        $td = '<td><a href = "#" style = "font-size:10px; font-family:verdana; color:blue"><u>'.$phone.'</u></a></td>';
                                    }
                                    echo ($td);
                                echo ("</tr>");
                            }
                            ?>                               
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- My Projects End -->
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
$myprojects = new Projects($_SESSION["admin"], $order_by);
$myprojects->printPage();

function isValidKey ($order_by) {
    return $order_by == "name" || $order_by == "type" || $order_by == "client" || 
            $order_by == "county" || $order_by == "ID";
}
?>