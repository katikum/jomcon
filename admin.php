<?php
include ("../site/HTMLPage.php");
include ("../site/classes/DB.php");

class Admin extends HTMLPage {
    function __construct ($title, $admin) {
        parent::__construct ($title, "");
        $this->title = "Jomcon Construction Limited - Admin - ".$this->name;
        $this->keywords = "jomcon/cpanel8_";
        $this->admin = $admin;
    }

    function printPage () {
        echo ('<!DOCTYPE html>
        <html lang="en">');
        $this->printHead();
        echo ('<body><div class="wrapper">');
        $this->printTopBar();
        $this->printNavBar();
        $this->printHeader();
        $this->printBody();
        $this->printFooter();
        echo ('</div>'); // Wrapper End
        $this->printJS();
        echo ('</body></html>');

    }

    function printBody () {
        echo ("Children override and implement");
    }

    function printNavBar ($active = "") {
        ?> 
        
        <!-- Nav Bar Start -->
        <div class="nav-bar">
            <div class="container-fluid">
                <nav class="navbar navbar-expand-lg bg-dark navbar-dark">
                    <a href="#" class="navbar-brand">MENU</a>
                    <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                        <div class="navbar-nav mr-auto">
                            <a href="../site/" class="nav-item nav-link">Site |</a>
                            <div class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Projects</a>
                                <div class="dropdown-menu">
                                    <a href="project_upload.php" class="dropdown-item">New Project</a>
                                    <a href="my_projects.php" class="dropdown-item">My Projects</a>
                                </div>
                            </div>
                            <div class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Jobs</a>
                                <div class="dropdown-menu">
                                    <a href="job_upload.php" class="dropdown-item">Upload Job</a>
                                        
                                    <a href="my_jobs.php" class="dropdown-item">Job Uploads</a>
                                    <a href="applications.php" class="dropdown-item">Job Applications</a>
                                </div>
                            </div>
                        </div>
                        <div class="ml-auto">
                            <a class="btn" href="logout.php">Logout</a>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
        <!-- Nav Bar End -->

        <?php
    }

    function printTopBar () {
        ?> 
        
        <!-- Top Bar Start -->
        <div class="top-bar">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-lg-4 col-md-12">
                        <div class="logo">
                            <a href="../site/">
                            <h3 style="color: white"><a href="index.php" style = "color: #030f27">jomcon</a> construction ltd.</h3>
                                <!-- <img src="img/building.jpg" alt="Logo"> -->
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-7 d-none d-lg-block">
                        <div class="row">
                            <div class="col-4">
                                <div class="top-bar-item">
                                    <div class="top-bar-icon">
                                        <i class="flaticon-calendar"></i>
                                    </div>
                                    <div class="top-bar-text">
                                        <h3>Logged in as: <?php echo ('<u>'.$this->admin["username"].'</u>'); ?></h3>
                                        <p><?php echo ($this->admin["name"]); ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="top-bar-item">
                                    <div class="top-bar-icon">
                                        <i class="flaticon-call"></i>
                                    </div>
                                    <div class="top-bar-text">
                                        <h3>Phone</h3>
                                        <p>+254<?php echo ($this->admin["phone"]); ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="top-bar-item">
                                    <div class="top-bar-icon">
                                        <i class="flaticon-send-mail"></i>
                                    </div>
                                    <div class="top-bar-text">
                                        <h3>Email</h3>
                                        <p><?php echo ($this->admin["email"]); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Top Bar End -->

        <?php
    }

    function printFooter () {
        ?>        
        <!-- Footer Start -->
        <div class="footer wow fadeIn" data-wow-delay="0.3s">            
            <div class="container copyright">
                <div class="row">
                    <div class="col-md-6">
                        <p>&copy; <a href="#">Jomcon Construction Limited. </a>All Rights Reserved.</p>
                    </div>
                    <div class="col-md-6">
                        <p>Designed by <a href="../dev/">webdevs_ke</a></p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer End -->
        <a href="#" class="back-to-top"><i class="fa fa-chevron-up"></i></a>
        <?php
    }

    function printProjectView ($data) {
        $jobs = getRows ("jobs", "name, vac", ["PID", $data["ID"]]);
        ?> 
        <div class="col-md-6">
            <div class="contact-info">
                <div class="contact-item">
                    <!--<i class="flaticon-address"></i> -->
                    <div class="contact-text">
                        <h2 style = "font-size:14px"><u>
                        <?php 
                        echo (strtoupper(getRow ("projecttypes", "name", ["ID", $data["type"]])["name"]." Project"));

                        ?>
                        </u></h2>
                        <?php
                            echo ('<p>Name: <a href = "project_edit.php?id='.$data["ID"].'"><u>'.strtoupper($data["name"]).'</u></a></p>'); 
                            echo ('<p>Client: '.strtoupper($data["client"]).'</p>'); 
                            echo ('<p>County: '.strtoupper(getRow ("counties", "name", ["ID", $data["county"]])["name"]).'</p>');
                            echo ('<p>Job Posts: '.count ($jobs).'</p>');
                        ?>
                    </div>
                </div>  
                <div class="contact-item">
                    <!--<i class="flaticon-address"></i> -->
                    
                    <div class="contact-text">
                        <h2 style = "font-size:14px"><u><?php echo (strtoupper("jobs"));?></u></h2>
                        <?php
                        $i = 0;
                        foreach ($jobs as $job) {
                            echo ('<p>'.++$i.'. '.$job["name"].' - '.$job["vac"].' vacancies'.'</p>'); 
                        }
                        
                        ?>
                    </div>
                </div>
                <div class="contact-item">
                    <!--<i class="flaticon-address"></i> -->
                    <div class="contact-text">
                        <h2 style = "font-size:14px"><u><?php echo (strtoupper("Uploaded by"));?></u></h2>
                            <?php 
                            $uploader = uploader ($data['AID']);
                            echo ('<p>Admin: '.strtoupper($uploader["name"]).'</p>');    
                            echo ('<p>Date: '.$data["uploaddate"].'</p>'); 
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