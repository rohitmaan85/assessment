<?php
require_once 'header.php';
?>
<!DOCTYPE HTML>
<html>

<head>
  <title>BriskMindTest EMS</title>
  <script src="js/jquery.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/jquery.dataTables.min.js"></script>
  <script src="js/dataTables.responsive.js"></script>
  <script src="js/dataTables.bootstrap.min.js"></script>
  <script src="js/dataTables.responsive.min.js"></script>

  <script src="js/dataTables.buttons.min.js"></script>
  <script src="js/buttons.bootstrap.min.js"></script>
  <script src="js/jszip.min.js"></script>
  <script src="js/pdfmake.min.js"></script>
  <script src="js/vfs_fonts.js"></script>
  <script src="js/buttons.html5.min.js"></script>
  <script src="js/buttons.print.min.js"></script>
  <script src="js/buttons.colVis.min.js"></script>

  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css"  href="css/main.css">
  <link rel="stylesheet" type="text/css"  href="css/dataTables.bootstrap.css">
  <link rel="stylesheet" type="text/css"  href="css/dataTables.responsive.css">
  <link rel="stylesheet" type="text/css"  href="css/buttons.bootstrap.min.css">

  <script src="js/toggle.js"></script>
  <script src="js/manage_exams.js"></script>
  <script src="js/main.js"></script>

  </head>

