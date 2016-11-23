<?php
$_GET['action']="edit";
if(isset($_GET['action'])){
   $action = $_GET['action'];
  // If action is "edit"
  if($action=="edit"){
     echo '<input type="hidden" value="yes" id="isEditable" name="isEditable"/>';
    // get Question id to edit
     $id      = $_GET['id'];
     $subId   = $_GET['subid'];
     $qstnId  = $_GET['qstnid'];
     echo '<input type="hidden" value="'.$id.'" id="id" name="id"/>';
     echo '<input type="hidden" value="'.$subId.'" id="subId" name="subId"/>';
     echo '<input type="hidden" value="'.$qstnId.'" id="qstnId" name="qstnId"/>';
  } else{
     echo '<input type="hidden" value="no" id="isEditable" name="isEditable"/>';
  }
}
?>


<html>
<head>
  <title>BriskMindTest EMS</title>

    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>

    <script src="js/jquery.dataTables.min.js"></script>
    <script src="js/dataTables.responsive.js"></script>
    <script src="js/dataTables.bootstrap.js"></script>
    <script src="js/dataTables.responsive.min.js"></script>
        <script src="js/moment.js"></script>
    <script src="js/bootstrap-datetimepicker.min.js"></script>
    <script src="js/toggle.js"></script>
    <script src="js/validator.js"></script>
    <script src="js/main.js"></script>
    <script src="js/keyboard.js"></script>
    <script src="js/manage_exams.js"></script>


    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css"  href="css/main.css">
    <link rel="stylesheet" type="text/css"  href="css/dataTables.bootstrap.css">
    <link rel="stylesheet" type="text/css"  href="css/dataTables.responsive.css">
    <link rel="stylesheet" type="text/css" href="css/keyboard.css" />
        <link rel="stylesheet" type="text/css" href="css/bootstrap-datetimepicker.min.css" />

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
           src="images/logo.png">    Brisk Mind Examination Management System. </a>
       </h3>
       <!--
       <blockquote>
         <br><br>
        <p>Skill India Assessment Module!.</p>
        <small>by <cite>Albert Einstein</cite></small>
      </blockquote>
    -->
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
          </div>
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
        <!-- /.navbar-collapse -->
       </div>
     </nav>
    </div>
    <!-- Main Content -->
    <div class="container-fluid">
      <div class="side-body">
            <div class="panel panel-default">
              <div class="panel-heading">
                 <h4 id = "heading" class="form-signin-heading pull-left"><strong>Add New Student</strong></h4>
                 </div>
               <div class="panel-body">
                 <!--  Start add Student -->
                <div id='createExamDiv' class="col-xs-18">
                  <form id="createExamForm" class="form-horizontal" >
                      <div class="col-xs-14" >
                       <div id="error_msg"  class="alert alert-danger fade" style="position:relative">
                         <button href="#" type="button" class="close">&times;</button>
                             <strong></strong>
                        </div>
                       </div>

                      <div class="form-group">
                         <label for="studentName"  class="col-xs-2">Name*</label>
                         <div class="col-xs-4"></div>
                         <label  class="col-xs-2" for="numberOfOption">Select Batch*</label>
                         <div class="col-xs-6">
                           <input type="text" id="studentNameText" class="form-control" placeholder="Enter Student Name" required>
                        </div>
                        <div class="col-xs-6">
                                  <select class="form-control" id="selBatchDropdown">
                                   <option>None Selected</option>
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
                                    <option>4</option>
                                  </select>
                        </div>
                      </div>

                      <div class="form-group">
                        <label  class="col-xs-2" for="address">Address *</label>
                        <div class="col-xs-12">
                            <input type="text" id="addressText" class="form-control" placeholder="Enter Address" required>
                        </div>
                      </div>

                      <div class="form-group">
                         <label for="phone"  class="col-xs-2">Phone*</label>
                         <div class="col-xs-4"></div>
                         <label  class="col-xs-2" for="guradian">Guardian Name*</label>
                         <div class="col-xs-6">
                           <input type="text" id="phoneText" class="form-control" placeholder="Enter Phone" required>
                        </div>
                        <div class="col-xs-6">
                            <input type="text" id="guradianText" class="form-control" placeholder="Enter Guardian Name" required>
                        </div>
                      </div>


                      <div class="form-group">
                         <label for="enrol"  class="col-xs-2">Enrolment Number*</label>
                         <div class="col-xs-4"></div>
                         <label  class="col-xs-2" for="guradian">Status*</label>
                         <div class="col-xs-6">
                           <input type="text" id="enrolmentText" class="form-control" placeholder="Enter Enrolment" required>
                        </div>
                        <div class="col-xs-6">
                          <select class="form-control" id="selStatusDropdown">
                            <option>Active</option>
                            <option>InActive</option>
                          </select>
                        </div>
                      </div>
                   <hr>
                   <div class="form-group">
                    <div id='newModeButton' class="form-group ">
                      <div class="span4 offset4 text-center">
                        <button id="addStudentButton"  type="button" class="btn btn-info"><span class="glyphicon glyphicon-floppy-saved"></span>    Add Student</button>
                        &nbsp;&nbsp;<button id="cancelExamButton"  type="button" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span>  Cancel</button>

                      </div>
                    </div>

                    <div id='editModeButton' class="form-group hide">
                      <div class="span4 offset4 text-center">
                        <button id="saveExamButton"  type="button" class="btn btn-info"><span class="glyphicon glyphicon-floppy-saved"></span>    Save  </button>
                        &nbsp;&nbsp;<button id="editResetExamButton"  type="button" class="btn btn-warning"><span class="glyphicon glyphicon-remove"></span>  Reset</button>
                        &nbsp;&nbsp;<button id="editCancelExamButton"  type="button" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span>  Close</button>
                      </div>
                    </div>
                     </div>

                    </form>
                  </div>
                 <!--  End Create Exam Form-->

                 <!--
                 <div class="col-md-8 center-block">
                    <button id="createQstnButton"  type="button" class="btn btn-info"><span class="glyphicon glyphicon-floppy-saved"></span>     Create Question</button>
                    <button id="resetQstnButton"  type="button" class="btn btn-warning"><span class="glyphicon glyphicon-remove"></span>  Reset</button>
                    <button id="cancelQstnButton"  type="button" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span>  Cancel</button>
                   </div>
               </div>
              </div>
            -->
            </div>
        <!-- </div> -->
      </div>
</body>
</html>
