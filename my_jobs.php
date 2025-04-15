<?php
include ("admin.php");

class Jobs extends Admin {
    public $jobs = array (); 

    function __construct ($admin, $order_by) {
        parent::__construct ("My Jobs", $admin);
        $where = array ();

        if ($this->admin["ID"] != 1) {
            $where = ["AID", $this->admin["ID"]];
        }
        $jobs = getRows ("jobs", "*", $where, $order_by);

        foreach ($jobs as $job) {
            $proj = getRow ("projects", "*", ["ID", $job["PID"]]);
            $type = getRow ("jobtypes", "name", ["ID", $job["type"]])["name"];
            $this->jobs[] = new Job ($job, $type, $proj);
        } 
        if ($order_by == "type") {
            $this->jobs = sortByName ("jobtypes", $order_by, $this->jobs);
        }
        if ($order_by == "PID") {
            $this->jobs = sortByName ("projects", $order_by, $this->jobs);
        }
    }

    function printBody () {
        ?> 
        
        <!-- My Projects Start -->
        <div class="contact wow fadeInUp">
            <div class="container">
                <div class="section-header text-center">
                    <p></p>
                    <h3><u>Job Uploads</u></h3>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover table-condensed" style = "font-size:14px;">            
                        <?php 
                        $caption = "<strong>NB:</strong> Click <u>table headings</u> to sort by column. ";
                        if (count($this->jobs) < 1) {
                            $caption = 'You have no job uploads yet. 
                                <a href = "my_projects.php" style = "font-size:14px; font-family:verdana; color:red">
                                <u>Click to upload</u></a>';
                        }
                        echo ('<caption style = "color:black; font-size:14px">'.$caption."</caption>"); 
                        ?>              
                        <thead id = "thead" style="background-color: #9c0404">
                            <tr>
                                <th><u>#</u></th>
                                <th><a href = "my_jobs.php?order_by=name" style = "color: #030f27"><u>Title</u></a></th>
                                <th><a href = "my_jobs.php?order_by=type" style = "color: #030f27"><u>Type</u></a></th>
                                <th><a href = "my_jobs.php?order_by=PID" style = "color: #030f27"><u>Project</u></a></th>
                                <th><a href = "my_jobs.php?order_by=wages" style = "color: #030f27"><u>KES./Day</u></a></th>
                                <th><a href = "my_jobs.php?order_by=vac" style = "color: #030f27"><u>Jobs</u></a></th>
                                <th><a href = "my_jobs.php?order_by=eta" style = "color: #030f27"><u>Deadline</u></a></th>
                                <th><a href = "my_jobs.php?order_by=ID" style = "color: #030f27"><u>Uploaded</u></a></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $i = 0;
                            foreach ($this->jobs as $job) {
                                echo ('<tr style = "color: #030f27">');
                                    echo ('<td style = "font-family:verdana;">'.++$i.".</td>");
                                    echo ('<td><a href = "job_edit.php?id='.$job->info["ID"].'" style = "font-size:14px; color:blue"><u>'.$job->info["name"]."</u></td>");
                                   
                                    echo ("<td>".$job->type."</td>"); 
                                    $county = getRow ("counties", "name", ["ID", $job->proj["county"]])["name"]; 
                                    echo ('<td><a href = "project_edit.php?id='.$job->proj["ID"].'" style = "font-size:14px; color:blue"><u>'.$job->proj["name"].' in '.$county.' for '.$job->proj["client"]."</u></a></td>");
                                    echo ("<td>".$job->info["wages"]."</td>");
                                    echo ("<td>".$job->info["vac"]."</td>");
                                    echo ('<td style = "font-size:12px; font-family:verdana; color: red">'.$job->info["eta"]."</td>");
                                    echo ('<td style = "font-size:12px; font-family:verdana; ">'.$job->info["uploaddate"]."</td>");
                                    echo ('<td><a href = "job_edit.php?id='.$job->info["ID"].'" style = "font-size:11px; color:green"><u>Edit</u></a></td>');
                                    $td = '<td><a href = "job_delete.php?id='.$job->info["ID"].'" style = "font-size:10px; color:red"><u>Delete</u></a></td>';
                                    if ($job->info["AID"] != $this->admin["ID"]) {
                                        $phone = uploader($job->info["AID"])["phone"];
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
$myjobs = new Jobs($_SESSION["admin"], $order_by);
$myjobs->printPage();

function isValidKey ($order_by) {
    return $order_by == "name" || $order_by == "type" || $order_by == "PID" || $order_by == "wages" 
            || $order_by == "vac" || $order_by == "eta" || $order_by == "ID";
}
?>