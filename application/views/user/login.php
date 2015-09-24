<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<?php echo validation_errors(); ?>
<!-- Forms -->
<div class="docs-section">
    <h4 class="docs-header"><?php echo isset($meta['title']) ? $meta['title'] : 'Login';?> &nbsp; OR &nbsp; <?php echo anchor('user/apply', 'Apply', 'data-pjax="#pjax-container"')?></h4>
    <div class="docs-example docs-example-forms">
        <?php echo form_open(current_url(), array('id' => 'myform'));?>
        <div class="row">
            <label for="emailInput">Your email</label>
            <?php echo form_input(array('class' => 'u-full-width', 'id' => 'emailInput', 'name' => 'email', 'type' => 'email', 'placeholder' => 'test@mailbox.com'));?>
            <label for="passwordInput">Your password</label>
            <?php echo form_password(array('class' => 'u-full-width', 'id' => 'passwordInput', 'name' => 'password'));?>
        </div>
        <?php echo form_button(array('class' => 'button-primary', 'id' => 'loginBtn'), 'Let Me In.');?> &nbsp; OR &nbsp; <?php echo anchor('user/forget', 'Request A Reset', 'data-pjax="#pjax-container"');?>
        <?php echo form_close(); ?>
    </div>
</div>