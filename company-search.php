<?php
 if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 

if(!isset($_SESSION['user_id']) || !isset($_SESSION['logged_in_time'])){
    //User not logged in. Redirect them back to the login.php page.
    header('Location: sign-in.php');
    exit;
}



function __autoload($class)
{
  require_once "classes/$class.php";
}


function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

if (isset($_POST['delete-company']) && $_SERVER["REQUEST_METHOD"] == "POST") {

$companyID = test_input($_POST["companyID"]);
$tableName = "tb_companies";
$whereClause = "company_id";
$ch = new Chempo();

$fields = [ 
         
            'del_status'=>'X'
           
        ];

$check = $ch->delete($fields,$companyID,$tableName,$whereClause);

if($check == 1)
{
    header("Location: company-search.php");
}
else
{
    echo '<div class="alert alert-danger" role="alert">
                                <div class="container">
                                    <div class="alert-icon">
                                        <i class="zmdi zmdi-block"></i>
                                    </div>
                                    <strong>Oh snap!</strong> There was a problem occured during the deletion of this record. Please try again.
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">
                                            <i class="zmdi zmdi-close"></i>
                                        </span>
                                    </button>
                                </div>
                            </div>';
}


}




?>



<!doctype html>
<html class="no-js " lang="en">


<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="">

<title>:: ChemPO :: Company Search</title>
<link rel="icon" href="assets/images/rimpido-header.png" type="image/x-icon">
<!-- Favicon-->
<link rel="stylesheet" href="assets/plugins/bootstrap/css/bootstrap.min.css">
<!-- Custom Css -->
<link rel="stylesheet" href="assets/css/style.min.css">

<link rel="stylesheet" href="assets/plugins/jquery-datatable/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="assets/plugins/sweetalert/sweetalert.css"/>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>

<body class="theme-blush">

<!-- Page Loader -->
<div class="page-loader-wrapper">
    <div class="loader">
        <div class="m-t-30"><img class="zmdi-hc-spin" src="assets/images/rimpido-header.png" width="48" height="48" alt="ChemPO"></div>
        <p>Please wait...</p>
    </div>
</div>

<!-- Overlay For Sidebars -->
<div class="overlay"></div>

<!-- Main Search -->
<div id="search">
    <button id="close" type="button" class="close btn btn-primary btn-icon btn-icon-mini btn-round">x</button>
    <form>
        <input type="search" value="" placeholder="Search..." />
        <button type="submit" class="btn btn-primary">Search</button>
    </form>
</div>

<!-- Navbar -->
<?php include_once('navbar.php') ?>

