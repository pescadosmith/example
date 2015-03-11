<!DOCTYPE html>

<html>
    <head>
        <meta name="author" content="Andrew Hollis Smith"/>
        <meta name="application-name" content="<?php echo $screenName; ?>"/>
        <meta name="description" content="<?php echo $description; ?>"/>
        <meta name="keywords" content="<?php echo $keywords; ?>"/>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/> 
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <title><?php echo $screenName; ?></title>

        <link rel="shortcut icon" href="<?php echo base_url('css/images/favicon64.ico'); ?>" />
        <link rel="icon" href="<?php echo base_url('css/images/favicon64.ico'); ?>" sizes="64x64 32x32 24x24 16x16" />
        <!-- CSS -->

        <link href="<?php echo base_url('css/bootstrap.min.css'); ?>" rel="stylesheet"/>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('css/custom/bootC.css'); ?>" media="screen" />
        <link href="<?php echo base_url('css/bootstrap-responsive.css'); ?>" rel="stylesheet">
        <link href="<?php echo base_url('css/jquery.ui.all.css'); ?>" rel="stylesheet"/>
        <!-- TABLE SORTER CSS -->
        <link href="<?php echo base_url('css/theme.bootstrap.css'); ?>" rel="stylesheet">

        <script type="text/javascript">
            var site_url = '<?php echo base_url(); ?>';
        </script>

        <script src="<?php echo base_url('js/jquery-1.10.2.min.js'); ?>"></script>
        <script src="<?php echo base_url('js/jquery-ui.js'); ?>"></script>
        <script src="<?php echo base_url('js/bootstrap.js'); ?>"></script>
        <script src="<?php echo base_url('js/bootstrap-tooltip.js'); ?>"></script>
        <script src="<?php echo base_url('js/bootstrap-transition.js'); ?>"></script>
        <script src="<?php echo base_url('js/jquery.ui.datepicker.js'); ?>"></script>
        <script src="<?php echo base_url('js/jquery.tablesorter.js'); ?>"></script>
        <script src="<?php echo base_url('js/jquery.tablesorter.widgets.js'); ?>"></script>
        <script src="<?php echo base_url('js/jquery.ui.selectable.js'); ?>"></script>

        <!-- Add fancyBox main JS and CSS files -->
        <script type="text/javascript" src="<?php echo base_url('js/jquery.fancybox.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo base_url('js/jquery.fancybox.pack.js'); ?>"></script>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('css/jquery.fancybox.css'); ?>" media="screen" />
        <!-- Add Button helper (this is optional) -->
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('css/jquery.fancybox-buttons.css'); ?>" />
        <script type="text/javascript" src="<?php echo base_url('js/jquery.fancybox-buttons.js'); ?>"></script>

        <!-- Add Thumbnail helper (this is optional) -->
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('css/jquery.fancybox-thumbs.css'); ?>" />
        <script type="text/javascript" src="<?php echo base_url('js/jquery.fancybox-thumbs.js'); ?>"></script>

        <!-- Add Media helper (this is optional) -->
        <script type="text/javascript" src="<?php echo base_url('js/jquery.fancybox-media.js'); ?>"></script>

        <script src="<?php echo base_url('js/custom/fancybox.js'); ?>"></script>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('css/custom/fanC.css'); ?>" media="screen" />

        <link rel="stylesheet" type="text/css" href="<?php echo base_url('css/custom/jqui.css'); ?>" media="screen" />

        <script src="<?php echo base_url('js/ui.dropdownchecklist.js'); ?>"></script>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('css/ui.dropdownchecklist.standalone.css'); ?>" media="screen" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('css/custom/dropdownC.css'); ?>" media="screen" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('css/style.css'); ?>" media="screen" />
    </head>
    <body>
        <?php include_once("main_menu.php"); ?>
        <div id = "wrap" class = "group">
            <div id="main" class = "group" style='background-color:rgb(0,114,164);'>
                 <!-- MAIN CONTENT -->
                 <div id="header" class="group">
                    <h1>
                        <?php echo html_escape($title); ?>
                        <?php if (!empty($pic)) { ?>
                            <?php echo "<img src='" . base_url('img/pandas/') . "/$pic' style='height:4em;margin:-25px 20px -40px 0px;float:right;border-radius: 10px;'/>"; ?>
                        <?php } ?>
                    </h1>            
                </div>
                <div id="main_content" class ="row-fluid">
                    <div class='container-fluid'>
                        <div id="resultDivRow" class="row-fluid">
                            <div id='resultDivSpan' class='span12'>