<?php
  
  // Initilize the page. Authentication and session management.
  require_once("inc/init.php");
  
  //require UI configuration (nav, ribbon, etc.)
  require_once("inc/config.ui.php");
  
  /*---------------- PHP Custom Scripts ---------
  
  YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
  E.G. $page_title = "Custom Title" */
  
  $page_title = "Facebook";
  
  /* ---------------- END PHP Custom Scripts ------------- */
  
  //include header
  //you can add your custom css in $page_css array.
  //Note: all css files are inside css/ folder
  $page_css[] = "your_style.css";
  include("inc/header.php");
  
  //include left panel (navigation)
  //follow the tree in inc/config.ui.php
  $page_nav["facebook"]["active"] = true;
  include("inc/nav.php");
  
?>

<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
  <?php
    //configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
    //$breadcrumbs["New Crumb"] => "http://url.com"
    include("inc/ribbon.php");
  ?>
  
  <!-- MAIN CONTENT -->
  <div id="content">
    <div class="row">
      <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
	<h1 class="page-title txt-color-blueDark"><i class="fa fa-bar-chart-o fa-facebook "></i> Facebook</h1>
      </div>
    </div>
    
    <section id="widget-grid" class="">
      <div class="row" id="charts_container">	
      </div>
    </section>  
  </div>
  <!-- END MAIN CONTENT -->
  
</div>
<!-- END MAIN PANEL -->
<!-- ==========================CONTENT ENDS HERE ========================== -->

<?php 
  //include required scripts
        include("inc/scripts.php"); 
?>

<!-- PAGE RELATED PLUGIN(S) -->

<!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->
<script src="<?php echo ASSETS_URL; ?>/js/plugin/flot/jquery.flot.cust.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/flot/jquery.flot.resize.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/flot/jquery.flot.fillbetween.min.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/flot/jquery.flot.orderBar.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/flot/jquery.flot.pie.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/flot/jquery.flot.tooltip.js"></script>
<script src="js/chart-manager.js"></script>

<script src="js/facebook-page.js"></script>

<?php 
  //include footer
     include("inc/footer.php"); 
?>
