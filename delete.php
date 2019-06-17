<?php
set_include_path(get_include_path() . PATH_SEPARATOR . $_SERVER["DOCUMENT_ROOT"]);

if (get_magic_quotes_gpc() == true) {
    foreach ($_REQUEST as $k => $v) {
        $_REQUEST[$k] = stripslashes($v);
    }

    foreach ($_COOKIE as $k => $v) {
        $_COOKIE[$k] = stripslashes($v);
    }
}
$fss_cookie = json_decode($_COOKIE["fss_cookie"], true);

?>
<!doctype html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta charset="utf-8">

        <meta name="author" content="Rich Whiting">
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>Document Delete</title>

        <!-- Force latest IE rendering engine or ChromeFrame if installed -->
        <!--[if IE]>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <![endif]-->

        <?php require_once "common/assets/assets_major.php"; ?>

        <!-- 
            APP-SPECIFIC LINKS 
        -->

    </head>

    <body id="main_body" >
        <img id="top" src="./images/common/top.png" alt="">
        <div id="container">
            <div id="form_container">
                <h1 style="background-color:#00336E;">Delete Documentation</h1>
                <?php
                $workorder = $_GET["workorder"];
                $project = $_GET["project"];
                $file = $_GET['file'];
                $row = $_GET['row'];
                $link = $_GET['link'];
                $deletepath = './Documentation/' . $project . '/' . $workorder . '/'; // The place the file will be removed from      

                $submit = $_POST['submit'];

                if ($submit) {
                    $file = $_POST['file'];
                    $row = $_POST['row'];
                    $link = $_POST['link'];
                    $deletepath = $_POST['deletepath'];
                    $project = $_POST['project'];
                    ?>
                    <form id="form_150059" class="appnitro" enctype="multipart/form-data" method="post" action="index.php">
                        <div class="form_description" align="center">
                            <img title="FederatedLogo" alt="Federated Logo" src="./images/logos/logo.jpg" border=0><p>
                        </div>	
                        <p>
                            <?php
                            $filerem = $deletepath . $file;
                            @unlink($filerem); //suppress the error in case of a refresh
                            @rmdir($deletepath);
                            @rmdir('./Documentation/' . $project . '/');

                            require_once './includes/QuickBase.php';

                            class DeleteRow {

                                public $qbUser = 'quickbase@federatedservice.com';
                                public $qbPass = '30955FSS1';
                                public $qbToken = 'cpnwmk8jgj3rqxjy5zbdw36u7f';
                                public $qbDoc = 'bf8syh8uj'; //the unique id to the documentation table created for this demo

                                public function __construct($row) {
                                    $qb = new QuickBase($this->qbUser, $this->qbPass, true, $this->qbDoc, $this->qbToken);
                                    $result = $qb->delete_record($row);
                                }
                            }

                            $federated = new DeleteRow($row);
                            ?>
                            The record containing the file "<?php echo $file; ?>" was successfully deleted.<br>
                        </p>
                    </form>

            </body>
                    </html>
                    <?php
                    exit;
                }
                ?>            
                <form id="form_150059" class="appnitro" enctype="multipart/form-data" method="post" action="delete.php">
                    <div class="form_description" align="center">
                        <img title="FederatedLogo" alt="Federated Logo" src="./images/logos/logo.jpg" border=0>
                        <span style="margin:auto auto;text-align:center">
                            <h3>Delete Upload</h3>
                            <div>
                                <br/>
                                <span style="text-align:center;font-size:1em;">
                                    <h5 style="font-size:1.4em;">Please confirm that you wish to delete "</h5>
                                    <h4><?php echo $file; ?>"</h4>
                                </span> 
                            </div>
                        </span>	
                        <ul>
                            <li class="nav-item buttons" align="center">
                                <input type="hidden" name="form_id" value="150059" />
                                <input type="hidden" name="deletepath" value="<?php echo $deletepath; ?>" />
                                <input type="hidden" name="file" value="<?php echo $file; ?>" />
                                <input type="hidden" name="project" value="<?php echo $project; ?>" />
                                <input type="hidden" name="row" value="<?php echo $row; ?>" />
                                <input id="saveForm" class="button_text" style="margin:auto auto;text-align:center;" type="submit" name="submit" value="Delete it!" />
                            </li>
                        </ul>
                        <div>
                            <?php
                            echo "<br/><br/>To download this file <a href=$link>Click Here</a>.<br/><br/><br/>";
                            ?>
                        </div>
                    </div>	
                </form>	
            </div>
        </div>

    </body>
</html> 