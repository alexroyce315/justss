<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<nav class="navbar">
    <div class="container">
        <ul class="navbar-list">
            <li class="navbar-item"><?php echo anchor('', 'Home', 'class="navbar-link" data-pjax="#pjax-container"');?></li>
            <?php if(!empty($user)){?>
            <li class="navbar-item"><?php echo anchor('user/tool', 'Tool', 'class="navbar-link" data-pjax="#pjax-container"');?></li>
            <!--li class="navbar-item"><?php echo anchor('user/plan', 'Plan', 'class="navbar-link" data-pjax="#pjax-container"');?></li-->
            <li class="navbar-item">
                <a class="navbar-link" href="javascript:(0);" data-popover="#accountNavPopover">Account</a>
                <div id="accountNavPopover" class="popover">
                    <ul class="popover-list">
                        <li class="popover-item">
                            <?php echo anchor(site_url('user').'#traffic', 'Traffic', 'class="popover-link" data-pjax="#pjax-container"');?>
                        </li>
                        <li class="popover-item">
                            <?php echo anchor(site_url('user').'#node', 'Node', 'class="popover-link" data-pjax="#pjax-container"');?>
                        </li>
                        <li class="popover-item">
                            <?php echo anchor(site_url('user/profile'), 'Profile', 'class="popover-link" data-pjax="#pjax-container"');?>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="navbar-item">
                <a class="navbar-link" href="javascript:(0);" data-popover="#logNavPopover">Log</a>
                <div id="logNavPopover" class="popover">
                    <ul class="popover-list">
                        <li class="popover-item">
                            <?php echo anchor(site_url('user/checkInLog'), 'CheckIn Log', 'class="popover-link" data-pjax="#pjax-container"');?>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="navbar-item"><?php echo anchor('user/logout', 'Logout', 'class="navbar-link"');?></li>
            <?php } else{?>
            <li class="navbar-item"><?php echo anchor('user/login', 'Login', 'class="navbar-link" data-pjax="#pjax-container"');?></li>
            <?php }?>
        </ul>
    </div>
</nav>