<?php
//ini_set('error_reporting', E_ALL);

//include("./php/lib/MPDF_5_7/MPDF57/mpdf.php");
include('./php/manageExams.php');
$testName   =   "rohit_12345";
$testId     =   5;
$obj = new manageExams();
$qstnDivs = $obj->getExamQuestionsDivs($testId,$testName);
$htmlContent='<html>
<head>
  <title>BriskMindTest EMS</title>
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>

    <script src="js/jquery.dataTables.min.js"></script>
    <script src="js/dataTables.responsive.js"></script>
    <script src="js/dataTables.bootstrap.js"></script>
    <script src="js/dataTables.responsive.min.js"></script>

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css"  href="css/main.css">
    <link rel="stylesheet" type="text/css"  href="css/dataTables.bootstrap.css">
    <link rel="stylesheet" type="text/css"  href="css/dataTables.responsive.css">

    <script src="js/toggle.js"></script>
    <script src="js/createQuestion.js"></script>
    <script src="js/validator.js"></script>
    <script src="js/main.js"></script>
    <script src="js/keyboard.js"></script>
    <link rel="stylesheet" type="text/css" href="css/keyboard.css" />

  </head>

<body>
  <div class="loader"></div>
  <!-- <script src="js/jquery-3.1.0.js"></script> -->

  <div class="row">
    <!-- uncomment code for absolute positioning tweek see top comment in css -->
    <div class="absolute-wrapper"> </div>
    <!-- Menu -->
    <div class="page-header">
      <h3 class="navbar-brand brand-name">
           <a href="login.php"><img class="img-responsive2"
           src="images/logo.png">      Brisk Mind Examination Management System !!! </a>
       </h3>
    </div>
    <div class="side-menu">

      <nav class="navbar navbar-default" role="navigation" >
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <!-- <div class="brand-wrapper"> -->
            <!-- Hamburger -->
            <button type="button" class="navbar-toggle"  data-toggle="collapse" data-target=".side-menu-container">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>

        <!-- Main Menu -->
        <div class="side-menu-container">
          <ul class="nav navbar-nav" >
            <li><a  href="uploadSubjectPage.php"><span class="glyphicon glyphicon-collapse-down"></span>Import Subjects</a></li>
            <li><a  href="importQuestionsPage.php"><span class="glyphicon glyphicon-collapse-down"></span>Import Questions</a></li>
            <li><a  href="importBatchPage.php"><span class="glyphicon glyphicon-collapse-down"></span>Import Batches</a></li>
            <li><a  href="manageSubjectsPage.php"><span class="glyphicon glyphicon-paperclip"></span>Manage Questions</a></li>
            <li><a  href="manageExamsPage.php"><span class="glyphicon glyphicon-pencil"></span>Manage Exams</a></li>
            <li><a  href="createQuestionPage.php" ><span class="glyphicon glyphicon-pushpin"></span>Create Question</a></li>
            <li><a  href="createExamPage.php"><span class="glyphicon glyphicon-education"></span>Create Test</a></li>
            <li><a  href="createStudentPage.php"><span class="glyphicon glyphicon-user"></span>Create Student</a></li>
            <li><a  href="login.php"><span class="glyphicon glyphicon-log-in"></span>Login</a></li>
            <li><a  href="login.php"><span class="glyphicon glyphicon-paperclip"></span>Reports</a></li>
          </ul>
        </div>
        <!-- /.navbar-collapse -->
      </nav>
    </div>
    <!-- Main Content -->
    <div class="container-fluid">
      <div class="side-body">
        <div class="panel panel-default">
          <div class="panel-heading">
                  <h4 id = "heading" class="form-signin-heading pull-left"><strong>Test Questionsstrong></h4>
          </div>
          <div class="panel-body">
              <div id="examQuestions" class="col-xs-14">
                <form id="createQuestionForm" class="form-horizontal" >
                  <!--  <div id="1" class="col-xs-14">
                      <p id="qstn_p">1. esttesttesttestttesttestesttesttesttesttestesttesttesttesttesttesttesttesttesttesttesttestttesttestteesttesttesttestttesttestesttesttesttesttestesttesttesttesttesttesttesttesttesttesttesttesttte
                      </p>
                      <div class="col-xs-14" id="optiona"><p id="qstn_p">  <input id="qstn_checkbox" type="checkbox">
                         test1
                      </p>
                      </div>
                       <div><br></div>
                      <div id="optionb"><p id="qstn_p"> <input id="qstn_checkbox" type="checkbox">
                           test2</p>
                      </div> <div><br></div>
                      <div id="optionc"><p id="qstn_p"> <input id="qstn_checkbox" type="checkbox">
                           test3</p>
                      </div> <div><br></div>
                      <div id="optiond"><p id="qstn_p"> <input id="qstn_checkbox" type="checkbox">
                              test4</p>
                      </div> <div><br></div>
                    </div> -->
                    <div id="examQuestions" class="col-xs-14"><hr></div>'.$qstnDivs.'
                </form>
            </div>
          </div>
      </div>
    </div>
  </div>
</body>
</html>';
echo $htmlContent;
?>
