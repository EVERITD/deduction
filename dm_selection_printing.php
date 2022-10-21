<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Ever Employee</title>
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/bootstrap-responsive.min.css">
  <link rel="stylesheet" href="css/datepicker.css">
  <style>
    body, table, th, td, label {font-size: 11px;}
    table.table thead tr th { text-align:center !important; background-color: #F85931; }
    .showComDetailLists tbody tr:first-child td {border-top:none;}
    .accordion-group{}
    .accordion-body .accordion-inner { background-color: #A3A948; color: #222; }
    .table-striped tbody > tr:nth-child(even) > td { background-color: #f0f0f0; }
    .accordion-toggle { background-color: #333; color: #f2f2f2; font-family: Arial; font-size: 12px; }
    .mcheckbox { font-size: 11px; }
    .navbar-inner { border-radius: 0; -moz-border-radius: 0; -webkit-border-radius: 0; }
  </style>
</head>
<body>
  <!-- <script id="navbar" type="text/template">-->
    <div class="navbar">
      <div class="navbar-inner">
        <a class="brand" href="#">DEDUCTION</a>
        <ul class="nav pull-right">
          <li><a href="statistics.php"><i class="icon-user"></i> <strong>View Status</strong></a></li>
		  <li><a href="#"><i class="icon-user"></i> <strong>SP-RI Report</strong></a></li>
		  <li><a href="#"><i class="icon-user"></i> <strong>SP Total Deduction</strong></a></li>
		  <li><a href="#"><i class="icon-user"></i> <strong>Penalty-SP Summary</strong></a></li>
          <li><a href="#logout"><i class="icon-off"></i> <strong>Hi, <%= user %> &mdash; LogOut</strong></a></li>
        </ul>
      </div>
    </div>
  <!-- </script>-->
  
</body>
</html>