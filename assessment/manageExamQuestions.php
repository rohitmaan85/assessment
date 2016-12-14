<?php
if(isset($_GET['action'])){
   $action = $_GET['action'];
  // If action is "edit"
  if($action=="manageExamQstn"){
     $examName      = $_GET['examName'];
     echo '<input type="hidden" value="'.$examName.'" id="examName" name="examName">';
    // echo '<input type="hidden" value="'.$getExamName.'" id="getExamName" name="getExamName">';
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
  <script src="js/dataTables.select.min.js"></script>


  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css"  href="css/main.css">
  <link rel="stylesheet" type="text/css"  href="css/dataTables.bootstrap.css">
  <link rel="stylesheet" type="text/css"  href="css/dataTables.responsive.css">
  <link rel="stylesheet" type="text/css"  href="css/buttons.bootstrap.min.css">
  <link rel="stylesheet" type="text/css"  href="css/select.dataTables.min.css">


  <script src="js/toggle.js"></script>
  <script src="js/main.js"></script>
  <script src="js/manage_exam_questions.js"></script>

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
           src="images/logo.png">     Brisk Mind Examination Management System !!!</a>
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
            <li><a  href="manageSubjectsPage.php"><span class="glyphicon glyphicon-paperclip"></span>Manage Questions</a></li>
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


        <div id="displayBatchDetailsModal" class="modal fade" role="dialog">
          <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Batch Details</h4>
              </div>
              <div class="modal-body">
                <form id="showDetailsForm" class="form-horizontal" >
                  <label for="batchlbl"  >Batch Id :</label>
                  <input type="text" maxlength="4" id="batch_text" class="form-control" disabled="true">

                  <label for="exam_date_lbl" >Exam Date :</label>
                  <input type="text" id="exam_date_text" class="form-control" disabled="true">

                  <label for="no_stdents_lbl" >No. Of Students :</label>
                  <input type="text" id="no_students_text" class="form-control" disabled="true">

                  <label for="center_lbl">Center Id / Address :</label>
                  <input type="text" id="center_text" class="form-control" disabled="true">

                  <label for="training_lbl">Training Partner :</label>
                  <input type="text" id="training_text" class="form-control" disabled="true">

                  <label for="status_lbl">Status :</label>
                  <input type="text" id="status_text" class="form-control" disabled="true">

                </form>

              </div>

               <div class="modal-footer">
                   <button type="button" class="btn btn-warning" data-dismiss="modal">Close Batch Information</button>
              </div>
            </div>
          </div>
        </div>




          <div class="panel panel-default">
              <div class="panel-heading">
                 <h4 id = "heading" class="form-signin-heading pull-left"><strong>Manage Exam Questions </strong></h4>
                 </div>

                 <div id="displayQstnModal" class="modal fade" role="dialog">
                   <div class="modal-dialog">
                     <!-- Modal content-->
                     <div class="modal-content">
                       <div class="modal-body">
                         <textarea rows="5" cols="4" id="qstnCompleteVal" class="form-control" disabled="true"></textarea>
                       </div>
                       <div class="modal-footer">
                         <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                       </div>
                     </div>

                   </div>
                 </div>
               <div class="panel-body">
                 <div id="alertModal" class="modal fade" role="dialog">
                   <div class="modal-dialog">
                     <!-- Modal content-->
                     <div class="modal-content">
                       <div class="modal-header">
                         <button type="button" class="close" data-dismiss="modal">&times;</button>
                         <h4 class="modal-title">Mandatory Fields Missing !!</h4>
                       </div>
                       <div class="modal-body">
                        <strong> <textarea  style="color: red;"  id="alertMessage"  disabled="true"rows="5" cols="4" class="form-control"></textarea>
                        </strong>
                         </div>
                       <div class="modal-footer">
                         <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                       </div>
                     </div>
                   </div>
                 </div>

                 <!--  Start Create Exam <Form-->

            <form id="manageExamQuestionsForm" class="form-horizontal" >
              <div id='manageExamQuestionDiv' class="col-xs-14">
                    <div class="col-xs-14 " >
                       <div id="error_msg"  class="alert alert-danger fade" style="position:relative">
                         <button href="#" type="button" class="close">&times;</button>
                             <strong></strong>
                        </div>
                    </div>

                    <div class="form-group">
                       <div class="form-group-inline required">
                         <label for="examName"  class="col-xs-2 control-label">Exam Name </label>
                       </div>
                         <div class="col-xs-3">
                           <input type="text" id="examNameText" class="form-control" disabled="true" >
                         </div>
                         <div class="col-xs-2"></div>
                         <div class="form-group-inline required">
                           <label for="noOfQstns"  class="col-xs-2 control-label">No. of Questions </label>
                         </div>
                         <div class="col-xs-3">
                           <input type="text" id="noOfQstnsText" class="form-control" disabled="true">
                        </div>
                   </div>

                   <div class="form-group">
                      <div class="form-group-inline required">
                        <label for="batch"  class="col-xs-2 control-label">Batch Name </label>
                      </div>
                        <div class="col-xs-3">
                          <input type="text" id="batchText" class="form-control" disabled="true" >
                        </div>
                        <div class="col-xs-2"></div>
                        <div class="form-group-inline required">
                          <label for="examDate"  class="col-xs-2 control-label">Exam Date</label>
                        </div>
                        <div class="col-xs-3">
                          <input type="text" id="examDateText" class="form-control" disabled="true">
                       </div>
                  </div>

                  <div class="form-group">
                     <div class="form-group-inline required">
                       <label for="duration"  class="col-xs-2 control-label">Duration </label>
                     </div>
                       <div class="col-xs-3">
                         <input type="text" id="durationText" class="form-control" disabled="true">
                       </div>
                       <div class="col-xs-2"></div>
                       <div class="form-group-inline required">
                         <label for="tm"  class="col-xs-2 control-label">Total Marks</label>
                       </div>
                       <div class="col-xs-3">
                         <input type="text" id="tmText" class="form-control" disabled="true">
                      </div>
                </div>

                <div class="form-group">
                   <div class="form-group-inline required">
                     <label for="validFrom"  class="col-xs-2 control-label">Valid From Date</label>
                   </div>
                     <div class="col-xs-3">
                       <input type="text" id="validFromText" class="form-control" disabled="true">
                     </div>
                     <div class="col-xs-2"></div>
                     <div class="form-group-inline required">
                       <label for="validTo"  class="col-xs-2 control-label">Valid To Date </label>
                     </div>
                     <div class="col-xs-3">
                       <input type="text" id="validToText" class="form-control" disabled="true">
                    </div>
               </div>

               <div class="form-group">
                  <div class="form-group-inline required">
                    <label for="ssc"  class="col-xs-2 control-label">SSC</label>
                  </div>
                    <div class="col-xs-3">
                      <input type="text" id="sscText" class="form-control" disabled="true" >
                    </div>
                    <div class="col-xs-2"></div>
                    <div class="form-group-inline required">
                      <label for="Jobrole"  class="col-xs-2 control-label">Job Role</label>
                    </div>
                    <div class="col-xs-3">
                      <input type="text" id="JobroleText" class="form-control" disabled="true"">
                   </div>
              </div>

                   </div>

                    </form>
                    <div class="col-xs-14">
                      <hr>
    <table id="qstns" class="table table-striped table-bordered table-hover dt-responsive"  cellspacing="0" width="100%">
               <thead>
                     <tr>
                           <th>Category</th>
                           <th>Module</th>
                           <th>Question</th>
                           <th>Option A</th>
                           <th>Option B</th>
                           <th>Option C</th>
                           <th>Option D</th>
                           <th>Answer</th>
                           <th>Action</th>
                           <th>marks</th>
                           <th>id</th>
                       </tr>
                     </thead>
                  </table>
  </div>
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
