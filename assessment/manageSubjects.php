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
   <script src="js/subjects.js"></script>

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
            <!--<div class="col-lg-14 col-md-11 col-sm-2 col-xs-12"> -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                         <h3 class="panel-title">Manage Subjects.</h3>
                    </div>
              <div class="panel-body">
                <div class="col-xs-8 col-md-8">
                  <form>
                    <div class="form-group">
                      <label for="ssc_label">Select SSC Code :</label>
                      <div class="dropdown">
                        <button id="sscdropdownButton" class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Select    SSC
                        <span class="caret"></span></button>
                         <ul id="ssc-dropdown-menu"  class="dropdown-menu dropdown-menu-center scrollable-menu">
                        </ul>
                       </div>
                     </div>


                     <hr>
                    <div class="form-group">
                      <label for="jobrole_label">Select Job Role/QP Name :</label>
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
                </div>

                <div class="col-xs-14 col-md-14">
                    <hr>
                </div>
                <!-- Drop down End-->
                 <div class="rowtable">
                     <div class="col-md-12">
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
</body>

</html>