<section class="content">
    <div class="body_scroll">
        <div class="block-header">
            <div class="row">
                <div class="col-lg-7 col-md-6 col-sm-12">
                    <h2>Search Company</h2>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="https://chemistryservice.rimpido.com"><i class="zmdi zmdi-home"></i> Chempo</a></li>
                        <li class="breadcrumb-item active">Companies</li>
                        <li class="breadcrumb-item active">Search Company</li>
                    </ul>
                    <button class="btn btn-primary btn-icon mobile_menu" type="button"><i class="zmdi zmdi-sort-amount-desc"></i></button>
                </div>
                <div class="col-lg-5 col-md-6 col-sm-12">                
                    <button class="btn btn-primary btn-icon float-right right_icon_toggle_btn" type="button"><i class="zmdi zmdi-arrow-right"></i></button>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="header">
                            <!--<h2><strong>Basic</strong> Examples </h2>-->
                            <!--
                            <ul class="header-dropdown">
                                <li class="dropdown"> <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <i class="zmdi zmdi-more"></i> </a>
                                    <ul class="dropdown-menu dropdown-menu-right slideUp">
                                        <li><a href="javascript:void(0);">Action</a></li>
                                        <li><a href="javascript:void(0);">Another action</a></li>
                                        <li><a href="javascript:void(0);">Something else</a></li>
                                    </ul>
                                </li>
                                <li class="remove">
                                    <a role="button" class="boxs-close"><i class="zmdi zmdi-close"></i></a>
                                </li>
                            </ul>
                        -->
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <form id="userAction" method="post">
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                    <thead>
                                        <tr>
                                            <th>Company Name</th>
                                            <th>Address</th>
                                            <th width="30%">Action</th>
                                           
                                        </tr>
                                    </thead>
                                   
                                    <tbody>
                                <?php
                                                unset($_SESSION['company-uid']);
                                                $ch = new Chempo();
                                                $rows = $ch->companiesList();
                                                foreach ($rows as $row) 
                                                {
                                            ?>
                        
                                                    <tr>
                                                    <td><?php echo $row['company_name']; ?></td>
                                                    <td><?php echo $row['compAddress']; ?></td>
                                                    

                                                     <td>
                                                        <?php
                                                        if($_SESSION['user_role_id'] == '1')
                                                        {
                                                        ?>

                                                           <!-- EDIT COMPANY DETAILS -->
                                                            <button type="submit" class="btn btn-sm btn-outline-info waves-effect" name="editCompany" value="<?php echo $row['company_id']; ?>" onclick='gotoAction("edit");' disabled><i class="zmdi zmdi-edit" ></i>&nbsp;Edit</button> &nbsp;
                                                        
                                                            <!-- DELETE COMPANY DETAILS -->
                                                            <button type="button" class="btn btn-sm btn-outline-danger deleteCompID" data-id="<?php echo $row['company_id']; ?>" disabled><i class="zmdi zmdi-delete"></i>&nbsp;Delete</button>&nbsp;
                                                            
                                                            <!-- VIEW COMPANY DETAILS -->
                                                            <button type="submit" class="btn btn-sm btn-outline-success" name="viewCompany" value="<?php echo $row['company_id']; ?>" onclick='gotoAction("view");'><i class="zmdi zmdi-eye"></i>&nbsp;View</button>

                                                        <?php

                                                        }
                                                        else if($_SESSION['user_role_id'] == '2')
                                                        {

                                                            if($row['company_id'] == $_SESSION['company_id'])
                                                            {

                                                        ?>
                                                                 <!-- EDIT COMPANY DETAILS -->
                                                                 <button type="submit" class="btn btn-sm btn-outline-info waves-effect" name="editCompany" value="<?php echo $row['company_id']; ?>" onclick='gotoAction("edit");'><i class="zmdi zmdi-edit" ></i>&nbsp;Edit</button> &nbsp;
                                                        
                                                                <!-- DELETE COMPANY DETAILS -->
                                                                <button type="button" class="btn btn-sm btn-outline-danger deleteCompID" data-id="<?php echo $row['company_id']; ?>"><i class="zmdi zmdi-delete"></i>&nbsp;Delete</button>&nbsp;
                                                        
                                                                <!-- VIEW COMPANY DETAILS -->
                                                                <button type="submit" class="btn btn-sm btn-outline-success" name="viewCompany" value="<?php echo $row['company_id']; ?>" onclick='gotoAction("view");'><i class="zmdi zmdi-eye"></i>&nbsp;View</button>




                                                            <?php

                                                             }
                                                             else
                                                             {
                                                            ?>
                                                                <!-- EDIT COMPANY DETAILS -->
                                                                <button type="submit" class="btn btn-sm btn-outline-info waves-effect" name="editCompany" value="<?php echo $row['company_id']; ?>" onclick='gotoAction("edit");' disabled><i class="zmdi zmdi-edit" ></i>&nbsp;Edit</button> &nbsp;
                                                                
                                                                <!-- DELETE COMPANY DETAILS -->
                                                                <button type="button" class="btn btn-sm btn-outline-danger deleteCompID" data-id="<?php echo $row['company_id']; ?>" disabled><i class="zmdi zmdi-delete" ></i>&nbsp;Delete</button>&nbsp;
                                                        
                                                                <!-- VIEW COMPANY DETAILS -->
                                                                <button type="submit" class="btn btn-sm btn-outline-success" name="viewCompany" value="<?php echo $row['company_id']; ?>" onclick='gotoAction("view");'><i class="zmdi zmdi-eye"></i>&nbsp;View</button>

                                                                    

                                                            <?php
                                                            }
                                                            ?>
                                                         

                                                        <?php
                                                        }
                                                        else if($_SESSION['user_role_id'] == '3')
                                                        {
                                                        ?>
                                                        <!-- EDIT COMPANY DETAILS -->
                                                         <button type="submit" class="btn btn-sm btn-outline-info waves-effect" name="editCompany" value="<?php echo $row['company_id']; ?>" onclick='gotoAction("edit");'><i class="zmdi zmdi-edit" ></i>&nbsp;Edit</button> &nbsp;
                                                        
                                                        <!-- VIEW COMPANY DETAILS -->
                                                        <button type="submit" class="btn btn-sm btn-outline-success" name="viewCompany" value="<?php echo $row['company_id']; ?>" onclick='gotoAction("view");'><i class="zmdi zmdi-eye"></i>&nbsp;View</button>

                                                        <?php
                                                        }
                                                        else if($_SESSION['user_role_id'] == '4')
                                                        {
                                                        ?>
                                                        <!-- EDIT COMPANY DETAILS -->
                                                        <button type="submit" class="btn btn-sm btn-outline-info waves-effect" name="editCompany" value="<?php echo $row['company_id']; ?>" onclick='gotoAction("edit");'><i class="zmdi zmdi-edit" ></i>&nbsp;Edit</button> &nbsp; 
                                                        
                                                        <!-- DELETE COMPANY DETAILS -->
                                                        <button type="button" class="btn btn-sm btn-outline-danger deleteCompID" data-id="<?php echo $row['company_id']; ?>"><i class="zmdi zmdi-delete"></i>&nbsp;Delete</button>&nbsp;
                                                        
                                                        <!-- VIEW COMPANY DETAILS -->
                                                        <button type="submit" class="btn btn-sm btn-outline-success" name="viewCompany" value="<?php echo $row['company_id']; ?>" onclick='gotoAction("view");'><i class="zmdi zmdi-eye"></i>&nbsp;View</button>
                                                        
                                                        <?php
                                                        }

                                                        ?>

                                                        

                                                       


                                                    </td>
                                                    </tr>
                                            <?php
                                                }

                                            ?>
                                
                            </tbody>
                                </table>
                            </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
