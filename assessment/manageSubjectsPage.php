<!DOCTYPE HTML>
<html>

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
  <script src="js/manage_subjects.js"></script>
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
                         <h3 class="panel-title pull-left"><strong>Manage Subjects</strong></h3>
                    </div>
               <div class="panel-body">
                <div class="col-xs-14 col-md-14">


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



                  <div id="categoryModal" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                      <!-- Modal content-->
                      <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                          <h4 class="modal-title">Enter New Category</h4>
                        </div>
                        <div class="modal-body">
                          <input type="text" id="newCatText" class="form-control"  placeholder="Enter Category">
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                          <button type="button" id="createCat" class="btn btn-success" disabled="true">Create Category</button>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div id="moduleModal" class="modal fade" role="dialog">
                    <div class="modal-dialog">

                      <!-- Modal content-->
                      <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                          <h4 class="modal-title">Enter New Module</h4>
                        </div>
                        <div class="modal-body">
                          <label for="ssc_label">Category * </label><input type="text" id="selected_category" class="form-control" disabled="true">
                          <label for="ssc_label">Module * </label><input type="text" id="newModuleText" class="form-control"  placeholder="Enter Module">
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                          <button type="button" id="createMod" class="btn btn-success" disabled="true">Create Module</button>

                        </div>
                      </div>

                    </div>
                  </div>

                  <form>
                    <div class="form-group">
                      <label for="ssc_label">Select SSC Code *</label>
                      <div class="dropdown">
                        <button id="sscdropdownButton" class="btn btn-success dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Select    SSC
                        <span class="caret"></span></button>
                         <ul id="ssc-dropdown-menu"  class="dropdown-menu dropdown-menu-center scrollable-menu">
                        </ul>
                       </div>
                     </div>


                    <hr>
                    <div class="form-group">
                      <label for="jobrole_label">Select Job Role / QP Name   or   QPCode  *</label>
                      <div class="dropdown">
                        <button id="jobroledropdownButton" class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Select Job Role
                        <span class="caret"></span></button>
                         <ul id="jobrole-dropdown-menu" class="dropdown-menu dropdown-menu-center">
                        </ul>
                       </div>
                    </div>
                    <hr>

                    <div class="form-group">
                       <label for="cat" class="col-xs-1">Category</label>
                       <div class="col-xs-3">
                           <div class="dropdown">
                               <button id="catButton" class="btn btn-success dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                               -Select Category-<span class="caret"></span></button>
                                <ul id="cat-dropdown-menu"  class="dropdown-menu dropdown-menu-center scrollable-menu">
                               </ul>
                            </div>
                      </div>
                                             <!--<input type="text" id="examDuText" class="form-control" required> -->
                      <label for="module" class="col-xs-1">Module</label>
                      <div class="col-xs-3">
                             <div class="dropdown">
                                <button id="moduleButton" class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                -Select Module-<span class="caret"></span></button>
                                 <ul id="module-dropdown-menu"  class="dropdown-menu dropdown-menu-center scrollable-menu">
                                </ul>
                              </div>
                      </div>
                        <div class="col-xs-1"></div>
                      <div class="col-xs-4">
                        <div class="btn-group pull-right">
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#categoryModal">Create Category</button>
                            <button id="createModuleModalButton" type="button" class="btn btn-primary" data-toggle="modal" data-target="#moduleModal" disabled="true">Create Module </button>
                        </div>
                      </div>

                      </div>
                     </form>
                   <br>
                <br>
                <!-- Drop down End-->
              <div class="col-xs-14">
                <hr>
                <table id="qstns" class="table table-striped table-bordered table-hover dt-responsive"  cellspacing="0" width="100%">
                           <thead>
                                 <tr>
                                       <th>S.No</th>
                                       <th>SSC</th>
                                       <th>Job Role</th>
                                       <th>Category</th>
                                       <th>Module</th>
                                       <th>Question</th>
                                       <th>Action</th>
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
          </body>

</html>
