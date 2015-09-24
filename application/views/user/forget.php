<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<?php echo validation_errors(); ?>

<!-- Forms -->
<div class="docs-section">
    <h4 class="docs-header"><?php echo isset($meta['title']) ? $meta['title'] : 'Reset';?></h4>
    <div class="docs-example docs-example-forms">
        <?php echo form_open(current_url(), array('id' => 'myform'));?>
        <div class="row">
            <label for="emailInput">Your email</label>
            <?php echo form_input(array('class' => 'u-full-width', 'id' => 'emailInput', 'name' => 'email', 'type' => 'email', 'placeholder' => 'test@mailbox.com'));?>
        </div>
        <?php echo form_button(array('class' => 'button-primary', 'id' => 'forgetBtn'), 'Request A Reset.');?> &nbsp; OR &nbsp; <?php echo anchor('user/login', 'Login', 'data-pjax="#pjax-container"');?>
        <?php echo form_close(); ?>
    </div>
</div>