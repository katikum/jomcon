<?php
include ("job_alter.php");

session_start ();

class EditJob extends AlterJob {

    function __construct ($admin, $JID) {
        parent::__construct ("Edit Job", $admin, $JID);
    }

    function printBody () {
        $jtrows = getRows ("jobtypes", "*");
        ?> 
        <!-- Editor Start -->
        <div class="contact wow fadeInUp">
            <div class="container">
                <div class="section-header text-center">
                    <p>Input Job Details Carefully</p>
                    <h3><u>Jobs</u></h3>
                </div>
                <div class="row">

                    <?php $this->printEditView (); ?>

                    <div class="col-md-6">
                        <div class="contact-form">
                            <div id="success"></div>
                            <form name="sentMessage" id="contactForm" method="POST" action="job_edit.php">                        
                                <div>
                                    <h4>Edit the job post</h4>
                                </div>
                                <input type="hidden" name = "JID" value = <?php echo ($this->data["ID"]); ?> />      
                                <div class="control-group">
                                    <label for = "type" class = "formlabel">Job Category</label>
                                    <select class = "form-control" name="type" required="required" id = "type">
                                                                            
                                        <?php
                                        foreach ($jtrows as $row) {
                                            $option = '<option value='.$row["ID"];
                                            if ($row["ID"] == $this->data["type"]) $option .= " selected";
                                            $option .= '>'.$row["name"].'</option>';
                                            echo ($option);
                                        } 
                                        ?>
                                    </select>
                                    <p class="help-block text-danger"></p>
                                </div>
                                <div class="control-group">
                                    <label for = "name" class = "formlabel">Job Title</label>
                                    <input type="text" name = "title" class="form-control" id="name" placeholder="Title of job" required="required" data-validation-required-message="Please input job title" value = "<?php echo ($this->data["name"]); ?>" />
                                    <p class="help-block text-danger"></p>
                                </div>
                                <div class="control-group">
                                    <label for = "message" class = "formlabel">Requirements</label>
                                    <textarea class="form-control" name = "req" id="message" placeholder="Job requirements (less than 300 words)" required="required" data-validation-required-message="Please input job requirements"><?php echo ($this->data["req"]); ?></textarea>
                                    <p class="help-block text-danger"></p>
                                </div>
                                <div class="control-group">
                                    <label for = "name" class = "formlabel">Wages (KSH/day)</label>
                                    <input type="number" name = "wages" class="form-control" id="name" placeholder="Daily Wages (e.g. 1200)" required="required" data-validation-required-message="Please input wages" value = "<?php echo ($this->data["wages"]); ?>"/>
                                    <p class="help-block text-danger"></p>
                                </div>
                                <div class="control-group">
                                    <label for = "type" class = "formlabel">Vacancies</label>
                                    <input type="number" name = "vac" class="form-control" id="name" placeholder="No. of vacancies (e.g. 10)" required="required" data-validation-required-message="Please input no. of job vacancies" value = "<?php echo ($this->data["vac"]); ?>" />
                                    <p class="help-block text-danger"></p>
                                </div>
                                <div class="control-group">
                                    <label for = "date" class = "formlabel">Application Deadline</label>
                                    <input type="date" name = "date" class="form-control" id="date" placeholder="Deadline for application" required="required" data-validation-required-message="Please input deadline for applications" value = "<?php echo ($this->data["eta"]); ?>"/>
                                    <p class="help-block text-danger"></p>
                                </div>
                                <div>
                                    <button class="btn" type="submit" id="sendMessageButton" name ="edit" value=1>Go</button>
                                    <button class="btn" name ="edit" value=0>Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Editor End -->
        <?php
    }

    function printEditView () {
        ?> 
        <div class="col-md-6">
            <div class="contact-info">
                <div class="contact-item">
                    <!--<i class="flaticon-address"></i> -->
                    <div class="contact-text">
                        <h2 style = "font-size:14px"><u>
                        <?php                        
                        echo (strtoupper(getRow ("jobtypes", "name", ["ID", $this->data["type"]])["name"]." Job"));

                        ?>
                        </u></h2>
                        <?php                          
                            echo ('<p>Project: <a href = "project_edit.php?id='.$this->proj["ID"].'"><u>'.strtoupper($this->proj["name"]).'</u></a></p>'); 
                            echo ('<p>Type: '.strtoupper(getRow ("projecttypes", "name", ["ID", $this->proj["type"]])["name"]).'</p>'); 
                            echo ('<p>Client: '.strtoupper($this->proj["client"]).'</p>'); 
                            echo ('<p>County: '.strtoupper(getRow ("counties", "name", ["ID", $this->proj["county"]])["name"]).'</p>');
                        ?>
                    </div>
                </div>  
                <div class="contact-item">
                    <!--<i class="flaticon-address"></i> -->
                    <div class="contact-text">
                        <h2 style = "font-size:14px"><u><?php echo (strtoupper("Job Posted by"));?></u></h2>
                            <?php 
                            $uploader = uploader ($this->data['AID']);
                            echo ('<p>Admin: '.strtoupper($uploader["name"]).'</p>');    
                            echo ('<p>Date: '.$this->data["uploaddate"].'</p>'); 
                            echo ('<p>Phone: 0'.$uploader["phone"].'</p>');                                                                 
                            ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}

if (!isset ($_SESSION["admin"])) {
    header ("Location: index.php"); exit;
} else {
    if (isset ($_GET["id"])) {
        $jid = (int) $_GET["id"];
        if ($jid > 0) {  
            if (count (getRow ("jobs", "*", ["ID", $jid])) > 0) {
                $edit = new EditJob ($_SESSION["admin"], $jid);
                $edit->printPage();
            } else { 
                header ("Location: my_jobs.php"); exit;
            }
        } else {
            header ("Location: my_jobs.php"); exit;
        }
    } else {
        if (isset ($_POST["edit"])) {
            if ($_POST["edit"] == 1) {
                $set = 'type = '.$_POST["type"].', name = "'.$_POST["title"].'", req = "'.
                $_POST["req"].'", wages = '.$_POST["wages"].', vac = '.$_POST["vac"].', eta = "'.$_POST["date"].'"';
                updateRow ("jobs", $set, ["ID", $_POST["JID"]]);
            }
        }
        header ("Location: my_jobs.php"); exit;
    }
}
?>