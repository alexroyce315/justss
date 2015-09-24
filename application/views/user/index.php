<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<div class="docs-section">
    <h4 class="docs-header">Account, <?php echo $this->session->userdata('userEmail');?></h4>
    <div class="row">
        <div class="six columns">
            <canvas id="traffic-chart"></canvas>
        </div>
        <div class="six columns">
            <ul>
                <?php if($user['last_check_in_time'] > strtotime('-1 day')){?>
                <li><?php echo anchor('user/checkInLog', 'CheckIn History', 'data-pjax="#pjax-container"');}else{?>
                <li id="checkIn" class="cursor"><a>Check In To Get More Traffic</a>
                <?php }?></li>
                <!--li><?php echo anchor('user/plan', 'Get More', 'data-pjax="#pjax-container"')?>.</li-->
            </ul>
        </div>
    </div>

    <label>Node<a href="#node"></a></label>
    <ul id="node">
        <?php if($nodes){ foreach($nodes as $item){?>
        <li><?php echo anchor('user/node/'.$item['id'], $item['node_server'], 'data-pjax="#pjax-container"');?>(<?php echo $item['node_status'];?>)</li>
        <?php }} else{?>
        <li>There is No Node.</li>
        <?php }?>
    </ul>
</div>
<script>
    $(document).ready(function(){
        loadJS('myScript', '<?php echo base_url('assets/js/chart.min.js');?>');
        var ctx = document.getElementById('traffic-chart').getContext("2d");
        var myNewChart = new Chart(ctx).Doughnut([{value:<?php echo $user['u'];?>,color:"#F7464A",highlight:"#FF5A5E",label:"Upload(GB)"},{value:<?php echo $user['d'];?>,color:"#FDB45C",highlight: "#FFC870",label:"Download(GB)"},{value:<?php echo $user['transfer_enable']-$user['u']-$user['d'];?>,color:"#46BFBD",highlight:"#5AD3D1",label:"Left(GB)"}]);
    });
</script>