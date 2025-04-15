<?php
include ("job_alter.php");

session_start ();

class UploadJob extends Admin {
    public $data = array ();

    function __construct ($admin, $PID) {
        parent::__construct ("Upload Job", $admin);
        $this->data = getRow ("projects", "*", ["ID", $PID]);
    }

    function printBody () {
        $jtrows = getRows ("jobtypes", "*", [], "name");
        ?>        
        <!-- Job Uploader Start -->
        <div class="contact wow fadeInUp">
            <div class="container">
                <div class="section-header text-center">
                    <p>Add jobs to projects</p>
                    <h2>Upload New Job</h2>
                </div>
                <div class="row">

                    <?php parent::printProjectView ($this->data); ?>

                    <div class="col-md-6">
                        <div class="contact-form">
                            <div id="success"></div>
                            <form name="sentMessage" id="contactForm" method="POST" action="job_upload.php">                        
                                <div>
                                    <h4>Add a job to this project..</h4>
                                </div>
                                <input type="hidden" name = "PID" value = <?php echo ($this->data["ID"]); ?> />      
                                <div class="control-group">
                                    <select class = "form-control" name="type" required="required">
                                        <option value="">Job Category</option>                                       
                                        <?php
                                        $other;
                                        foreach ($jtrows as $row) {
                                            if ($row["name"] != "Other") {
                                                echo ('<option value='.$row["ID"].'>'.
                                                        $row["name"].'</option>');
                                            } else {
                                                $other = [$row["ID"], $row["name"]];
                                            }                                           
                                        }
                                        echo ('<option value='.$other[0].'>'.
                                                $other[1].'</option>');
                                        ?>
                                    </select>
                                    <p class="help-block text-danger"></p>
                                </div>
                                <div class="control-group">
                                    <input type="text" name = "title" class="form-control" id="name" placeholder="Title of job" required="required" data-validation-required-message="Please input job title" />
                                    <p class="help-block text-danger"></p>
                                </div>
                                <div class="control-group">
                                    <textarea class="form-control" name = "req" id="message" placeholder="Job requirements (less than 300 words)" required="required" data-validation-required-message="Please input job requirements"></textarea>
                                    <p class="help-block text-danger"></p>
                                </div>
                                <div class="control-group">
                                    <input type="number" name = "wages" class="form-control" id="name" placeholder="Daily Wages (e.g. 1200)" required="required" data-validation-required-message="Please input wages" />
                                    <p class="help-block text-danger"></p>
                                </div>
                                <div class="control-group">
                                    <input type="number" name = "vac" class="form-control" id="name" placeholder="No. of vacancies (e.g. 10)" required="required" data-validation-required-message="Please input no. of job vacancies" />
                                    <p class="help-block text-danger"></p>
                                </div>
                                <div class="control-group">
                                    <label for = "date" style = "color: black; font-size: 14px">Application Deadline</label>
                                    <input type="date" name = "date" class="form-control" id="date" placeholder="Deadline for application" required="required" data-validation-required-message="Please input deadline for applications" />
                                    <p class="help-block text-danger"></p>
                                </div>
                                <div>
                                    <button class="btn" type="submit" id="sendMessageButton" name ="upload" value=1>Go</button>
                                    <button class="btn" name ="upload" value=0>Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Uploader End -->
        <?php
    }
}

if (!isset ($_SESSION["admin"])) {
    header ("Location: index.php"); exit;
} else {
    if (isset ($_GET["id"])) {
        $pid = (int) $_GET["id"];
        if ($pid > 0) {  
            if (count (getRow ("projects", "*", ["ID", $pid])) > 0) {
                $ujob = new UploadJob ($_SESSION["admin"], $pid);
                $ujob->printPage();
            } else { 
                header ("Location: my_projects.php"); exit;
            }
        } else {
            header ("Location: my_projects.php"); exit;
        }
    } else {
        if (isset ($_POST["upload"])) {
            if ($_POST["upload"] == 1) {
                $tz = ini_get('date.timezone');
                $dtz = new DateTimeZone($tz);
                $dt = (new DateTime("now", $dtz))->format("Y-m-d h:i:s");
                $query = '(ID, type, name, req, wages, vac, eta, PID, uploaddate, AID) values (null, '.$_POST["type"].', "'.
                            $_POST["title"].'", "'.$_POST["req"].'", '.$_POST["wages"].', '.$_POST["vac"].', "'.$_POST["date"].
                            '", '.$_POST["PID"].', "'.$dt.'", '.$_SESSION["admin"]["ID"].')';
                insertRow ("jobs", $query);               
            }
        }
        header ("Location: my_projects.php"); exit;
    }
}
?>