<?php
include ("admin.php");

class AlterJob extends Admin {
    public $data = array (); public $proj = array (); public $jtrows = array ();

    function __construct ($title, $admin, $JID) {
        parent::__construct ($title, $admin);
        $this->data = getRow ("jobs", "*", ["ID", $JID]);
        $this->proj = getRow ("projects", "*", ["ID", $this->data["PID"]]);
    }

    function printJobView () {
        ?> 
        <div class="col-md-6">
            <div class="contact-info">
                <div class="contact-item">
                    <!--<i class="flaticon-address"></i> -->
                    <div class="contact-text">
                        <h2 style = "font-size:14px"><u>
                        <?php 
                        echo (strtoupper("job details"));
                        ?>
                        </u></h2>
                        <?php
                            echo ('<p>Name: <a href = "job_edit.php?id='.$this->data["ID"].'"><u>'.strtoupper($this->data["name"]).'</u></a></p>'); 
                            $row = getRow ("jobtypes", "name", ["ID", $this->data["type"]]);
                            echo ('<p>Job Type: '.$row["name"].'</p>');
                            echo ('<p>Project: <a href = "project_edit.php?id='.$this->proj["ID"].'"><u>'.strtoupper($this->proj["name"]).'</u></a></p>');
                            echo ('<p>Vacancies: '.strtoupper($this->data["vac"]).'</p>'); 
                            echo ('<p>Wages: KES. '.$this->data["wages"].' /day</p>');
                            echo ('<p>Application Deadline: '.$this->data["eta"].'</p>');
                        ?>
                    </div>
                </div>  
                <div class="contact-item">
                    <!--<i class="flaticon-address"></i> -->
                    <div class="contact-text">                        
                        <?php 
                        echo ('<h2 style = "font-size:14px"><u>'.strtoupper("requirements").'</u></h2>');
                        echo ('<p>'.$this->data["req"].'</p>');
                        ?>                    
                    </div>
                </div>
                <div class="contact-item">
                    <!--<i class="flaticon-address"></i> -->
                    <div class="contact-text">
                        <h2 style = "font-size:14px"><u><?php echo (strtoupper("Uploaded by"));?></u></h2>
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
?>
