<?php
include ("project_alter.php");

class EditProject extends AlterProject {

    function __construct ($admin, $PID) {
        parent::__construct ("Edit", $admin, $PID);
    }

    function printBody () {
        ?> 
        
        <!-- Editor Start -->
        <div class="contact wow fadeInUp">
            <div class="container">
                <div class="section-header text-center">
                    <p>Review Project Details Carefully</p>
                    <h2>Edit Project</h2>
                </div>
                <div class="row">
                    
                    <?php parent::printProjectView ($this->data); ?>
                    
                    <div class="col-md-6">
                        <div class="contact-form">
                            <div id="success"></div>
                            <form name="sentMessage" id="contactForm" method="POST" action="project_edit.php">
                                
                                <div class="control-group">
                                    <label for = "type" class = "formlabel">Project Category</label>
                                    <select class = "form-control" name="type" required="required">                                                                    
                                        <?php
                                        foreach ($this->prows as $row) {
                                            $option = '<option value='.$row["ID"];
                                            if ($row["ID"] == $this->data["type"]) $option .= ' selected';
                                            $option .= '>'.$row["name"].'</option>';
                                            echo ($option);
                                        } 
                                        ?>
                                    </select>
                                    <p class="help-block text-danger"></p>
                                </div>

                                <div class="control-group">
                                    <label for = "name" class = "formlabel">Project Name</label>
                                    <input type="text" name = "projectname" class="form-control" id="name" placeholder="Project Name" required="required" data-validation-required-message="Please input project name" value = "<?php echo ($this->data["name"]); ?>" />
                                    <p class="help-block text-danger"></p>
                                </div>
                                <div class="control-group">
                                    <label for = "name" class = "formlabel">Client</label>
                                    <input type="text" name = "clientname" class="form-control" id="name" placeholder="Client Name" required="required" data-validation-required-message="Please input client's name" value = "<?php echo ($this->data["client"]); ?>" />
                                    <p class="help-block text-danger"></p>
                                </div>
                                <div class="control-group">
                                    <label for = "message" class = "formlabel">Project Description</label>
                                    <textarea class="form-control" name = "desc" id="message" placeholder="Project description (less than 300 words)" required="required" data-validation-required-message="Please input project description"><?php echo ($this->data["descr"]); ?></textarea>
                                    <p class="help-block text-danger"></p>
                                </div>
                                <div class="control-group">
                                    <label for = "county" class = "formlabel">County</label>
                                    <select class = "form-control" name="county" required="required" id="county">
                                        <option value="">County</option>
                                        <?php
                                        foreach ($this->crows as $row) {
                                            $option = '<option value='.$row["ID"];
                                            if ($row["ID"] == $this->data["county"]) $option .= ' selected';
                                            $option .= '>'.$row["name"].'</option>';
                                            echo ($option);
                                        } 
                                        ?>
                                    </select>
                                    <p class="help-block text-danger"></p>
                                </div>   
                                <input type="hidden" name = "PID" value = <?php echo ($this->data["ID"]); ?> />                           
                                <div>
                                    <button class="btn" type="submit" id="sendMessageButton" name ="update" value=1>Update</button>
                                    <button class="btn" name ="update" value=0>Cancel</button>
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
}

session_start ();

if (!isset ($_SESSION["admin"])) {
    header ("Location: index.php"); exit;
} else {
    if (isset ($_GET["id"])) {
        $pid = (int) $_GET["id"];
        if ($pid > 0) {  
            if (count (getRow ("projects", "*", ["ID", $pid])) > 0) {
                $edit = new EditProject ($_SESSION["admin"], $pid);
                $edit->printPage();
            } else { 
                header ("Location: my_projects.php"); exit;
            }
        } else {
            header ("Location: my_projects.php"); exit;
        }
    } else {
        if (isset ($_POST["update"])) {
            if ($_POST["update"] == 1) {
                $set = 'type = '.$_POST["type"].', name = "'.$_POST["projectname"].'", client = "'.
                $_POST["clientname"].'", descr = "'.$_POST["desc"].'", county = '.$_POST["county"];
                updateRow ("projects", $set, ["ID", $_POST["PID"]]);
            }
        }
        header ("Location: my_projects.php"); exit;
    }
}
?>
