<?php
session_start();
?>
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
<link rel="stylesheet" type="text/css"  href="css/font-awesome.min.css">
<script src="js/toggle.js"></script>
<script src="js/import_batch.js"></script>
<script src="js/progress.js"></script>
  <script src="js/main.js"></script>
</head>

<body>
  <script src="js/main.js"></script>
    <script src="js/login.js"></script>
    <div class="loader"></div>
      <div id="wrapper">
       <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
		             <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
         <h3 class="navbar-brand brand-name">
		    <div class="navbar-header">
				      <h3 class="navbar-brand brand-name">
           <a href="login.php"><img class="img-responsive2"
           src="images/logo.png">      Brisk Mind Examination Management System !!!</a>
       </h3>
			</div>
		</h3>
	  <ul class="nav navbar-top-links navbar-right">
                <!-- /.dropdown -->
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
                    </a>
					<!--
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="#"><i class="fa fa-user fa-fw"></i> User Profile</a>
                        </li>
                        <li><a href="#"><i class="fa fa-gear fa-fw"></i> Settings</a>
                        </li>
                        <li class="divider"></li>
                        <li><a href="login.html"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                        </li>
          </ul> -->
                    <!-- /.dropdown-user -->
        </li>
                <!-- /.dropdown -->
      </ul>
    </div>
	</div>
	</nav>
    <div class="side-menu">
      <nav class="navbar navbar-default" role="navigation" >
        <!-- Main Menu -->
        <div class="side-menu-container">
          <ul class="nav navbar-nav" >
              <li><a  href="login.php"><span class="glyphicon glyphicon-log-in"></span>Login</a></li>
			        <li><a  href="fillSSP.php"><span class="glyphicon glyphicon-user"></span>Fill SSP Data</a></li>
              <li><a  href="editSSP.php"><span class="glyphicon glyphicon-edit"></span>Edit SSP Data</a></li>
          </ul>
        </div>
      </nav>
   </div>
 </div>
    <!-- Main Content -->
    <div class="container-fluid">
      <div class="side-body">
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
         <div id="successMessage" class="modal fade" role="dialog">
                     <div class="modal-dialog">
                       <div class="modal-content">
                         <div class="modal-header">
                           <button type="button" class="close" data-dismiss="modal">&times;</button>
                           <h4 class="modal-title">Status</h4>
                         </div>
                         <div class="modal-body">
                             <strong> <font color='Green'><span id="successMessageText">SSP Submitted Successfully !</span></font></strong>
                       </div>
                   <div class="modal-footer">
                       <button type="button" data-dismiss="modal" class="btn">Cancel</button>
                   </div>
                   </div>
                   </div>
          </div>

          <div id="loginbox" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-1">
            <div class="panel panel-success">
              <div class="panel-heading">
                <div class="panel-title text-center">Sign  In to Arrow SSP System</div>
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
                    <input id="login-username" type="text" class="form-control" name="username" value="" placeholder="username or email" required>
                  </div>

                  <div style="margin-bottom: 25px" class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                    <input id="login-password" type="password" class="form-control" required="required" name="password" placeholder="password">
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
    </div>
  </div>
</body>

</html>';
