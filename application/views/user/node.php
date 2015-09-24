<div class="docs-section">
    <h4 class="docs-header"><?php echo $node['node_name'];?>(<?php echo '1' == $node['node_status'] ? 'Normal' : 'Stoped';?>)</h4>
    <p><?php echo $node['node_info'];?></p>
    <div class="row">
        <div class="six columns">
            <div id="qrcode" data-url="<?php echo $ssurl;?>"></div>
        </div>
        <div class="six columns">
            <pre><code>{
    "server":"<?php echo $node['node_server'];?>",
    "server_port":<?php echo $node['node_port'];?>,
    "password":"<?php echo $node['node_password'];?>",
    "timeout":600,
    "method":"<?php echo $node['node_method'];?>"
}
            </code></pre>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        loadJS('myScript', '<?php echo base_url('assets/js/qrcode.min.js');?>');
        var qrcode = new QRCode('qrcode');
        qrcode.makeCode($('#qrcode').attr('data-url'));
    });
</script>