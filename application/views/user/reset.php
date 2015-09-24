<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!-- Forms -->
<div class="docs-section">
    <h4 class="docs-header"><?php echo isset($meta['title']) ? $meta['title'] : 'Password';?> &nbsp; OR &nbsp; <?php echo anchor('user/login', 'Login', 'data-pjax="#pjax-container"')?></h4>
    <div class="docs-example docs-example-forms">
        <?php echo form_open(current_url(), array('id' => 'myform'));?>
        <div class="row">
            <label for="emailInput">Email</label>
            <?php echo form_input(array('class' => 'u-full-width', 'id' => 'emailInput', 'name' => 'email', 'type' => 'email', 'placeholder' => 'test@mailbox.com', 'readonly'=>'true', 'value' => isset($user) ? $user['email'] : NULL));?>
            <label for="newPasswordInput">New Password (In Case Of Want To Change)</label>
            <?php echo form_password(array('class' => 'u-full-width', 'id' => 'newPasswordInput', 'name' => 'newPass', 'placeholder' => 'New Password'));?>
            <label for="confirmPasswordInput">Confirm New Password (The Same As Above)</label>
            <?php echo form_password(array('class' => 'u-full-width', 'id' => 'confirmPasswordInput', 'name' => 'confirmPass', 'placeholder' => 'Confirm New password'));?>
        </div>
        <?php echo form_button(array('class' => 'button-primary', 'id' => 'resetBtn'), 'Reset Password.');?>
        <?php echo form_close(); ?>
    </div>
</div>
<!--End Document-->