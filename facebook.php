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

<div id="fb-root"></div>


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
      <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <h1 class="page-title txt-color-blueDark"><i class="fa fa-bar-chart-o fa-facebook "></i> Facebook </h1>
      </div>
    </div>

    <div class="row">
      <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <!-- <fb:login-button data-scope="manage_pages,read_insights" data-show-faces="true" width="200" max-rows="1"></fb:login-button> -->
        
	<form class="col-xs-12 col-sm-3 col-md-3 col-lg-3 smart-form">
	  <section>
	    <label class="select">
	      <select id="facebook_pages"> </select> <i> </i>
	    </label>
	  </section>
	</form>
	
	  <div class="input-daterange input-group col-xs-12 col-sm-6 col-md-6 col-lg-4" id="datepicker">
	    <input type="text" class="input-sm form-control" id="since_date" placeholder="4/23/2014"/>
	    <span class="input-group-addon">to</span>
	    <input type="text" class="input-sm form-control" id="until_date" placeholder="4/24/2014" />
	  </div>
	  
	  <span class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
	    <button type="button" class="input-sm form-control" id="update_button">Go!</button>
	  </span>
      </div>
    </div>
    
    <section id="widget-grid" class="">
      <div class="row" id="charts_container">	
      </div>

      <div class="row">

    <!-- NEW WIDGET START -->
    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12 sortable-grid ui-sortable">

      <!-- Widget ID (each widget will need unique ID)-->
      
      <!-- end widget -->

    <div class="jarviswidget jarviswidget-sortable" id="wid-id-4" data-widget-editbutton="false" role="widget" style="">
        <!-- widget options:
        usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">

        data-widget-colorbutton="false"
        data-widget-editbutton="false"
        data-widget-togglebutton="false"
        data-widget-deletebutton="false"
        data-widget-fullscreenbutton="false"
        data-widget-custombutton="false"
        data-widget-collapsed="true"
        data-widget-sortable="false"

        -->
        <header role="heading"><div class="jarviswidget-ctrls" role="menu">   <a href="#" class="button-icon jarviswidget-toggle-btn" rel="tooltip" title="" data-placement="bottom" data-original-title="Collapse"><i class="fa fa-minus "></i></a> <a href="javascript:void(0);" class="button-icon jarviswidget-fullscreen-btn" rel="tooltip" title="" data-placement="bottom" data-original-title="Fullscreen"><i class="fa fa-resize-full "></i></a> <a href="javascript:void(0);" class="button-icon jarviswidget-delete-btn" rel="tooltip" title="" data-placement="bottom" data-original-title="Delete"><i class="fa fa-times"></i></a></div><div class="widget-toolbar" role="menu"><a data-toggle="dropdown" class="dropdown-toggle color-box selector" href="javascript:void(0);"></a><ul class="dropdown-menu arrow-box-up-right color-select pull-right"><li><span class="bg-color-green" data-widget-setstyle="jarviswidget-color-green" rel="tooltip" data-placement="left" data-original-title="Green Grass"></span></li><li><span class="bg-color-greenDark" data-widget-setstyle="jarviswidget-color-greenDark" rel="tooltip" data-placement="top" data-original-title="Dark Green"></span></li><li><span class="bg-color-greenLight" data-widget-setstyle="jarviswidget-color-greenLight" rel="tooltip" data-placement="top" data-original-title="Light Green"></span></li><li><span class="bg-color-purple" data-widget-setstyle="jarviswidget-color-purple" rel="tooltip" data-placement="top" data-original-title="Purple"></span></li><li><span class="bg-color-magenta" data-widget-setstyle="jarviswidget-color-magenta" rel="tooltip" data-placement="top" data-original-title="Magenta"></span></li><li><span class="bg-color-pink" data-widget-setstyle="jarviswidget-color-pink" rel="tooltip" data-placement="right" data-original-title="Pink"></span></li><li><span class="bg-color-pinkDark" data-widget-setstyle="jarviswidget-color-pinkDark" rel="tooltip" data-placement="left" data-original-title="Fade Pink"></span></li><li><span class="bg-color-blueLight" data-widget-setstyle="jarviswidget-color-blueLight" rel="tooltip" data-placement="top" data-original-title="Light Blue"></span></li><li><span class="bg-color-teal" data-widget-setstyle="jarviswidget-color-teal" rel="tooltip" data-placement="top" data-original-title="Teal"></span></li><li><span class="bg-color-blue" data-widget-setstyle="jarviswidget-color-blue" rel="tooltip" data-placement="top" data-original-title="Ocean Blue"></span></li><li><span class="bg-color-blueDark" data-widget-setstyle="jarviswidget-color-blueDark" rel="tooltip" data-placement="top" data-original-title="Night Sky"></span></li><li><span class="bg-color-darken" data-widget-setstyle="jarviswidget-color-darken" rel="tooltip" data-placement="right" data-original-title="Night"></span></li><li><span class="bg-color-yellow" data-widget-setstyle="jarviswidget-color-yellow" rel="tooltip" data-placement="left" data-original-title="Day Light"></span></li><li><span class="bg-color-orange" data-widget-setstyle="jarviswidget-color-orange" rel="tooltip" data-placement="bottom" data-original-title="Orange"></span></li><li><span class="bg-color-orangeDark" data-widget-setstyle="jarviswidget-color-orangeDark" rel="tooltip" data-placement="bottom" data-original-title="Dark Orange"></span></li><li><span class="bg-color-red" data-widget-setstyle="jarviswidget-color-red" rel="tooltip" data-placement="bottom" data-original-title="Red Rose"></span></li><li><span class="bg-color-redLight" data-widget-setstyle="jarviswidget-color-redLight" rel="tooltip" data-placement="bottom" data-original-title="Light Red"></span></li><li><span class="bg-color-white" data-widget-setstyle="jarviswidget-color-white" rel="tooltip" data-placement="right" data-original-title="Purity"></span></li><li><a href="javascript:void(0);" class="jarviswidget-remove-colors" data-widget-setstyle="" rel="tooltip" data-placement="bottom" data-original-title="Reset widget color to default">Remove</a></li></ul></div>
          <span class="widget-icon"> <i class="fa fa-table"></i> </span>
          <h2>Condenced table + combined prev. classes </h2>

        <span class="jarviswidget-loader"><i class="fa fa-refresh fa-spin"></i></span></header>

        <!-- widget div-->
        <div role="content">

          <!-- widget edit box -->
          <div class="jarviswidget-editbox">
            <!-- This area used as dropdown edit box -->

          </div>
          <!-- end widget edit box -->

          <!-- widget content -->
          <div class="widget-body no-padding">
            
            
            <div class="table-responsive">
            
              <table class="table table-bordered table-striped table-condensed table-hover smart-form has-tickbox">
                <thead>
                  <tr>
                    <th>
                      <label class="checkbox">
                        <input type="checkbox" name="checkbox-inline">
                        <i></i>
                      </label>
                    </th>
                    <th>Column name <a href="javascript:void(0);" class="btn btn-xs btn-default pull-right"><i class="fa fa-filter"></i></a> </th>
                    <th>Column name <a href="javascript:void(0);" class="btn btn-xs btn-default pull-right"><i class="fa fa-filter"></i></a></th>
                    <th>Column name <a href="javascript:void(0);" class="btn btn-xs btn-default pull-right"><i class="fa fa-filter"></i></a></th>
                    <th>Column name <a href="javascript:void(0);" class="btn btn-xs btn-default pull-right"><i class="fa fa-filter"></i></a></th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>
                      <label class="checkbox">
                        <input type="checkbox" name="checkbox-inline">
                        <i></i>
                      </label>
                    </td>
                    <td>Row 1</td>
                    <td>Row 2</td>
                    <td>Row 3</td>
                    <td>Row 4</td>
                  </tr>
                  <tr>
                    <td>
                      <label class="checkbox">
                        <input type="checkbox" name="checkbox-inline">
                        <i></i>
                      </label>
                    </td>
                    <td>Row 1</td>
                    <td>Row 2</td>
                    <td>Row 3</td>
                    <td>Row 4</td>
                  </tr>
                  <tr>
                    <td>
                      <label class="checkbox">
                        <input type="checkbox" name="checkbox-inline">
                        <i></i>
                      </label>
                    </td>
                    <td>Row 1</td>
                    <td>Row 2</td>
                    <td>Row 3</td>
                    <td>Row 4</td>
                  </tr>
                  <tr>
                    <td>
                      <label class="checkbox">
                        <input type="checkbox" name="checkbox-inline">
                        <i></i>
                      </label>
                    </td>
                    <td>Row 1</td>
                    <td>Row 2</td>
                    <td>Row 3</td>
                    <td>Row 4</td>
                  </tr>
                  <tr>
                    <td>
                      <label class="checkbox">
                        <input type="checkbox" name="checkbox-inline">
                        <i></i>
                      </label>
                    </td>
                    <td>Row 1</td>
                    <td>Row 2</td>
                    <td>Row 3</td>
                    <td>Row 4</td>
                  </tr>
                  <tr>
                    <td>
                      <label class="checkbox">
                        <input type="checkbox" name="checkbox-inline">
                        <i></i>
                      </label>
                    </td>
                    <td>Row 1</td>
                    <td>Row 2</td>
                    <td>Row 3</td>
                    <td>Row 4</td>
                  </tr>
                </tbody>
              </table>
              
            </div>
            
          </div>
          <!-- end widget content -->

        </div>
        <!-- end widget div -->

      </div></article>
    <!-- WIDGET END -->

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
<script>
$('#sandbox-container .input-daterange').datepicker({
    autoclose: true,
    todayHighlight: true
});
</script>

<?php 
  //include footer
     include("inc/footer.php"); 
?>
