<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="docs-section">
    <h4 class="docs-header">Welcome, <?php echo $this->session->userdata('adminEmail');?></h4>
    
    <div class="row">
        <div class="six columns">
            <label>Traffic<a href="#traffic"></a></label>
            <canvas id="traffic-chart"></canvas>
        </div>
        <div class="six columns">
            <label>Node<a href="#node"></a></label>
            <ul id="node">
                <?php if($nodes){ foreach($nodes as $item){?>
                <li><?php echo $item['node_server']?>(<?php echo $item['node_status'];?>)</li>
                <?php }} else{?>
                <li>暂时还没有可用的服务器</li>
                <?php }?>
            </ul>
        </div>
    </div>

    <div class="row">
        <div class="six columns">
            <label>Summary<a href="#summary"></a></label>
            <canvas id="user-chart"></canvas>
        </div>
        <div class="six columns">
            <label>Active<a href="#active"></a></label>
            <ul id="active">
                <li>Last 5 m: <?php echo $user['300'];?></li>
                <li>Last 1 h: <?php echo $user['3600'];?></li>
                <li>Last 24 h: <?php echo $user['86400'];?></li>
            </ul>
        </div>
    </div>
</div>
<script>
    loadJS('myScript', '<?php echo base_url('assets/js/chart.min.js');?>');
    var ctx = document.getElementById('traffic-chart').getContext("2d");
    var myNewChart = new Chart(ctx).Doughnut([{value:<?php echo $traffic['u'];?>,color:"#F7464A",highlight:"#FF5A5E",label:"Upload(GB)"},{value:<?php echo $traffic['d'];?>,color:"#FDB45C",highlight: "#FFC870",label:"Download(GB)"}]);
    var ctu = document.getElementById('user-chart').getContext("2d");
    var myChart = new Chart(ctu).Doughnut([{value:<?php echo $user['active'];?>,color:"#46BFBD",highlight:"#5AD3D1",label:"Active"},{value:<?php echo $user['user']-$user['active'];?>,color:"#F7464A",highlight:"#FF5A5E",label:"UnActive"}]);
</script>