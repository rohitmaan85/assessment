
<!DOCTYPE HTML>
<html>

<head><title>BriskMindTest EMS</title>
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
<script src="js/import_questions.js"></script>
<script src="js/progress.js"></script>
<script src="js/main.js"></script>
</head>

<body>
  <div class="loader"></div>  <!-- <script src="js/jquery-3.1.0.js"></script> -->

  <div class="row">
    <!-- uncomment code for absolute positioning tweek see top comment in css -->
    <div class="absolute-wrapper"> </div>
    <!-- Menu -->
    <div class="page-header">
      <h3 class="navbar-brand brand-name">
           <a href="login.php"><img class="img-responsive2"
           src="images/logo.png">      Brisk Mind Examination Management System !!!</a>
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
            <div class="col-xs-14 col-md-14" id="selectSubject">
                  <div class="panel panel-info">
                            <div class="panel-heading">
                                       Select SSC and Job Role
                                </div>
                        <div class="panel-body">
                              <div class="form-group">
                                   <label for="ssc"  class="col-xs-1">SSC *</label>
                                     <div class="col-xs-4">
                                         <div class="dropdown">
                                           <button id="sscdropdownButton" class="btn btn-success dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                             --Select SSC--<span class="caret"></span></button>
                                              <ul id="ssctest-dropdown-menu"  class="dropdown-menu dropdown-menu-center scrollable-menu">
                                         </ul>
                                       </div>
                               </div>
                               <label for="jobrole"  class="col-xs-2">JobRole *</label>
                                       <div class="col-xs-5">
                                         <div class="dropdown">
                                             <button id="jobroledropdownButton" class="btn btn-success dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                 --Select JobRole--<span class="caret"></span></button>
                                                      <ul id="jobroletest-dropdown-menu"  class="dropdown-menu dropdown-menu-center scrollable-menu">
                                                 </ul>
                                         </div>
                                         </div>
                                </div>
                                <br>
                                <br>
                              <div class="form-group">
                                   <label for="ssc"  class="col-xs-1">Category</label>
                                     <div class="col-xs-4">
                                         <div class="dropdown">
                                           <button id="categorydropdownButton" class="btn btn-success dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                             -- Select Category --<span class="caret"></span></button>
                                              <ul id="cat-dropdown-menu"  class="dropdown-menu dropdown-menu-center scrollable-menu">
                                         </ul>
                                       </div>
                               </div>
                               <label for="jobrole"  class="col-xs-2">Module</label>
                                       <div class="col-xs-5">
                                         <div class="dropdown">
                                             <button id="moduledropdownButton" class="btn btn-success dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                 -- Select Module --<span class="caret"></span></button>
                                                      <ul id="module-dropdown-menu"  class="dropdown-menu dropdown-menu-center scrollable-menu">
                                                 </ul>
                                         </div>
                                       </div>
                          </div>
                    </div>
            </div>
            <!--<div class="col-lg-14 col-md-11 col-sm-2 col-xs-12"> -->




                <div class="panel panel-default">
                    <div class="panel-heading">
                         <h2 class="panel-title pull-left"><strong>Import Questions from Excel File</strong></h2>
                    </div>
                    <div class="panel-body">


                      <div>

                     <form id="uploadXlsForm" class="well hide" action="#" method="post" enctype="multipart/form-data">
                      <div class="row" style="position:relative">
                         <div id="error_msg"  class="alert alert-danger fade hide" style="position:relative">
                           <button href="#" type="button" class="close">&times;</button>
                               <strong></strong>
                        </div>
                        <!--  <div class="col-lg-12 col-sm-6 col-12"> -->
                          <h4>Upload Questions From Excel File.</h4>
                          <div class="input-group">
                              <label class="input-group-btn">
                                  <span class="btn btn-primary">
                                      Browse&hellip; <input id="fileUploadInput" type="file" style="display: none;" multiple>
                                  </span>
                              </label>
                              <input type="text" class="form-control" readonly>
                          </div>
                          <span class="help-block">
                              Select file to be uploaded , Only Excel files are allowed.
                          </span>

                        <!-- File Upload Progress Bar -->
                        <div class="list-group" id="files"></div>
                        <script id="fileUploadProgressTemplate" type="text/x-jquery-tmpl">
                        <div class="list-group-item">
                            <div class="progress progress-striped active">
                                <div class="progress-bar progress-bar-info" style="width: 0%;"></div>
                            </div>
                        </div>
                        </script>
                        <!-- File Upload Progress Bar End-->
                       </div>
                       <button id="uploadFileButton"  type="button" class="btn btn-success disabled" disabled="disabled"><span class="glyphicon glyphicon-upload"></span>      Import Questions ...</button>
                       <button id="cancelButton"  type="button" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span>  Cancel</button>
                       </form>
                     </div>
                    <hr>

                    <div class="rowtable">
                        <div class="col-md-14">
                            <table id="qstnsTable" class="table table-striped table-bordered table-hover dt-responsive"  cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>S.No</th>
                                            <th>SSC</th>
                                            <th>Job Role</th>
                                            <th>Category</th>
                                            <th>Module</th>
                                            <th>Question</th>
                                            <th>Option A</th>
                                            <th>Option B</th>
                                            <th>Option C</th>
                                            <th>Option D</th>
                                            <th>Answer</th>
                                            <th>type</th>
                                        </tr>
                                    </thead>
                                 </table>
                            </div>
                        </div>
                    </div>
                </div>
        <!-- </div> -->
    </div>
    </div>
</body>

</html>
