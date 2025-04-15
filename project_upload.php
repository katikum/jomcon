<?php
include ("admin.php");

session_start ();

class UploadProject extends Admin {
    function __construct ($admin) {
        parent::__construct ("Upload", $admin);
    }

    function printBody () {
        $prows = getRows ("projecttypes", "ID, name", [], "name");
        $crows = getRows ("counties", "*", [], "name");
        ?> 
        <!-- Uploader Start -->
        <div class="contact wow fadeInUp">
            <div class="container">
                <div class="section-header text-center">
                    <p>Input Project Details Carefully</p>
                    <h2>New Project</h2>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="contact-info">
                            <div class="contact-item">
                                <!--<i class="flaticon-address"></i> -->
                                <div class="contact-text">
                                    <h2>Project Information</h2>
                                    <p>This data may appear on the website</p>
                                </div>
                            </div>
                            <div class="contact-item">
                                
                                <div class="contact-text">
                                    <h2>Upload Job</h2>
                                    <p>Update jobs to existing project</p>
                                </div>
                            </div>
                            <div class="contact-item">
                                
                                <div class="contact-text">
                                    <h2>Work Applications</h2>
                                    <p>Review job applications and hire workers</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="contact-form">
                            <div id="success"></div>
                            <form name="sentMessage" id="contactForm" method="POST" action="project_upload.php">
                                
                                <div class="control-group">
                                    <select class = "form-control" name="type" required="required">
                                        <option value="">Project Category</option>                                       
                                        <?php
                                        foreach ($prows as $row) {
                                            echo ('<option value='.$row["ID"].'>'.
                                                    $row["name"].'</option>');
                                        } 
                                        ?>
                                    </select>
                                    <p class="help-block text-danger"></p>
                                </div>

                                <div class="control-group">
                                    <input type="text" name = "projectname" class="form-control" id="name" placeholder="Project Name" required="required" data-validation-required-message="Please input project name" />
                                    <p class="help-block text-danger"></p>
                                </div>
                                <div class="control-group">
                                    <input type="text" name = "clientname" class="form-control" id="name" placeholder="Client Name" required="required" data-validation-required-message="Please input client's name" />
                                    <p class="help-block text-danger"></p>
                                </div>
                                <div class="control-group">
                                    <textarea class="form-control" name = "desc" id="message" placeholder="Project description (less than 300 words)" required="required" data-validation-required-message="Please input project description"></textarea>
                                    <p class="help-block text-danger"></p>
                                </div>
                                <div class="control-group">
                                    <select class = "form-control" name="county" required="required">
                                        <option value="">County</option>
                                        <?php
                                        foreach ($crows as $row) {
                                            echo ('<option value='.$row["ID"].'>'.
                                                    $row["name"].'</option>');
                                        } 
                                        ?>
                                    </select>
                                    <p class="help-block text-danger"></p>
                                </div>                              
                                <div>
                                    <button class="btn" type="submit" id="sendMessageButton">Upload</button>
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
    if (isset($_POST["projectname"]) && isset($_POST["clientname"]) && 
        isset($_POST["desc"]) && isset($_POST["county"])) {
    
        $dtz = new DateTimeZone("+0300"); // Nairobi Time Zone = +0300
        $dt = (new DateTime("now", $dtz))->format("Y-m-d h:i:s");
        $query = '(ID, name, client, descr, county, AID, uploaddate, type) values (null, "'.$_POST["projectname"].'", "'.
                    $_POST["clientname"].'", "'.$_POST["desc"].'", '.$_POST["county"].', '.$_SESSION["admin"]["ID"].', "'.$dt.'", '.$_POST["type"].')';
        insertRow ("projects", $query);
        header("Location: my_projects.php"); exit;
    } else {
        $upload = new UploadProject($_SESSION["admin"]);
        $upload->printPage();
    }
}
?>