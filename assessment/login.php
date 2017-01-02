<!DOCTYPE HTML>
<html>

<head>
  <title>BriskMindTest EMS</title>
   <script src="js/jquery.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/toggle.js"></script>
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css"  href="css/main.css">
</head>

<body>



  <script src="js/main.js"></script>
    <script src="js/login.js"></script>
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

    <!--
    <div class="side-menu">

      <nav class="navbar navbar-default" role="navigation" >
          <div class="navbar-header">
            <button type="button" class="navbar-toggle"  data-toggle="collapse" data-target=".side-menu-container">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>



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
      </nav>

    </div>

  -->
    <!-- /.navbar-collapse -->

    <!-- Main Content -->

         <div class="container1">

	            <div id="errorMessage" class="modal fade" role="dialog">
             <div class="modal-dialog">
               <div class="modal-content">
                 <div class="modal-header">
                   <button type="button" class="close" data-dismiss="modal">&times;</button>
                   <h4 class="modal-title">Error</h4>
                 </div>
                 <div class="modal-body">
                     <strong> <font color='Red'><span id="errorMessageText">Error while connecting Axway Arrow !!</span></font></strong>
               </div>
             <div class="modal-footer">
               <button type="button" data-dismiss="modal" class="btn">Cancel</button>
             </div>
           </div>
          </div>
         </div>

          <div id="loginbox" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-1">
            <div class="panel panel-info">
              <div class="panel-heading">
                <div class="panel-title">Sign In To BriskMind EMS</div>
                <div style="float:right; font-size: 80%; position: relative; top:-10px"><a href="#">Forgot password?</a></div>
              </div>

              <div style="padding-top:30px" class="panel-body">


				<div id="login-alert" class="alert alert-danger"  hidden="hidden">
                  <button class="close" data-hide="alert">&times;</button>
                    <strong></strong>
                </div>
                <form id="loginform" class="form-horizontal" role="form">

                  <div style="margin-bottom: 25px" class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                    <input id="login-username" type="text" class="form-control" name="username" value="" placeholder="username or email" required="required">
                  </div>

                  <div style="margin-bottom: 25px" class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                    <input id="login-password" type="password" class="form-control" name="password" placeholder="password" required="required">
                  </div>



                  <div class="input-group">
                    <div class="checkbox">
                      <label>
                        <input id="login-remember" type="checkbox" name="remember" value="1"> Remember me
                      </label>
                    </div>
                  </div>

                  <div style="margin-top:10px" class="form-group">
                                         <div class="col-sm-12 controls">
                      <button id="loginButton" type="submit" class="btn btn-success">Login</button>
                    </div>
                  </div>

                </form>
              </div>
            </div>
          </div>
    </div>
  </div>
</body>

</html>';