<body>
  <!-- <script src="js/jquery-3.1.0.js"></script> -->

    <div class="loader"></div>
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
            <li><a  href="importSubjectPage.php"><span class="glyphicon glyphicon-collapse-down"></span>Import Subjects</a></li>
            <li><a  href="importQuestionsPage.php"><span class="glyphicon glyphicon-collapse-down"></span>Import Questions</a></li>
            <li><a  href="importBatchPage.php"><span class="glyphicon glyphicon-collapse-down"></span>Import Batches</a></li>
            <li><a  href="importStudentsPage.php"><span class="glyphicon glyphicon-collapse-down"></span>Import Students</a></li>
            <li><a  href="manageQuestionPage.php"><span class="glyphicon glyphicon-paperclip"></span>Manage Questions</a></li>
            <li><a  href="manageSubjectCategoriesPage.php"><span class="glyphicon glyphicon-paperclip"></span>Manage Subjects</a></li>
            <li><a  href="manageExamsPage.php"><span class="glyphicon glyphicon-pencil"></span>Manage Exams</a></li>
            <li><a  href="manageAttendencePage.php"><span class="glyphicon glyphicon-pencil"></span>Manage Batch Attendence</a></li>
            <li><a  href="createQuestionPage.php" ><span class="glyphicon glyphicon-pushpin"></span>Create Question</a></li>
            <li><a  href="createExamPage.php"><span class="glyphicon glyphicon-education"></span>Create Test</a></li>
            <li><a  href="createStudentPage.php"><span class="glyphicon glyphicon-user"></span>Create Student</a></li>
            <li><a  href="login.php"><span class="glyphicon glyphicon-log-in"></span>Login</a></li>
            <li><a  href="backupFPDF.php"><span class="glyphicon glyphicon-paperclip"></span>Reports</a></li>
          </ul>
        </div>
        <!-- /.navbar-collapse -->
      </nav>
    </div>
    <!-- Main Content -->
    <div class="container-fluid">
      <div class="side-body">




          <!--  Modal to display Exam details -->
          <div id="displayExamDetailsModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
              <!-- Modal content-->
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Exam Details</h4>
                </div>
                <div class="modal-body">
                  <form id="showExamDetailsForm" class="form-horizontal" >
                    <label for="namelbl"  >Exam Name :</label>
                    <input type="text" maxlength="4" id="name_text" class="form-control" disabled="true">

                    <label for="batch_lbl" >Batch Id :</label>
                    <input type="text" id="batch_text" class="form-control" disabled="true">

                    <label for="no_of_qstns_lbl" >Number of Questions :</label>
                    <input type="text" id="no_of_qstns_text" class="form-control" disabled="true">

                    <label for="duration_lbl" >Duration of Exam :</label>
                    <input type="text" id="duration_text" class="form-control" disabled="true">

                    <label for="exam_from_lbl">Exam Valid From :</label>
                    <input type="text" id="exam_from_text" class="form-control" disabled="true">

                    <label for="exam_to_lbl">Exam Valid To :</label>
                    <input type="text" id="exam_to_text" class="form-control" disabled="true">

                    <label for="total_marks_lbl">Total Marks :</label>
                    <input type="text" id="total_marks_text" class="form-control" disabled="true">

                    <label for="pass_percent_lbl">Passing Percentage :</label>
                    <input type="text" id="pass_percent_text" class="form-control" disabled="true">

                  </form>

                </div>

                 <div class="modal-footer">
                     <button type="button" class="btn btn-warning" data-dismiss="modal">Close Batch Information</button>
                </div>
              </div>
            </div>
          </div>


            <!--<div class="col-lg-14 col-md-11 col-sm-2 col-xs-12"> -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                         <h3 class="panel-title pull-left"><strong>Manage Exams</strong></h3>
                    </div>
                    <div class="panel-body">
                      <div id="confirm" class="modal fade" role="dialog">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Confirm Deletion</h4>
                              </div>
                              <div class="modal-body">
                                  <strong> <font color='Red'>Are you sure , you want to delete this Exam?</font></strong>
                            </div>
                        <div class="modal-footer">
                          <button type="button" data-dismiss="modal" class="btn btn-danger" id="delete">Delete</button>
                          <button type="button" data-dismiss="modal" class="btn">Cancel</button>
                        </div>
                        </div>
                        </div>
                      </div>

                      <div id="successMessage" class="modal fade" role="dialog">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Confirm Deletion</h4>
                              </div>
                              <div class="modal-body">

                                  <strong> <font color='Green'><span id="successMessageText">Exam deleted Successfully !</span></font></strong>
                            </div>
                        <div class="modal-footer">
                          <button type="button" data-dismiss="modal" class="btn btn-primary" id="delete">Delete</button>
                          <button type="button" data-dismiss="modal" class="btn">Cancel</button>
                        </div>
                        </div>
                        </div>
                      </div>


                      <div id="errorModal" class="modal fade" role="dialog">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Confirm Deletion</h4>
                              </div>
                              <div class="modal-body">

                                <strong> <font color="Red"><span id="errorMessageText">Error while deleting Exam , Please try again !</span></font></strong>

                            </div>
                        <div class="modal-footer">
                          <button type="button" data-dismiss="modal" class="btn btn-primary" id="delete">Delete</button>
                          <button type="button" data-dismiss="modal" class="btn">Cancel</button>
                        </div>
                        </div>
                        </div>
                      </div>

                      <div class="col-xs-14 col-md-14">
                        <form>
                          <div class="form-group">
                               <label for="ssc"  class="col-xs-1">SSC*</label>
                                 <div class="col-xs-4">
                                     <div class="dropdown">
                                       <button id="sscdropdownButton" class="btn btn-success dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                         --Select SSC--<span class="caret"></span></button>
                                          <ul id="ssctest-dropdown-menu"  class="dropdown-menu dropdown-menu-center scrollable-menu">
                                     </ul>
                                   </div>
                           </div>
                           <label for="jobrole"  class="col-xs-1">JobRole*</label>
                                   <div class="col-xs-6">
                                     <div class="dropdown">
                                         <button id="jobroledropdownButton" class="btn btn-success dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                             --Select JobRole--<span class="caret"></span></button>
                                                  <ul id="jobroletest-dropdown-menu"  class="dropdown-menu dropdown-menu-center scrollable-menu">
                                             </ul>
                                     </div>
                                     </div>
                            </div>
                         </form>
                      </div>
                    </div>
                  </div>
                  <div class="col-xs-14 col-md-14">
                      <br>
                  </div>
                  <div class="rowtable">
                        <div class="col-md-14">
                            <table id="exams" class="table table-striped table-bordered table-hover dt-responsive"  cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Exam Name</th>
                                            <th>BatchId</th>
                                            <th>Action</th>
                                    <!--
                                            <th>JobRole</th>
                                            <th>Qstns</th>
                                            <th>Dur</th>
                                            <th>Total Marks</th> -->
                                        </tr>
                                    </thead>
                                 </table>
                            </div>
                      </div>
                  </div>
                </div>
              </body>
</html>