<!-- JS -->
<script type="text/javascript">
    $(".deleteCompID").click(function () {
    var ids = $(this).attr('data-id');
    $("#companyID").val( ids );
    $('#myModal').modal('show');
});

function gotoAction(action_name) {
    document.getElementById('userAction').action;
   if(action_name == "view"){
       document.getElementById('userAction').action = "company-view.php";
       document.getElementById('userAction').submit();
   }else if(action_name == "edit"){
       document.getElementById('userAction').action = "company-edit.php";
       document.getElementById('userAction').submit();
   }
}
    
</script>

<!--///////////////////////////////////////////////Delete Modal//////////////// -->
<div class="modal fade" id="myModal" role="dialog" >
    <div class="modal-dialog modal-dialog-centered">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">

          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h5 class="modal-title" id="exampleModalLongTitle"><i class="zmdi zmdi-alert-triangle"></i></i>&nbsp;Confirm</h5><br>
        </div>
        <form  method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <div class="modal-body">
          Are you sure you want to delete this Company ?<br>
        You can't undo this action.<br>
            <input type="text" class="form-control" name="companyID" id="companyID" value="" hidden>
         
        </div>
        <div class="modal-footer">
            
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn btn-outline-danger waves-effect" name="delete-company" value=""><i class="zmdi zmdi-delete" ></i>&nbsp;Yes</button>
            
          

        </div>
        </form>
      </div>
      
    </div>
  </div>
        </div>
        
    </div>
</section>

<!-- Jquery Core Js --> 
<script src="assets/bundles/libscripts.bundle.js"></script> <!-- Lib Scripts Plugin Js --> 
<script src="assets/bundles/vendorscripts.bundle.js"></script> <!-- Lib Scripts Plugin Js --> 

<script src="assets/bundles/mainscripts.bundle.js"></script><!-- Custom Js --> 

<script src="assets/bundles/datatablescripts.bundle.js"></script>
<script src="assets/plugins/jquery-datatable/buttons/dataTables.buttons.min.js"></script>
<script src="assets/plugins/jquery-datatable/buttons/buttons.bootstrap4.min.js"></script>
<script src="assets/plugins/jquery-datatable/buttons/buttons.colVis.min.js"></script>
<script src="assets/plugins/jquery-datatable/buttons/buttons.flash.min.js"></script>
<script src="assets/plugins/jquery-datatable/buttons/buttons.html5.min.js"></script>
<script src="assets/plugins/jquery-datatable/buttons/buttons.print.min.js"></script>

<script src="assets/js/pages/tables/jquery-datatable.js"></script>
</body>


</html>