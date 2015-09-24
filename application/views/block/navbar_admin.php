<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<nav class="navbar">
    <div class="container">
        <ul class="navbar-list">
            <li class="navbar-item"><?php echo anchor('admin', 'Home', 'class="navbar-link" data-pjax="#pjax-container"');?></li>
            <?php if(!empty($user)){?>
            <li class="navbar-item"><?php echo anchor('admin/node', 'Node', 'class="navbar-link" data-pjax="#pjax-container"');?></li>
            <li class="navbar-item"><?php echo anchor('admin/user', 'User', 'class="navbar-link" data-pjax="#pjax-container"');?></li>
            <li class="navbar-item">
                <a class="navbar-link" href="javascript:(0);" data-popover="#logNavPopover">Log</a>
                <div id="logNavPopover" class="popover">
                    <ul class="popover-list">
                        <li class="popover-item">
                            <?php echo anchor('admin/adminLog', 'Admin', 'class="popover-link" data-pjax="#pjax-container"');?>
                        </li>
                        <li class="popover-item">
                            <?php echo anchor('admin/mailLog', 'Mail', 'class="popover-link" data-pjax="#pjax-container"');?>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="navbar-item"><?php echo anchor('admin/profile', 'Profile', 'class="navbar-link" data-pjax="#pjax-container"');?></li>
            <li class="navbar-item"><?php echo anchor('admin/logout', 'Logout', 'class="navbar-link"');?></li>
            <?php } else{?>
            <li class="navbar-item"><?php echo anchor('admin/login', 'Login', 'class="navbar-link" data-pjax="#pjax-container"');?></li>
            <?php }?>
        </ul>
    </div>
</nav>