<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!-- Forms -->
<div class="docs-section">
    <h4 class="docs-header"><?php echo isset($meta['title']) ? $meta['title'] : 'User Edit';?></h4>
    <div class="docs-example docs-example-forms">
        <?php echo form_open(current_url(), array('id' => 'myform'));?>
        <div class="row">
            <label for="emailInput">Email</label>
            <?php echo form_input(array('class' => 'u-full-width', 'id' => 'emailInput', 'name' => 'email', 'type' => 'email', 'placeholder' => 'test@mailbox.com', 'value' => isset($user) ? $user['email'] : NULL));?>
            <label for="passwordInput">Password</label>
            <?php echo form_password(array('class' => 'u-full-width', 'id' => 'passwordInput', 'name' => 'pass', 'placeholder' => 'Password'));?>
            <label for="trafficInput">Traffic (B)</label>
            <?php echo form_input(array('class' => 'u-full-width', 'id' => 'trafficInput', 'name' => 'traffic', 'placeholder' => 'Traffic', 'value' => isset($user) ? $user['transfer_enable'] : NULL));?>
            <label for="enableRadio">Enable</label>
            <?php echo form_radio('enable', '1', (isset($user) && '1' == $user['enable']) ? TRUE : NULL);?> &nbsp; Normal &nbsp;&nbsp;&nbsp;&nbsp; <?php echo form_radio('enable', '0', (!isset($user) || '0' == $user['enable']) ? TRUE : NULL);?> &nbsp; Stoped
            
            <label for="portInput">Port &nbsp; <a href="javascript:;" id="portGenerate">Generate</a></label>
            <?php echo form_input(array('class' => 'u-full-width', 'id' => 'portInput', 'name' => 'port', 'placeholder' => 'Port', 'readonly'=>'true', 'value' => isset($user) ? $user['port'] : NULL));?>
            <label for="ssPasswordInput">Shadowsocks Password</label>
            <?php echo form_input(array('class' => 'u-full-width', 'id' => 'ssPasswordInput', 'name' => 'passwd', 'placeholder' => 'Shadowsocks Password', 'value' => isset($user) ? $user['passwd'] : NULL));?>
        </div>
        <?php echo form_button(array('class' => 'button-primary', 'id' => 'editBtn'), 'Edit It.');?>
        <?php echo form_close(); ?>
    </div>
</div>
<!--End Document-->