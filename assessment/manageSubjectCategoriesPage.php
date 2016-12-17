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
  <script src="js/manage_sub_categories.js"></script>
  <script src="js/main.js"></script>

  </head>

<body>
  <!-- <script src="js/jquery-3.1.0.js"></script> -->

  <div class="loader"></div>
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
            <li><a  href="manageSubjectCategoriesPage.php"><span class="glyphicon glyphicon-paperclip"></span>Manage Categories</a></li>
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
  </div>

       <!-- Main Content -->
    <div class="container-fluid">
      <div class="side-body">
            <!--<div class="col-lg-14 col-md-11 col-sm-2 col-xs-12"> -->
            <div class="col-xs-14 col-md-14">
                <div class="panel panel-default">
                  <div class="panel-heading">
                     <h3 class="panel-title pull-left"><strong>Manage Subjects Category and Modules </strong></h3>
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
                                      <div class="dropdown">
                                          <button id="catButton" class="btn btn-success dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                          -Select Category-<span class="caret"></span></button>
                                           <ul id="cat-dropdown-menu"  class="dropdown-menu dropdown-menu-center scrollable-menu">
                                          </ul>
                                       </div>

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
                    <div id="categoryRenameModal" class="modal fade" role="dialog">
                      <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Rename Category</h4>
                          </div>
                          <div class="modal-body">
                            <input type="text" id="renameCatText" class="form-control"  placeholder="Enter New Name">
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                            <button type="button" id="renameCat" class="btn btn-success" disabled="true">Rename Category</button>
                          </div>
                        </div>
                      </div>
                    </div>


                    <div id="moduleRenameModal" class="modal fade" role="dialog">
                      <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Rename Module</h4>
                          </div>
                          <div class="modal-body">
                            <input type="text" id="renameModuleText" class="form-control"  placeholder="Enter New Module Name">
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                            <button type="button" id="renameModule" class="btn btn-success" disabled="true">Rename Module</button>
                          </div>
                        </div>
                      </div>
                    </div

                    <div class="panel-body">
                      <div id="confirmCategory" class="modal fade" role="dialog">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Confirm Deletion</h4>
                              </div>
                              <div class="modal-body">
                                  <strong> <font color='Red'>Are you sure , you want to delete this Category ?</font></strong>
                            </div>
                        <div class="modal-footer">
                          <button type="button" data-dismiss="modal" class="btn btn-danger" id="deleteCategory">Delete</button>
                          <button type="button" data-dismiss="modal" class="btn">Cancel</button>
                        </div>
                        </div>
                      </div>
                    </div>

                    <div id="confirmModule" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                              <h4 class="modal-title">Confirm Deletion</h4>
                            </div>
                            <div class="modal-body">
                                <strong> <font color='Red'>Are you sure , you want to delete this Module ?</font></strong>
                          </div>
                      <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn btn-danger" id="deleteModule">Delete</button>
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
                  <div id="errorModal" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Confirm Deletion</h4>
                              </div>
                              <div class="modal-body">

                                <strong> <font color="Red"><span id="errorMessageText">Error while geting Category , Please try again !</span></font></strong>

                            </div>
                        <div class="modal-footer">
                          <button type="button" data-dismiss="modal" class="btn btn-primary" id="delete">Delete</button>
                          <button type="button" data-dismiss="modal" class="btn">Cancel</button>
                        </div>
                        </div>
                        </div>
                    </div>
                          <div class="form-group">
                               <label for="ssc"  class="col-xs-1">SSC*</label>
                                 <div class="col-xs-4">
                                     <div class="dropdown">
                                       <button id="sscdropdownButton" class="btn btn-success dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                         -- Select SSC --<span class="caret"></span></button>
                                          <ul id="ssctest-dropdown-menu"  class="dropdown-menu dropdown-menu-center scrollable-menu">
                                     </ul>
                                   </div>
                           </div>
                           <label for="jobrole"  class="col-xs-1">JobRole*</label>
                                   <div class="col-xs-6">
                                     <div class="dropdown">
                                         <button id="jobroledropdownButton" class="btn btn-success dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                             -- Select JobRole --<span class="caret"></span></button>
                                                  <ul id="jobroletest-dropdown-menu"  class="dropdown-menu dropdown-menu-center scrollable-menu">
                                         </ul>
                                 </div>
                             </div>
                        </div>


                    <div class="form-group">
                      <br>
                      <div class="col-xs-14 col-md-14">
                            <hr style="width: 100%; color: black; height: 1px; background-color:green;" />
                      </div>
                    </div>

                    <div class="col-xs-14 col-md-14" id="showModules">
                          <div class="panel panel-info">
                              <div class="panel-heading">
                                       Create category and Modules
                                </div>
                            <div class="panel-body">
                              <div class="form-group">
                                   <div class="col-xs-14 col-md-14">
                                        <div class="btn-group pull-center">
                                          <button type="button" class="btn btn-success" data-toggle="modal" data-target="#categoryModal">Create Category</button>
                                          <button id="createModuleModalButton" type="button" class="btn btn-primary" data-toggle="modal" data-target="#moduleModal">Create Module </button>
                                        </div>
                                  </div>
                              </div>
                          </div>
                        </div>
                   </div>

                    <div class="col-xs-14 col-md-14" id="showCategory">
                          <div class="panel panel-info">
                                <div class="panel-heading">
                                       Categories
                                </div>
                              <div class="panel-body">
                              <ul class="list-group" id="category_list_group" style="margin-top:5px">
                            </div>
                            </div>
                      </div>

                      <div class="col-xs-14 col-md-14" id="showModules">
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                         Modules
                                  </div>
                              <div class="panel-body">
                            <ul class="list-group" id="module_list_group" style="margin-top:5px">
                            </div>
                          </div>
                     </div>


                    </div>
                  </div>

                </div>
                </div>
                </div>
                </div>
  </body>
</html>
