<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<?php echo validation_errors(); ?>

<!-- Forms -->
<div class="docs-section">
    <h4 class="docs-header"><?php echo isset($meta['title']) ? $meta['title'] : 'Login';?></h4>
    <p>Forms are a huge pain, but hopefully these styles make it a bit easier. All inputs, select, and buttons are normalized for a common height cross-browser so inputs can be stacked or placed alongside each other.</p>
    <div class="docs-example docs-example-forms">
        <?php echo form_open(current_url(), array('id' => 'myform'));?>
        <div class="row">
            <label for="emailInput">Your email</label>
            <?php echo form_input(array('class' => 'u-full-width', 'id' => 'emailInput', 'name' => 'email', 'type' => 'email', 'placeholder' => 'test@mailbox.com'));?>
            <label for="passwordInput">Your password</label>
            <?php echo form_password(array('class' => 'u-full-width', 'id' => 'passwordInput', 'name' => 'password'));?>
        </div>
        <?php echo form_button(array('class' => 'button-primary', 'id' => 'loginBtn'), 'Let Me In.');?>
        <?php echo form_close(); ?>
    </div>
</div>