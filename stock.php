<?php 
require_once "vendor/autoload.php";
$dbHost='localhost';
$dbPort='27017';
$client=new MongoDB\Client;
$connection=new MongoDB\Driver\Manager("mongodb://$dbHost:$dbPort");
$query=new MongoDB\Driver\Query([]);
$stocklist=$connection->executeQuery('Statis_ByStock.Stock_list', $query);
$id=array();
$total_stockSector=array();
$total_class = array();
foreach($stocklist as $row) {
    $id[]=$row;
    array_push($total_stockSector,$row->stockSector);
    // array_push($total_class,$row->Class);
}
$total_stockSector = array_count_values($total_stockSector);
$total = count($id);

$statisStock = array();

$stocklist=$connection->executeQuery('Statis_ByStock.Stock_list', $query);
foreach($stocklist as $row) {
    $statisStock[]=$row;
}
$filter = ['class'=> 'Over'];
$query=new MongoDB\Driver\Query($filter);
$overstock = $connection->executeQuery('Statis_ByStock.Stock_list', $query);
$num_over_stock = count(iterator_to_array($overstock));

$filter = ['class'=> 'Fair'];
$query=new MongoDB\Driver\Query($filter);
$fairstock = $connection->executeQuery('Statis_ByStock.Stock_list', $query);
$num_fair_stock = count(iterator_to_array($fairstock));

$filter = ['class'=> 'Under'];
$query=new MongoDB\Driver\Query($filter);
$understock = $connection->executeQuery('Statis_ByStock.Stock_list', $query);
$num_under_stock = count(iterator_to_array($understock));

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title>Stocify | Dashboard </title>

  <link href="dist/css/table.css" rel="stylesheet">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="http://localhost/Stocify/stock.php" class="nav-link">Home</a>
      </li>
    </ul>

    <!-- SEARCH FORM -->
    <form class="form-inline ml-3">
      <div class="input-group input-group-sm">
        <input class="form-control form-control-navbar" id="tableSearch"type="search" placeholder="Search" aria-label="Search" onkeyup="myFunction()">
        <div class="input-group-append">
          <button class="btn btn-navbar" type="submit">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>
    </form>

  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="http://localhost/Stocify/stock.php" class="brand-link">
      <img src="dist/img/StocifyLogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">Stocify</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="dist/img/profile.jpeg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a  class="d-block">Welcome To Stocify !!!</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item has-treeview menu-open">
            <a href="#" class="nav-link active">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="./index.html" class="nav-link active">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Dashboard</p>
                </a>
              </li>
            </ul>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="http://localhost/Stocify/stock.php">Home</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Info boxes -->
        <div class="row">
          <div class="col-12 col-sm-6 col-md-3" id='total_div' onclick='display_class("ALL")'>
            <div class="info-box">
              <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>

              <div class="info-box-content">
                <span class="info-box-number">Number of Stock</span>
                <span class="info-box-text" id='total_stock'>
                  <?php echo $total;?>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-12 col-sm-6 col-md-3" id='over_div' onclick='display_class("Over")'>
            <div class="info-box mb-3">
              <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-thumbs-down"></i></span>

              <div class="info-box-content">
                <span class="info-box-number" style = ' color: rgb(255, 0, 0);'>Over-Value Stocks</span>
                <span class="info-box-text" id='num_over'><?php echo $num_over_stock;?></span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->

          <!-- fix for small devices only -->
          <div class="clearfix hidden-md-up"></div>

          <div class="col-12 col-sm-6 col-md-3"  id='under_div' onclick='display_class("Under")'> 
            <div class="info-box mb-3">
              <span class="info-box-icon bg-success elevation-1"><i class="fas fa-shopping-cart"></i></span>

              <div class="info-box-content">
                <span class="info-box-number"  style = ' color: rgb(0, 163, 22);'>Under-Value Stocks</span>
                <span class="info-box-text" id='num_under'><?php echo $num_under_stock;?></span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-12 col-sm-6 col-md-3" id='fair_div' onclick='display_class("Fair")'>
            <div class="info-box mb-3">
              <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>

              <div class="info-box-content">
                <span class="info-box-number" style = ' color: rgb(95, 95, 95);'>Fair-Value Stock</span>
                <span class="info-box-text" id='num_fair' ><?php echo $num_fair_stock?></span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->

        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h1 class="card-title" style='padding-top:3px;'>Stock Lists</h1>

                  <button  onclick='view_all("ALL")' id='view_all' class="btn btn-tool" type="button">
                    View All
                  </button>

                <div class="card-tools">
                  <button onclick='toClassify()' id='update_btn' class="btn btn-tool" type="button">
                    Update
                  </button>
                  <button style='display:none;'id='loading_btn' class="btn btn-tool" type="button" disabled>
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    Updating...
                  </button>
                  
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="row">
                  <div class="col-lg-12 mx-auto bg-white rounded shadow">
      
                      <!-- Fixed header table-->
                      <div class="table-responsive">
                          <table class="table table-fixed" id="myTable">
                              <thead>
                                  <tr>
                                      <th scope="col" class="col-2">Name</th>
                                      <th scope="col" class="col-2">Sector</th>
                                      <th scope="col" class="col-1">P/EPS %</th>
                                      <th scope="col" class="col-1">P/RPS</th>
                                      <th scope="col" class="col-1">P/NAPS</th>
                                      <th scope="col" class="col-2">Price</th>
                                      <th scope="col" class="col-2">Class</th>
                                  </tr>
                              </thead>
                              <tbody>
                                  <?php foreach ($id as $document) {
                                          ?>
                                  <tr onclick ='toAnotherPage("<?php echo $document->stockName?>")'>
                                      <td class="col-2">
                                          <?php echo $document->stockName?>
                                      </td>
                                      <td class="col-2">
                                          <?php 
                                          if ($document->stockSector == "Transportation&Logistics"){
                                            echo ("Logistics");
                                          }else{
                                            echo $document->stockSector;
                                          }       
                                          ?>
                                      </td>
                                      <td class="col-1">
                                          <span class ="<?php echo $document->p_eps_class?>"><b><?php echo $document->P_EPS?></span>
                                      </td>
                                      <td class="col-1">
                                          <span class ="<?php echo $document->p_rps_class?>"><b><?php echo $document->P_RPS?></span>
                                      </td>
                                      <td class="col-1">
                                          <span class ="<?php echo $document->p_naps_class?>"><b><?php echo $document->P_NAPS?></span>
                                      </td>
                                      <td class="col-2">
                                          <?php echo $document->Price?>
                                      </td>
                                      <td class="col-2">
                                          <span class ="<?php echo $document->class?>"><b><?php echo $document->overall_class?></span>
                                      </td>
                                  </tr>
                                  <?php
                                   }
                                      ?>
                              </tbody>
                          </table>
                      </div>
                  </div>
              </div>
                <!-- /.row -->
              </div>
              <!-- ./card-body -->
              <div class="card-footer">
                <div id="chartContainer1" style="height: 300px; width: 100%;"></div>
              </div>
              <!-- /.card-footer -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->

        <!-- Main row -->
      
        <!-- /.row -->
      </div><!--/. container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->

