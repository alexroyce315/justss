<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!-- Forms -->
<div class="docs-section">
    <h4 class="docs-header"><?php echo isset($meta['title']) ? $meta['title'] : 'Node Edit';?></h4>
    <div class="docs-example docs-example-forms">
        <?php echo form_open(current_url(), array('id' => 'myform'));?>
        <div class="row">
            <label for="nameInput">Name</label>
            <?php echo form_input(array('class' => 'u-full-width', 'id' => 'nameInput', 'name' => 'name', 'placeholder' => 'Name', 'value' => isset($node) ? $node['node_name'] : NULL));?>
            <label for="serverInput">IP/Server</label>
            <?php echo form_input(array('class' => 'u-full-width', 'id' => 'serverInput', 'name' => 'server', 'placeholder' => 'IP/Server', 'value' => isset($node) ? $node['node_server'] : NULL));?>
            <label for="statusRadio">Status</label>
            <?php echo form_radio('status', '1', (isset($node) && '1' == $node['node_status']) ? TRUE : NULL);?> &nbsp; Normal &nbsp;&nbsp;&nbsp;&nbsp; <?php echo form_radio('status', '0', (!isset($node) || '0' == $node['node_status']) ? TRUE : NULL);?> &nbsp; Stoped
            <label for="methodDrowdown">Method</label>
            <?php echo form_dropdown('method', $methods, isset($node) ? $node['node_method'] : NULL, 'class="u-full-width" id="methodDrowdown"');?>
            <label for="orderInput">Display Order</label>
            <?php echo form_input(array('class' => 'u-full-width', 'id' => 'orderInput', 'name' => 'order', 'placeholder' => 'Display Order', 'value' => isset($node) ? $node['node_order'] : NULL));?>
            <label for="infoInput">Info</label>
            <?php echo form_textarea(array('class' => 'u-full-width', 'id' => 'infoInput', 'name' => 'info', 'placeholder' => 'Info', 'value' => isset($node) ? $node['node_info'] : NULL));?>
        </div>
        <?php echo form_button(array('class' => 'button-primary', 'id' => 'editBtn'), 'Edit It.');?>
        <?php echo form_close(); ?>
    </div>
</div>
<!--End Document-->