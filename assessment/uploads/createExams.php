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
  <title>Online Portal System</title>

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
  <!-- <script src="js/jquery-3.1.0.js"></script> -->
  <script src="js/main.js"></script>
  <div class="row">
    <!-- uncomment code for absolute positioning tweek see top comment in css -->
    <div class="absolute-wrapper"> </div>
    <!-- Menu -->
    <div class="page-header">
    <h2> Control panel for handlng Data</h2>
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
            <li><a  href="import.php"><span class="glyphicon glyphicon-send"></span>Import Subjects</a></li>
            <li><a  href="manageSubjects.php"><span class="glyphicon glyphicon-send"></span>Manage Subjects</a></li>
            <li><a  href="manageExams.php"><span class="glyphicon glyphicon-send"></span>Manage Exams</a></li>
            <li><a  href="login.php" ><span class="glyphicon glyphicon-cloud"></span>Users</a></li>
            <li><a  href="login.php"><span class="glyphicon glyphicon-send"></span>Courses</a></li>
             <li><a  href="createQuestion.php"><span class="glyphicon glyphicon-plane"></span>Questions</a></li>
            <li><a  href="login.php"><span class="glyphicon glyphicon-send"></span>Reports</a></li>
            <li><a  href="login.php"><span class="glyphicon glyphicon-plane"></span>Active Link</a></li>
          </ul>
        </div>
        <!-- /.navbar-collapse -->
      </nav>
    </div>
    <!-- Main Content -->
    <div class="container-fluid">
      <div class="side-body">
        <div class="row">
              <div class="panel panel-default">
              <div class="panel-heading">
                 <h4 id = "heading" class="form-signin-heading">Create New Exam</h4>
                 </div>
               <div class="panel-body">
                 <!--  Start Create Exam <Form--></Form-->
                  <div class="row">
                  <div id='createExamDiv' class="col-xs-14">
                  <form id="createExamForm" class="form-horizontal" >
                      <div class="col-xs-12 " >
                       <div id="error_msg"  class="alert alert-danger fade" style="position:relative">
                         <button href="#" type="button" class="close">&times;</button>
                             <strong></strong>
                        </div>
                       </div>

                       <div class="form-group">
                          <label for="subjectName"  class="col-xs-2">Subject</label>
                          <div class="col-xs-5">
                            <div class="dropdown">
                                <button id="subNameButton" class="btn btn-success dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                --Select Subject--<span class="caret"></span></button>
                                 <ul id="subname-dropdown-menu"  class="dropdown-menu dropdown-menu-center scrollable-menu">
                                </ul>
                            </div>
                          </div>
                             <div class="col-xs-1"></div>
                           <label for="qpCode" class="col-xs-1">QPCode</label>
                           <div class="col-xs-2">
                             <input type="text" id="qpcodeText" class="form-control" placeholder="QPCode" disabled="true" required>  </div>
                            <!--  <input type="text" id="passPercentText" class="form-control" required>  </div> -->
                         </div>

                         <hr>


                      <div class="form-group">
                         <label for="examName"  class="col-xs-2">Exam Name</label>
                         <div class="col-xs-3">
                           <input type="text" id="examNameText" class="form-control" placeholder="Enter Exam Name" required>  </div>

                            <div class="col-xs-1"></div>
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
                           <label for="enterQuestion" class="col-xs-2">Exam Instruction</label>
                           <div class="col-xs-9">
                             <div id="hindiTextArea" class="textarea.form-control hide">
                               <script language="javascript">
                                   CreateHindiTextArea("hindiTextQstn");
                               </script>
                             </div>
                              <textarea rows="5" cols="4" class="form-control" id="examInstArea" placeholder="Exam Instruction" required></textarea>
                            </div>
                         </div>

                       <div class="form-group">
                          <label for="examDur" class="col-xs-2">Exam Duration(Min.)</label>
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
                            <div class="col-xs-1"></div>
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
                           <label for="examStart" class="col-xs-2">Start Date</label>

                           <div class="col-xs-3">
                             <div class='input-group date' id='startDate'>
                                 <input type='text' class="form-control" />
                                 <span class="input-group-addon">
                                     <span class="glyphicon glyphicon-calendar"></span>
                                 </span>
                             </div>
                            </div>

                            <div class="col-xs-1"></div>

                            <label for="examEnd" class="col-xs-2">End Date</label>

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
                                     <input name="radioGroup" id="radio1" value="1" type="radio"> Yes
                                    </label>
                                    <label class="radio-inline">
                                     <input name="radioGroup" id="radio2" value="2" checked="checked" type="radio"> No
                                    </label>
                                 </div>
                              </div>
                               <div class="col-xs-1"></div>
                             <label  class="col-xs-2" for="numberOfOption">Select Group</label>
                              <div class="col-xs-3">
                                   <select class="form-control" id="selGroupDropdown">
                                    <option>None Selected</option>
                                     <option>1</option>
                                     <option>2</option>
                                     <option>3</option>
                                     <option>4</option>
                                   </select>
                             </div>
                           </div>
                            <div class="form-group">
                               <label for="negMarking" class="col-xs-2">Negative Marking</label>
                                 <div class="col-xs-3">
                                    <div class="btn-group" id="negMarkingRadio" data-toggle="buttons">
                                      <label class="radio-inline">
                                       <input name="radioGroup1" id="radio1" value="1" type="radio"> Yes
                                      </label>
                                      <label class="radio-inline">
                                       <input name="radioGroup1" id="radio2" value="2" checked="checked" type="radio"> No
                                      </label>
                                   </div>
                                </div>
                                 <div class="col-xs-1"></div>
                                 <label for="radomQstn" class="col-xs-2">Random Question</label>
                                   <div class="col-xs-3">
                                      <div class="btn-group" id="radomQstnRadio" data-toggle="buttons">
                                        <label class="radio-inline">
                                         <input name="radioGroup2" id="radio1" value="1" type="radio"> Yes
                                        </label>
                                        <label class="radio-inline">
                                         <input name="radioGroup2" id="radio2" value="2" checked="checked" type="radio"> No
                                        </label>
                                     </div>
                                  </div>
                                </div>
                                <div class="form-group">
                                   <label for="radomQstn" class="col-xs-2">Result After Finish</label>
                                     <div class="col-xs-3">
                                        <div class="btn-group" id="radomQstnRadio" data-toggle="buttons">
                                          <label class="radio-inline">
                                           <input name="radioGroup3" id="radio1" value="1" type="radio" checked="checked"> Yes
                                          </label>
                                          <label class="radio-inline">
                                           <input name="radioGroup3" id="radio2" value="2"  type="radio"> No
                                          </label>
                                       </div>
                                    </div>
                                  </div>

                   <hr>
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
    </div>
</body>
</html>