</div>
<!-- ./wrapper -->

<script>
  window.onload = function () {
            var stock_list = <?php echo json_encode($id);?>;
            var totalStockSector = <?php echo json_encode($total_stockSector);?>;
            var total = <?php echo $total;?>;
            var curr_option = 'ALL'

            var dict = []; // create an empty array

            for (var key in totalStockSector) {
                percentage = totalStockSector[key] / total * 100;
                dict.push({
                    y: percentage.toFixed(2),
                    label: key,
                    count: totalStockSector[key]
                });
            }

            var chart = new CanvasJS.Chart("chartContainer1", {
                theme: "light2", // "light1", "light2", "dark1", "dark2"
                exportEnabled: true,
                animationEnabled: true,
                title: {
                    text: "Stock Market"
                },
                data: [{
                    type: "pie",
                    startAngle: 25,
                    toolTipContent: "<b>{label}</b>: {count}",
                    showInLegend: "true",
                    legendText: "{label}",
                    indexLabelFontSize: 16,
                    indexLabel: "{label} - {y}%",
                    click: onClick,
                    dataPoints: dict
                }]
            });
            chart.render();
            function onClick(e) {
              curr_option = e.dataPoint.label
              document.getElementById('total_div').setAttribute('onclick',"display_class_sector('ALL','" + e.dataPoint.label + "')")
              document.getElementById('over_div').setAttribute('onclick',"display_class_sector('Over','" + e.dataPoint.label + "')")
              document.getElementById('fair_div').setAttribute('onclick',"display_class_sector('Fair','" + e.dataPoint.label + "')")
              document.getElementById('under_div').setAttribute('onclick',"display_class_sector('Under','" + e.dataPoint.label + "')")

              $.ajax({
                  data: {text:e.dataPoint.label},
                  dataType: 'json',
                  url: "display_sector.php",
                context: document.body
                }).done(function(dataaaaaa) {

                  var over_count = 0
                  var under_count = 0
                  var fair_count = 0

                  console.log(dataaaaaa.length)
                  createNewTable(dataaaaaa)
                  document.getElementById('total_stock').innerHTML = dataaaaaa.length
                  for (var i = 0; i < dataaaaaa.length; i++) {
                      if(dataaaaaa[i]['class'] == 'Over'){
                        over_count++
                      }else if (dataaaaaa[i]['class'] == 'Under'){
                        under_count++
                      }else if (dataaaaaa[i]['class'] == 'Fair'){
                        fair_count++
                      }
                  }
                  document.getElementById('num_under').innerHTML = under_count
                  document.getElementById('num_fair').innerHTML = fair_count
                  document.getElementById('num_over').innerHTML = over_count
                });
                // var sector_list = ['Consumer',
                //     'Plantations',
                //     'Industrial Products',
                //     'Finance',
                //     'Property',
                //     'Construction',
                //     'Technology',
                //     'Transportation&Logistics',
                //     'Energy',
                //     'Telco&Media',
                //     'Closed&Fund',
                //     'Utilities',
                //     'REITS',
                //     'Health Care'
                // ];
                // var selected_stock_list = new Array();
                // for (var i = 0; i < sector_list.length; i++) {
                //     chosen_sector = sector_list[i]
                //     if (e.dataPoint.label == chosen_sector) {
                //         for (var j = 0; j < stock_list.length; j++) {
                //             if (stock_list[j]['stockSector'] == chosen_sector) {
                //                 selected_stock_list.push(stock_list[j]);
                //             }
                //         }
                //         break;
                //     }
                // }
                // createNewTable(selected_stock_list);
            }
        }
        function compareValues(key, order = 'asc') {
            return function innerSort(a, b) {
                if (!a.hasOwnProperty(key) || !b.hasOwnProperty(key)) {
                    // property doesn't exist on either object
                    return 0;
                }

                const varA = (typeof a[key] === 'string') ?
                    a[key].toUpperCase() : a[key];
                const varB = (typeof b[key] === 'string') ?
                    b[key].toUpperCase() : b[key];

                let comparison = 0;
                if (varA > varB) {
                    comparison = 1;
                } else if (varA < varB) {
                    comparison = -1;
                }
                return (
                    (order === 'desc') ? (comparison * -1) : comparison
                );
            };
        }
        
        function view_all(data){

          document.getElementById('total_div').setAttribute('onclick',"display_class('ALL')")
          document.getElementById('over_div').setAttribute('onclick',"display_class('Over')")
          document.getElementById('fair_div').setAttribute('onclick',"display_class('Fair')")
          document.getElementById('under_div').setAttribute('onclick',"display_class('Under')")

          $.ajax({
              data: {text:data},
              dataType: 'json',
              url: "display_all.php",
             context: document.body
            }).done(function(dataaaaaa) {
              var over_count = 0
                  var under_count = 0
                  var fair_count = 0

                  console.log(dataaaaaa.length)
                  createNewTable(dataaaaaa)
                  document.getElementById('total_stock').innerHTML = dataaaaaa.length
                  for (var i = 0; i < dataaaaaa.length; i++) {
                      if(dataaaaaa[i]['class'] == 'Over'){
                        over_count++
                      }else if (dataaaaaa[i]['class'] == 'Under'){
                        under_count++
                      }else if (dataaaaaa[i]['class'] == 'Fair'){
                        fair_count++
                      }
                  }
                  document.getElementById('num_under').innerHTML = under_count
                  document.getElementById('num_fair').innerHTML = fair_count
                  document.getElementById('num_over').innerHTML = over_count

            });
        }


        function toClassify(){
          document.getElementById('update_btn').style.display = 'none';
          document.getElementById('loading_btn').style.display = 'block';
          $.ajax({
              dataType: 'json',
              url: "classify.php",
             context: document.body
            }).done(function(dataaaaaa) {
              document.getElementById('update_btn').style.paddingLeft = '105px'
              document.getElementById('update_btn').style.display = 'block';
              document.getElementById('loading_btn').innerHTML = 'Last Update : ' +new Date().toLocaleTimeString();
              
              createNewTable(dataaaaaa)
             alert('Done Updating');;
            });
        }
 
        function display_class_sector(data,curr_option){
          console.log(data)
          console.log(curr_option)
          $.ajax({
              data: {text:data, option:curr_option},
              dataType: 'json',
              url: "display_class.php",
             context: document.body
            }).done(function(dataaaaaa) {
              createNewTable(dataaaaaa)
            });
        }

        function display_class(data){
          $.ajax({
              data: {text:data},
              dataType: 'json',
              url: "display_all.php",
             context: document.body
            }).done(function(dataaaaaa) {
              createNewTable(dataaaaaa)
            });
        }


        function createNewTable(dataa) {
            dataa.sort(compareValues('stockName', 'asc'));
            var tbl = document.getElementById("myTable"); // Get the table
            tbl.removeChild(tbl.getElementsByTagName("tbody")[0]);

            var table = document.getElementById("myTable");
            var tbody = document.createElement("tbody");
            table.appendChild(tbody);

            var headerr = ['stockName', 'stockSector', 'P_EPS', 'P_RPS', 'P_NAPS', 'Price', 'overall_class']
            for (var j = 0; j < dataa.length; j++) {
                item = dataa[j];
                var row = tbody.insertRow(-1);
                
                row.setAttribute('onclick','toAnotherPage("'+item['stockName'] + '")')
                for (var i = 0; i < headerr.length; i++) {

                    var cell = row.insertCell(-1);
                    if (i == 0 || i == 1 || i == 5 || i == 6) {
                        cell.setAttribute('class', 'col-2');
                    } else {
                        cell.setAttribute('class', 'col-1');
                    }
                    
                    if (headerr[i] == 'P_NAPS'){
                        cell.innerHTML = '<span class ="'+ item['p_naps_class'] + '"><b>'+ item[headerr[i]] + '</span>';
                    }
                    else if (headerr[i] == 'P_RPS'){ 
                        cell.innerHTML = '<span class ="'+ item['p_rps_class'] + '"><b>'+ item[headerr[i]] + '</span>';
                    }
                    else if (headerr[i] == 'P_EPS'){ 
                        cell.innerHTML = '<span class ="'+ item['p_eps_class'] + '"><b>'+ item[headerr[i]] + '</span>';
                    }
                    else if (headerr[i] == 'overall_class'){ 
                        cell.innerHTML = '<span class ="'+ item['class'] + '"><b>'+ item[headerr[i]] + '</span>';
                    }
                    else{
                        cell.innerHTML = item[headerr[i]];
                    }

                    
                }
            }   
        }
        function myFunction() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("tableSearch");
            filter = input.value.toUpperCase();
            table = document.getElementById("myTable");
            tr = table.getElementsByTagName("tr");
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[0];
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
        function toAnotherPage(stockName){
          var passData = stockName;
          localStorage.setItem("passData",passData);
          // window.open ('http://localhost/Stocify/stockdetail.php');
          window.location.href = 'http://localhost/Stocify/stockdetail.php'
          createCookie("gfg", passData, "1"); 
        }
        function createCookie(name, value, days) { 
          var expires; 
            
          if (days) { 
              var date = new Date(); 
              date.setTime(date.getTime() + (days *24*60*60*1000)); 
              expires = "; expires=" + date.toGMTString(); 
          } 
          else { 
              expires = ""; 
          } 
            
          document.cookie = escape(name) + "=" +  
              escape(value) + expires + "; path=/"; 
        } 


  
</script>


<!-- REQUIRED SCRIPTS -->
<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- overlayScrollbars -->
<script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.js"></script>

<!-- OPTIONAL SCRIPTS -->
<script src="dist/js/demo.js"></script>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<!-- PAGE PLUGINS -->
<!-- jQuery Mapael -->
<script src="plugins/jquery-mousewheel/jquery.mousewheel.js"></script>
<script src="plugins/raphael/raphael.min.js"></script>
<script src="plugins/jquery-mapael/jquery.mapael.min.js"></script>
<script src="plugins/jquery-mapael/maps/usa_states.min.js"></script>
<!-- ChartJS -->
<script src="plugins/chart.js/Chart.min.js"></script>

<!-- PAGE SCRIPTS -->
<script src="dist/js/pages/dashboard2.js"></script>
</body>
</html>
