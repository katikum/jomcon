<?php
include ("admin.php");

class Login extends Admin {
    function __construct () {
        parent::__construct ("Login", "");
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
                </div>
            </div>
        </div>
        <!-- Top Bar End -->
        <?php
    }

    function printBody () {
        ?>        
        <!-- Login Start -->
        <div class="contact wow fadeInUp">
            <div class="container">
                <div class="section-header text-center">
                    <p>Restricted: Administrator Only</p>
                    <h3></h3>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="contact-info">
                            <div class="contact-item">
                                <!--<i class="flaticon-address"></i> -->
                                <div class="contact-text">
                                    <h2>Upload Projects</h2>
                                    <p>Log into admin panel to upload new projects</p>
                                </div>
                            </div>
                            <div class="contact-item">
                                
                                <div class="contact-text">
                                    <h2>Upload Jobs</h2>
                                    <p>Update projects with upcoming jobs</p>
                                </div>
                            </div>
                            <div class="contact-item">
                                
                                <div class="contact-text">
                                    <h2>Review Company Data</h2>
                                    <p>Login to edit and review your uploads</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="contact-form">
                            <div id="success"></div>
                            <form name="sentMessage" id="contactForm" novalidate="novalidate" method="POST" action="index.php">
                                <div class="control-group">
                                    <input type="text" name = "username" class="form-control" id="name" placeholder="username" required="required" data-validation-required-message="username required" />
                                    <p class="help-block text-danger"></p>
                                </div>
                                
                                <div class="control-group">
                                    <input type="password" name = "password" class="form-control" id="subject" placeholder="password" required="required" data-validation-required-message="password required" />
                                    <p class="help-block text-danger"></p>
                                </div>
                                
                                <div>
                                    <button class="btn" type="submit" id="sendMessageButton">Login</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Login End -->
        <?php
    }
}
$login = new Login;

if (isset($_POST["username"]) && isset($_POST["password"])) {
    $rows = getRows ("admin", "*");

    foreach ($rows as $row) {
        if (($row["username"] == $_POST["username"]) && ($row["password"] == $_POST["password"])) {
            session_start();  
            if($_SESSION["admin"] == null) {
                $_SESSION["admin"] = $row;
            }  
        } 
    }
    if (!isset($_SESSION["admin"])) { //login unsuccessful
        $login->printPage();
        echo ('<script type = "text/javascript"> alert ("Invalid username or password. Try again!"); </script>');
    } else {
        header ("Location: my_projects.php"); exit;
    }
} else {
    $login->printPage();
}
?>