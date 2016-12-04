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
  <script src="js/manage_attendence.js"></script>
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
            <li><a  href="uploadSubjectPage.php"><span class="glyphicon glyphicon-collapse-down"></span>Import Subjects</a></li>
            <li><a  href="importQuestionsPage.php"><span class="glyphicon glyphicon-collapse-down"></span>Import Questions</a></li>
            <li><a  href="importBatchPage.php"><span class="glyphicon glyphicon-collapse-down"></span>Import Batches</a></li>
            <li><a  href="manageSubjectsPage.php"><span class="glyphicon glyphicon-paperclip"></span>Manage Questions</a></li>
            <li><a  href="manageExamsPage.php"><span class="glyphicon glyphicon-pencil"></span>Manage Exams</a></li>
            <li><a  href="manageAttendencePage.php"><span class="glyphicon glyphicon-pencil"></span>Manage Batch Attendence</a></li>
            <li><a  href="createQuestionPage.php" ><span class="glyphicon glyphicon-pushpin"></span>Create Question</a></li>
            <li><a  href="createExamPage.php"><span class="glyphicon glyphicon-education"></span>Create Test</a></li>
            <li><a  href="createStudentPage.php"><span class="glyphicon glyphicon-user"></span>Create Student</a></li>
            <li><a  href="login.php"><span class="glyphicon glyphicon-log-in"></span>Login</a></li>
            <li><a  href="login.php"><span class="glyphicon glyphicon-paperclip"></span>Reports</a></li>
          </ul>
        </div>
        <!-- /.navbar-collapse -->
        </div>
      </nav>
  </div>
    <!-- Main Content -->
    <div class="container-fluid">
      <div class="side-body">
            <!--<div class="col-lg-14 col-md-11 col-sm-2 col-xs-12"> -->
         <div class="panel panel-default">
                    <div class="panel-heading">
                         <h3 class="panel-title pull-middle"><strong>Manage Batch Attendence List</strong></h3>
                    </div>
               <div class="panel-body">
                <div class="col-xs-14 col-md-14">
                  <div id="displayStudentsModal" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                      <!-- Modal content-->
                      <div class="modal-content">
                        <div class="modal-body">
                            <table id="showStdntsTable" class="table table-striped table-bordered table-hover dt-responsive"  cellspacing="0" width="100%">
                                 <thead>
                                        <tr>
                                             <th>Name</th>
                                             <th>Enrollment Id</th>
                                             <th>Father Name</th>
                                         </tr>
                                 </thead>
                            </table>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div id="displayBatchDetailsModal" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                      <!-- Modal content-->
                      <div class="modal-content">

                        <div class="modal-body">


                            <label for="batchlbl"  class="col-xs-4 control-label">Batch Id :</label>

                            <div class="col-xs-2">  </div>
                            <div class="col-xs-8">
                                    <input type="text" maxlength="4" id="batch_text" class="form-control" disabled="true">
                            </div>

                             <!-- <div class="col-xs-2"></div> -->
                            <label for="exam_date_lbl" class="col-xs-4">Exam Date :</label>
                            <div class="col-xs-2">  </div>
                            <div class="col-xs-8">
                              <input type="text" id="exam_date_text" class="form-control" disabled="true">
                            </div>



                            <label for="no_stdents_lbl" class="col-xs-4">No. Of Students :</label>
                            <div class="col-xs-2">  </div>
                            <div class="col-xs-8">
                              <input type="text" id="no_students_text" class="form-control" disabled="true">
                            </div>


                            <label for="center_lbl" class="col-xs-4">Center Id / Address :</label>
                            <div class="col-xs-2">  </div>
                            <div class="col-xs-8">
                              <input type="text" id="center_text" class="form-control" disabled="true">
                            </div>



                            <label for="training_lbl" class="col-xs-4">Training Partner :</label>
                            <div class="col-xs-2">  </div>
                            <div class="col-xs-8">
                              <input type="text" id="training_text" class="form-control" disabled="true">
                            </div>



                            <label for="status_lbl" class="col-xs-4">Status :</label>
                            <div class="col-xs-2">  </div>
                            <div class="col-xs-8">
                              <input type="text" id="status_text" class="form-control" disabled="true">
                            </div>



                        </div>

                         <div class="modal-footer">
                           <div class="col-xs-6">  </div>
                           <div class="col-xs-8">
                             <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                           </div>
                        </div>
                      </div>
                    </div>
                  </div>



                  <div class="form-group">
                    <label for="ssc_label" class="col-xs-1">SSC *</label>
                      <div class="col-xs-4">
                        <div class="dropdown">
                          <button id="sscdropdownButton" class="btn btn-success dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Select    SSC
                            <span class="caret"></span></button>
                            <ul id="ssc-dropdown-menu"  class="dropdown-menu dropdown-menu-center scrollable-menu">
                            </ul>
                          </div>
                      </div>


                    <label for="jobrole_label" class="col-xs-1">Job Role*</label>
                    <div class="col-xs-6">
                          <div class="dropdown">
                            <button id="jobroledropdownButton" class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Select Job Role
                            <span class="caret"></span></button>
                             <ul id="jobrole-dropdown-menu" class="dropdown-menu dropdown-menu-center">
                            </ul>
                           </div>
                     </div>
                 </div>
                 <br>

              <form id="manageAttndnceForm" class="form-horizontal hide">
                <div id='createExamDiv' class="col-xs-14">
                <br>
                </div>
                <div id='createExamDiv' class="col-xs-14">
                <hr>
                </div>

                <div class="form-group">
                  <div class="form-group-inline required">
                    <label for="batch"  class="col-xs-2 control-label">Batch Id</label>
                  </div>
                 <div class="col-xs-3">
                       <div class="dropdown">
                           <button id="batchButton" class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                           -- Select Batch Id --<span class="caret"></span></button>
                            <ul id="batch-dropdown-menu"  class="dropdown-menu dropdown-menu-center scrollable-menu">
                           </ul>
                       </div>
                       <!--
                          <select class="form-control" id="examDurDropdown">
                          </select> -->
                    </div>
                     <!--<input type="text" id="examDuText" class="form-control" required> -->
                     <div class="col-xs-2"></div>
                      <label for="centerId" class="col-xs-2">Center Id and Address</label>
                    <div class="col-xs-3">

                      <div class="dropdown">
                          <button id="centerIdButton" class="btn btn-success dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          -- Select Center Id and Address --<span class="caret"></span></button>
                           <ul id="centerId-dropdown-menu"  class="dropdown-menu dropdown-menu-center scrollable-menu">
                          </ul>
                      </div>
                      </div>
                  </div>



                  <div class="form-group">
                    <div class="form-group-inline required">
                      <label for="trainingPartner"  class="col-xs-2 control-label">Training Partner</label>
                    </div>
                   <div class="col-xs-3">
                         <div class="dropdown">
                             <button id="trainingPartnerButton" class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                             -- Select Training Partner --<span class="caret"></span></button>
                              <ul id="trainingPartner-dropdown-menu"  class="dropdown-menu dropdown-menu-center scrollable-menu">
                             </ul>
                         </div>
                      </div>
                      <div class="col-xs-2"></div>
                      <label for="status" class="col-xs-2">Status</label>
                      <div class="col-xs-3">

                        <div class="dropdown">
                            <button id="statusButton" class="btn btn-success dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            -- Select Status --<span class="caret"></span></button>
                             <ul id="status-dropdown-menu"  class="dropdown-menu dropdown-menu-center scrollable-menu">
                            </ul>
                        </div>
                        </div>
                    </div>
               </form>

                <!-- Drop down End-->
              <div class="col-xs-14">
                <hr>
                <br>
                <table id="qstns" class="table table-striped table-bordered table-hover dt-responsive"  cellspacing="0" width="100%">
                           <thead>
                                 <tr>
                                   <th>BatchId</th>
                                   <th>Job Role</th>
                                   <th>Exam Date</th>
                                   <th>Action</th>
                                   <!-- <th>Center</th>
                                   <th>Action</th>
                                   <!--<th>Traiing Partner</th>
                                   <th>No Of  Students</th> -->
                                   </tr>
                                 </thead>
                              </table>
                          </div>
                      </div>
                  </div>
                </div>
          </body>

</html>
