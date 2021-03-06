<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!-- Forms -->
<div class="docs-section">
    <h4 class="docs-header"><?php echo isset($meta['title']) ? $meta['title'] : 'Profile';?></h4>
    <div class="docs-example docs-example-forms">
        <?php echo form_open(current_url(), array('id' => 'myform'));?>
        <div class="row">
            <label for="emailInput">Your Email</label>
            <?php echo form_input(array('class' => 'u-full-width', 'id' => 'emailInput', 'name' => 'email', 'type' => 'email', 'placeholder' => 'test@mailbox.com', 'value' => isset($user) ? $user['email'] : NULL));?>
            <label for="newPasswordInput">New Password (In Case Of Want To Change)</label>
            <?php echo form_password(array('class' => 'u-full-width', 'id' => 'newPasswordInput', 'name' => 'newPass', 'placeholder' => 'New Password'));?>
            <label for="confirmPasswordInput">Confirm New Password (The Same As Above)</label>
            <?php echo form_password(array('class' => 'u-full-width', 'id' => 'confirmPasswordInput', 'name' => 'confirmPass', 'placeholder' => 'Confirm New password'));?>
            <label for="passwordInput">Current Password</label>
            <?php echo form_password(array('class' => 'u-full-width', 'id' => 'passwordInput', 'name' => 'pass', 'placeholder' => 'Current Password'));?>
        </div>
        <?php echo form_button(array('class' => 'button-primary', 'id' => 'submitBtn'), 'Make The Chage.');?>
        <?php echo form_close(); ?>
    </div>
</div>
<!--End Document-->