<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!-- Tables -->
<div class="docs-section" id="tables">
    <h4 class="docs-header"><?php echo isset($meta['title']) ? $meta['title'] : 'List';?></h4>
    <div class="docs-example">
        <?php echo $this->table->generate($list);?>
    </div>
    <?php echo $this->pagination->create_links();?>
</div>