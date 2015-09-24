<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!--Basic Page Needs-->
    <meta charset="utf-8"/>
    {meta}
    <!--Mobile Specific Metas-->
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <!--CSS-->
    <link rel="stylesheet" href="<?php echo base_url('assets/css/normalize.css');?>"/>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/skeleton.css');?>"/>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/custom.css');?>"/>
    <!--link rel="stylesheet" href="<?php echo base_url('assets/css/github-prettify-theme.css');?>"-->
    <!--Scripts-->
    <script src="<?php echo base_url('assets/js/jquery.min.js');?>"></script>
    <script src="<?php echo base_url('assets/js/jquery.pjax.min.js');?>"></script>
    <script src="<?php echo base_url('assets/js/nprogress.js');?>"></script>
    <script src="<?php echo base_url('assets/js/site.js');?>"></script>
</head>
<body class="code-snippets-visible">
    <!--Primary Page Layout-->
    <div class="container">
        <div class="navbar-spacer"></div>
        {navbar}
        <div id="pjax-container" role="bar">
            {content}
        </div>
        <h6 class="docs-header">Contact</h6>
        <p>
            &copy; <?php echo $_SERVER['SERVER_NAME'];?>. &nbsp; <?php if(!empty($user)){?> &nbsp; Telegram:+1(607)52 70176. &nbsp; <?php }?>
            <br/>
            Based On CI & MariaDB & Skeleton & jQuery & Pjax & NProgress & notifIt &<?php if(!empty($user)){echo 'Shadowsocks & manyuser & Chart & QRcode &';}?> etc.
        </p>
    </div>
    <!--End Document-->
    <!--Scripts-->
    <script src="<?php echo base_url('assets/js/notifIt.js');?>"></script>
    <!--script src="<?php echo base_url('assets/js/run_prettify.js');?>"></script-->
    <script id="myScript" src=""></script>
</body>
</html>