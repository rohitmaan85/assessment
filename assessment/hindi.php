<!DOCTYPE HTML>
<html>

<head>
  <title>Online Portal System</title>


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
    <script src="js/upload.js"></script>

    <script src="http://www.hinkhoj.com/common/js/keyboard.js"></script>
    <link rel="stylesheet" type="text/css" href="http://www.hinkhoj.com/common/css/keyboard.css" />

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
                 <h4 class="form-signin-heading">Create New Question</h4>
                 </div>
               <div class="panel-body">
                <div class="col-xs-12">
                 <div id="error_msg"  class="alert alert-danger fade in" style="position:relative">
                   <button href="#" type="button" class="close">&times;</button>
                       <strong>test</strong>
                 </div>
                 </div>
                 <!--  Start Create Exam <Form--></Form-->
                  <div class="row">
                  <div id='createExamDiv' class="col-xs-12">

                  <form id="createExamForm" class="form-horizontal" >
                    <div class="form-group">
                       <label for="langauge" class="col-xs-4">Select Language : </label>
                         <div class="col-xs-8">
                            <div class="btn-group" id="selectLangRadio" data-toggle="buttons">
                              <label class="radio-inline">
                               <input name="radioGroup" id="radio1" value="1" type="radio"> Hindi
                              </label>
                              <label class="radio-inline">
                               <input name="radioGroup" id="radio2" value="2" checked="checked" type="radio"> English
                              </label>
                           </div>
                        </div>
                      </div>
                      <hr>
                      <div class="form-group">
                         <label for="enterQuestion" class="col-xs-4">Question : </label>
                         <div class="col-xs-8">
                           <div id="hindiTextArea" class="textarea.form-control hide">
                             <script language="javascript">
                                 CreateHindiTextArea("hindiTextQstn");
                             </script>
                           </div>
                            <textarea rows="5" cols="4" class="form-control" id="questionInputTextArea" placeholder="Enter Question" required></textarea>
                          </div>
                       </div>
                    <div class="form-group">
                      <label  class="col-xs-4" for="numberOfOption">Number of Options : </label>
                       <div class="col-xs-8">
                            <select class="form-control" id="noOfOptions">
                              <option>1</option>
                              <option>2</option>
                              <option>3</option>
                              <option>4</option>
                            </select>
                      </div>
                    </div>
                    <div id="optionADiv" class="form-group">
                       <label class="col-xs-4" for="optionAText">Option A</label>
                       <div class="col-xs-8">
                         <div id="hindioptionATextAreaInput" class="textarea.form-control hide">
                           <script language="javascript">
                               CreateHindiTextArea("hindiTextOptionA");
                           </script>
                         </div>
                         <textarea id="optionATextAreaInput" rows="2" cols="6" class="form-control" placeholder="Enter Option A" required></textarea>
                       </div>
                    </div>
                   <div id="optionBDiv" class="form-group hide">
                       <label class="col-xs-4" for="optionBText">Option B</label>
                       <div class="col-xs-8">
                         <div id="hindioptionBTextAreaInput" class="textarea.form-control hide">
                           <script language="javascript">
                               CreateHindiTextArea("hindiTextOptionB");
                           </script>
                         </div>
                          <textarea id="optionBTextAreaInput" rows="2" cols="6" class="form-control" placeholder="Enter Option B"></textarea>
                        </div>
                    </div>
                    <div id="optionCDiv" class="form-group hide">
                       <label class="col-xs-4" for="optionCText">Option C</label>
                       <div class="col-xs-8">
                         <div id="hindioptionCTextAreaInput" class="textarea.form-control hide">
                           <script language="javascript">
                               CreateHindiTextArea("hindiTextOptionC");
                           </script>
                         </div>
                        <textarea id="optionCTextAreaInput" rows="2" cols="6" class="form-control" placeholder="Enter Option C"></textarea>
                       </div>
                    </div>
                    <div id="optionDDiv" class="form-group hide">
                       <label class="col-xs-4" for="optionDText">Option D</label>
                       <div class="col-xs-8">
                         <div id="hindioptionDTextAreaInput" class="textarea.form-control hide">
                           <script language="javascript">
                               CreateHindiTextArea("hindiTextOptionD");
                           </script>
                         </div>
                          <textarea id="optionDTextAreaInput" rows="2" cols="6" class="form-control" placeholder="Enter Option D"></textarea>
                       </div>
                    </div>
                    <div class="form-group">
                      <label class="col-xs-4" for="correctOption">Correct Option : </label>
                      <div class="col-xs-8">
                        <select class="form-control" id="corrOption">
                          <option>Option A</option>
                          <option>Option B</option>
                          <option>Option C</option>
                          <option>Option D</option>
                        </select>
                      </div>
                    </div>
                    <div class="form-group">
                       <label class="col-xs-4" for="marks">Marks</label>
                       <div class="col-xs-8">
                           <input type="text" id="marksText" class="form-control" placeholder="Enter Marks" required>
                           </div>
                    </div>
                       <button id="createQstnButton"  type="button" class="btn btn-info"><span class="glyphicon glyphicon-floppy-saved"></span>     Create Question</button>
                       <button id="resetQstnButton"  type="button" class="btn btn-warning"><span class="glyphicon glyphicon-remove"></span>  Reset</button>
                       <button id="cancelQstnButton"  type="button" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span>  Cancel</button>
                    </form>
                  </div>
                  </div>
                 <!--  End Create Exam Form-->

               </div>
              </div>
            </div>
        <!-- </div> -->
    </div>
    </div>
</body>

</html>
