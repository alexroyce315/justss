<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="docs-section">
    <h4 class="docs-header"><?php echo isset($meta['title']) ? $meta['title'] : 'List';?></h4>
    <div class="row">
        <div class="six columns">
            <label>Windows</label>
            <ul>
                <li><?php echo anchor(base_url('uploads/Shadowsocks-win-dotnet4.0.zip'), 'Windows 8 or Above')?></li>
                <li><?php echo anchor(base_url('uploads/Shadowsocks-win.zip'), 'Windows 7 or Below');?></li>
            </ul>
        </div>
        <div class="six columns">
            <label>Mac OS</label>
            <ul>
                <li><?php echo anchor(base_url('uploads/ShadowsocksX.dmg'), 'ShadowsocksX');?></li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="six columns">
            <label>Android</label>
            <ul>
                <li><?php echo anchor('https://play.google.com/store/apps/details?id=com.github.shadowsocks', 'Google Play');?></li>
                <li><?php echo anchor(base_url('uploads/com.github.shadowsocks.apk'), 'Shadowsocks');?></li>
            </ul>
        </div>
        <div class="six columns">
            <label>iOS</label>
            <ul>
                <li><?php echo anchor('https://itunes.apple.com/tc/app/shadowsocks/id665729974?mt=8', 'App Store');?></li>
            </ul>
        </div>
    </div>
</div>