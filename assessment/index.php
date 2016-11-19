<!DOCTYPE HTML>
<html>

<head>
  <title>Online Portal System</title>
   <script src="js/jquery.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/toggle.js"></script>
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css"  href="css/main.css">
</head>

<body>
  <!-- <script src="js/jquery-3.1.0.js"></script> -->
  <script src="js/main.js"></script>
  <div class="row">
    <!-- uncomment code for absolute positioning tweek see top comment in css -->
     <div class="absolute-wrapper"> </div>
    <!-- Menu -->
    <div class="page-header">
      <h3 class="navbar-brand brand-name">
           <a href="login.php"><img class="img-responsive2"
           src="images/logo.png"> Brisk Mind EMS. </a>
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
        <!--
       <div class="container1">
          <div id="loginbox" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-1">
            <div class="panel panel-info">
              <div class="panel-heading">
                <div class="panel-title">Sign In</div>
                <div style="float:right; font-size: 80%; position: relative; top:-10px"><a href="#">Forgot password?</a></div>
              </div>

              <div style="padding-top:30px" class="panel-body">

                <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>

                <form id="loginform" class="form-horizontal" role="form">

                  <div style="margin-bottom: 25px" class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                    <input id="login-username" type="text" class="form-control" name="username" value="" placeholder="username or email">
                  </div>

                  <div style="margin-bottom: 25px" class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                    <input id="login-password" type="password" class="form-control" name="password" placeholder="password">
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
                      <a id="btn-login" href="#" class="btn btn-success">Login  </a>
                    </div>
                  </div>

                </form>
              </div>
            </div>
          </div>
        </div>
     --></div>
    </div>
  </div>
</body>

</html>
