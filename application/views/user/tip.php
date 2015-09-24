<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="docs-section">
    <h4 class="docs-header"><?php echo isset($meta['title']) ? $meta['title'] : (empty($user) ? $_SERVER['SERVER_NAME'] : 'Home');?></h4>
    <p><?php echo $result;?></p>
</div>