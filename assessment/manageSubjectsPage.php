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
   <script src="js/subjects.js"></script>
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
                      <label for="jobrole_label">Select Job Role/QP Name *</label>
                      <div class="dropdown">
                        <button id="jobroledropdownButton" class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Select Job Role
                        <span class="caret"></span></button>
                         <ul id="jobrole-dropdown-menu" class="dropdown-menu dropdown-menu-center">
                        </ul>
                       </div>
                    </div>

                    <hr>
                     <div class="form-group">
                      <label for="pwd">QP Code :</label>
                      <span class="input-group-addon" id="sizing-addon1">QP Code</span>
                     <strong> <input id="qpcodeText" type="text" class="form-control" aria-describedby="sizing-addon1" ></strong>
                      <!--
                      <div class="input-group input-group-sm">
                        <span class="input-group-addon" id="sizing-addon1">QP Code</span>
                        <input type="text" class="form-control" aria-describedby="sizing-addon1">
                      </div>
                      -->
                    </div>
                   </form>
                </div>
              </div>


                    <hr>
                <!-- Drop down End-->
            <div class="rowtable">
               <div class="col-md-14">
                 <table id="qstns" class="table table-striped table-bordered table-hover dt-responsive"  cellspacing="0" width="100%">
                           <thead>
                                 <tr>
                                         <th>S.No</th>
                                         <th>QstnId</th>
                                         <th>Subject</th>
                                         <th>Question</th>
                                         <th>Option A</th>
                                         <th>Option B</th>
                                         <th>Option C</th>
                                         <th>Option D</th>
                                         <th>Action</th>
                                         <th>Correct Opt</th>
                                         <th>Marks</th>
                                         <th>Lang</th>
                                     </tr>
                                 </thead>
                              </table>
                         </div>
                     </div>
                       </div>
         </div>
    </div>
</body>

</html>
