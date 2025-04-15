<?php
include ("job_alter.php");

class DeleteJob extends AlterJob {
    function __construct ($admin, $JID) {
        parent::__construct ("Remove Job", $admin, $JID);
    }

    function printBody () {
        ?>       
        <!-- Job Deleter Start -->
        <div class="contact wow fadeInUp">
            <div class="container">
                <div class="section-header text-center">
                    <p>Are you sure?</p>
                    <h2>Delete Project</h2>
                </div>
                <div class="row">

                <?php $this->printJobView (); ?>
                    
                    <div class="col-md-6">
                        <div class="contact-form">
                            <div id="success"></div>
                            <form name="sentMessage" id="contactForm" method="POST" action="job_delete.php">   
                                <input type="hidden" name = "JID" value = <?php echo ($this->data["ID"]); ?> />                           
                                <div>
                                    <h3>Are you sure you want to delete this job? This process cannot be reversed..</h3><hr>
                                </div>
                                <div>
                                    <button class="btn" type="submit" id="sendMessageButton" name ="delete" value=1>Go</button>
                                    <button class="btn" name ="delete" value=0>Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Deleter End -->
        <?php
    }
}

session_start ();

if (!isset ($_SESSION["admin"])) {
    header ("Location: index.php"); exit;
} else {
    if (isset ($_GET["id"])) {
        $jid = (int) $_GET["id"];
        if ($jid > 0) {  
            if (count (getRow ("jobs", "*", ["ID", $jid])) > 0) {
                $del = new DeleteJob ($_SESSION["admin"], $jid);
                $del->printPage();
            } else { 
                header ("Location: my_jobs.php"); exit;
            }
        } else {
            header ("Location: my_jobs.php"); exit;
        }
    } else {
        if (isset ($_POST["delete"])) {
            if ($_POST["delete"] == 1) {
                deleteRow ("jobs", $_POST["JID"]);
            }
        }
        header ("Location: my_jobs.php"); exit;
    }
}
?>
