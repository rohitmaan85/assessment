<?php
require_once 'header.php';


$_GET['action']="edit";
if(isset($_GET['action'])){
   $action = $_GET['action'];
  // If action is "edit"
  if($action=="edit"){
     echo '<input type="hidden" value="yes" id="isEditable" name="isEditable"/>';
    // get Question id to edit
     $exam_id      = $_GET['id'];
     echo '<input type="hidden" value="'.$exam_id.'" id="getExamName" name="getExamName">';
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
    <script src="js/create_exams.js"></script>


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

        <div id="displayStudentsModal" class="modal fade" role="dialog">
          <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Students List</h4>
              </div>
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
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Batch Details</h4>
              </div>
              <div class="modal-body" id="batchDetailBody">
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

        <!--  Start Create Exam <Form-->
  <div class="panel panel-info">
          <div class="panel-heading">
                   Select Batch Details :
          </div>
      <div class="panel-body">
        <form id="selectDetail" class="form-horizontal" >
          <div id='selectDetailDiv' class="col-xs-14">
           <div class="form-group">
            <label for="ssc"  class="col-xs-2">SSC*</label>
             <div class="col-xs-4">
                 <div class="dropdown">
                   <button id="sscdropdownButton" class="btn btn-success dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                     --Select SSC--<span class="caret"></span></button>
                      <ul id="ssctest-dropdown-menu"  class="dropdown-menu dropdown-menu-center scrollable-menu">
                 </ul>
               </div>
       </div>
       <label for="jobrole"  class="col-xs-1">JobRole*</label>
               <div class="col-xs-5">
                 <div class="dropdown">
                     <button id="jobroledropdownButton" class="btn btn-success dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                         --Select JobRole--<span class="caret"></span></button>
                              <ul id="jobroletest-dropdown-menu"  class="dropdown-menu dropdown-menu-center scrollable-menu">
                         </ul>
                 </div>
                 </div>
        </div>
        <div class="form-group">
               <label for="batch"  class="col-xs-2">Select Batch Id *</label>
                   <div class="col-xs-4">
                           <div class="dropdown">
                             <button id="batchdropdownButton" class="btn btn-success dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                               -- Select Batch Id --<span class="caret"></span></button>
                                <ul id="batch-dropdown-menu"  class="dropdown-menu dropdown-menu-center scrollable-menu">
                           </ul>
                         </div>
                 </div>
                 <div class="col-xs-3">
                      <button type="button" id="batch_info" class="btn btn-info" disabled="true">Batch Details</button>
                      </div>
                    <div class="col-xs-3">
                      <button type="button" id="studentDetails"  class="btn btn-primary" disabled="true">Student Details</button>
                     </div>
                </div>
              </div>
            </div>
          </form>
          </div>

          <div id="successMessage" class="modal fade" role="dialog">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h4 class="modal-title">Status</h4>
                    </div>
                    <div class="modal-body">
                        <strong> <font color='Green'><span id="successMessageText">Exam deleted Successfully !</span></font></strong>
                  </div>
              <div class="modal-footer">
                  <button type="button" data-dismiss="modal" class="btn">Cancel</button>
              </div>
              </div>
              </div>
          </div>

          <div id="errorMessage" class="modal fade" role="dialog">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h4 class="modal-title">Error</h4>
                    </div>
                    <div class="modal-body">
                        <strong> <font color='Green'><span id="errorMessageText"></span></font></strong>
                  </div>
              <div class="modal-footer">
                  <button type="button" data-dismiss="modal" class="btn">Cancel</button>
              </div>
              </div>
              </div>
          </div>

          <div class="panel panel-default">
               <div class="panel-heading">
                 <h2 class="panel-title pull-left"><strong>Create New Exam</strong></h2>
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

            <form id="createExamForm" class="form-horizontal hide" >
              <div id='createExamDiv' class="col-xs-14">
                    <div class="form-group">
                       <div class="form-group-inline required">
                         <label for="examName"  class="col-xs-2 control-label">Exam Name</label>
                       </div>
                         <div class="col-xs-3">
                           <input type="text" id="examNameText" class="form-control required" placeholder="Enter Exam Name" required="required">
                         </div>
                         <div class="col-xs-2"></div>
                         <div class="form-group-inline required">
                           <label for="noOfQstns"  class="col-xs-2 control-label">Number of Questions</label>
                         </div>
                         <div class="col-xs-3">
                           <input type="text" id="noOfQstnsText" class="form-control" placeholder="Number of Question" maxlength="3" required>
                        </div>
                      </div>



                       <div class="form-group">
                         <div class="form-group-inline required">
                           <label for="examDur"  class="col-xs-2 control-label">Exam Duration(Min.)</label>
                         </div>
                        <div class="col-xs-3">
                              <div class="dropdown">
                                  <button id="examDurButton" class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                  --Select Duration--<span class="caret"></span></button>
                                   <ul id="examdur-dropdown-menu"  class="dropdown-menu dropdown-menu-center scrollable-menu">
                                  </ul>
                              </div>
                              <!--
                                 <select class="form-control" id="examDurDropdown">
                                 </select> -->
                           </div>
                            <!--<input type="text" id="examDuText" class="form-control" required> -->
                            <div class="col-xs-2"></div>
                             <label for="examStart" class="col-xs-2">Attempt Count</label>
                           <div class="col-xs-3">

                             <div class="dropdown">
                                 <button id="atmptCountButton" class="btn btn-success dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                 --Select Attempt--<span class="caret"></span></button>
                                  <ul id="atmptCount-dropdown-menu"  class="dropdown-menu dropdown-menu-center scrollable-menu">
                                 </ul>
                             </div>
                             </div>
                         </div>
                         <div class="form-group">
                           <div class="form-group-inline required">
                             <label for="startDate"  class="col-xs-2 control-label">Start Date</label>
                           </div>
                         <div class="col-xs-3">
                             <div class='input-group date' id='startDate'>
                                 <input type='text' class="form-control" />
                                 <span class="input-group-addon">
                                     <span class="glyphicon glyphicon-calendar"></span>
                                 </span>
                             </div>
                            </div>

                            <div class="col-xs-2"></div>
                            <div class="form-group-inline required">
                              <label for="examEnd"  class="col-xs-2 control-label">End Date</label>
                            </div>

                           <div class="col-xs-3">
                            <div class='input-group date' id='endDate'>
                                 <input type='text' class="form-control" />
                                 <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                               </span>
                               </div>
                          </div>
                          </div>
                          <div class="form-group">
                             <label for="decResult" class="col-xs-2">Declare Result</label>
                               <div class="col-xs-3">
                                  <div class="btn-group" id="decResultRadio" data-toggle="buttons">
                                    <label class="radio-inline">
                                     <input name="radioGroup" id="radio1" value="yes" type="radio"> Yes
                                    </label>
                                    <label class="radio-inline">
                                     <input name="radioGroup" id="radio2" value="no" checked="checked" type="radio"> No
                                    </label>
                                 </div>
                              </div>
                               <div class="col-xs-2"></div>
                               <div class="form-group-inline required">
                                 <label for="batch"  class="col-xs-2 control-label">Select Batch</label>
                               </div>
                              <div class="col-xs-2">
                                   <select class="form-control" id="selGroupDropdown">
                                    <option>None Selected</option>
                                    </select>
                             </div>
                           </div>
                            <div class="form-group">
                               <label for="negMarking" class="col-xs-2">Negative Marking</label>
                                 <div class="col-xs-3">
                                    <div class="btn-group" id="negMarkingRadio" data-toggle="buttons">
                                      <label class="radio-inline">
                                       <input name="radioGroup1" id="radio1" value="yes" type="radio"> Yes
                                      </label>
                                      <label class="radio-inline">
                                       <input name="radioGroup1" id="radio2" value="no" checked="checked" type="radio"> No
                                      </label>
                                   </div>
                                </div>
                                 <div class="col-xs-2"></div>
                                 <label for="radomQstn" class="col-xs-2">Random Question</label>
                                   <div class="col-xs-3">
                                      <div class="btn-group" id="radomQstnRadio" data-toggle="buttons">
                                        <label class="radio-inline">
                                         <input name="radioGroup2" id="radio1" value="yes" type="radio"> Yes
                                        </label>
                                        <label class="radio-inline">
                                         <input name="radioGroup2" id="radio2" value="no" checked="checked" type="radio"> No
                                        </label>
                                     </div>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <div class="form-group-inline required">
                                    <label for="totalMarks"  class="col-xs-2 control-label">Total Marks</label>
                                  </div>
                                  <div class="col-xs-3">
                                          <input type="text" maxlength="4" id="totalMarksText" class="form-control" placeholder="Total Marks" required>
                                  </div>
                                  <div class="col-xs-2"></div>
                                    <label for="pasingPer" class="col-xs-2">Passing Percent</label>
                                     <div class="col-xs-3">
                                       <div class="dropdown">
                                           <button id="passPercentButton" class="btn btn-success dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                           --Select Percent--<span class="caret"></span></button>
                                            <ul id="passpercent-dropdown-menu"  class="dropdown-menu dropdown-menu-center scrollable-menu">
                                           </ul>
                                       </div>
                                      <!--  <input type="text" id="passPercentText" class="form-control" required>  </div> -->
                                   </div>
                                  </div>

                                <div class="form-group">
                                  <div class="form-group-inline required">
                                    <label for="enterDesc"  class="col-xs-2 control-label">Exam Instruction</label>
                                  </div>

                                <div class="col-xs-12">
                                       <div id="hindiTextArea" class="textarea.form-control hide">
                                         <script language="javascript">
                                             CreateHindiTextArea("hindiTextQstn");
                                         </script>
                                       </div>
                                        <textarea rows="5" cols="4" class="form-control" id="examInstArea" placeholder="Exam Instruction" required></textarea>
                                      </div>
                                </div>
                                <div class="form-group">
                                 <label for="showModules" class="col-xs-3">Show Categories\Modules</label>
                                    <input id="showModules-checkbox" type="checkbox">
                                 <div class="col-xs-12"> </div>
                                </div>
                                <div id='showModuleDiv' class="col-xs-14">
                        </div
                  <div class="form-group">
                    <div class="form-group-inline required">
                        <label for="MarksForEachQuestion"  class="col-xs-5 control-label">Marks for each Questions in Exam</label>
                        </div>
                        <div class="col-xs-5">
                                <input type="text" maxlength="4" id="marksForEachQuestionText" class="form-control" disabled=true placeholder="Marks for each Question" required>
                        </div>
                 </div>

                   <hr>
                   <br>
                   <div class="form-group">
                    <div id='newModeButton' class="form-group ">
                      <div class="span4 offset4 text-center">
                        <button id="createExamButton"  type="button" class="btn btn-info"><span class="glyphicon glyphicon-floppy-saved"></span>    Create Exam</button>
                        &nbsp;&nbsp;<button id="newResetExamButton"  type="button" class="btn btn-warning"><span class="glyphicon glyphicon-remove"></span>  Reset</button>
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
