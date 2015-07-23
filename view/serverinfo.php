<?php
/**
 * Copyright 2015 Sachin Puri
 * 
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and limitations
 * under the License.
 *
 * @author mail@sachinpuri.com
 * @since 19/07/2015
 */

foreach($mem->servers as $server){
$mem->connect($server['host'],$server['port']);
$stats=$mem->stats();
$stats=$mem->format($stats);
$stats=$stats['STAT'];
?>
<table cellspacing="0" cellpadding="5" style="margin:10px auto; float:left; margin:10px">
    <tr>
        <td class="table-heading" colspan="2"><?php echo $server['host'] ?>:<?php echo $server['port'] ?></td>
    </tr>
    <tr class="table-even">
        <td width="250">Process Id</td>
        <td width="150"><?php echo $stats['pid'] ?></td>
    </tr>
    <tr class="table-odd">
        <td>Up Time</td>
        <td><?php echo round($stats['uptime']/60) ?> Minutes</td>
    </tr>
    <tr class="table-even">
        <td>Version</td>
        <td><?php echo $stats['version'] ?></td>
    </tr>
    <tr class="table-odd">
        <td>Current Items</td>
        <td><?php echo $stats['curr_items'] ?></td>
    </tr>
    <tr class="table-even">
        <td>Total Items</td>
        <td><?php echo $stats['total_items'] ?></td>
    </tr>
    <tr class="table-odd">
        <td>Total Connections</td>
        <td><?php echo $stats['total_connections'] ?></td>
    </tr>
    <tr class="table-even">
        <td>Current Connections</td>
        <td><?php echo $stats['curr_connections'] ?></td>
    </tr>
    <tr class="table-odd">
        <td>Available Cache Size</td>
        <td><?php echo round($stats['limit_maxbytes']/1024/1024,1) ?> Mb</td>
    </tr>
    <tr class="table-even">
        <td>Used Cache Size</td>
        <td><?php 
            $usedCacheSize=$stats['bytes'];
            $ctr=1;
            while($usedCacheSize>1024){
                $usedCacheSize=$usedCacheSize/1024;
                $ctr++;
            }
            echo round($usedCacheSize,0) . ' ' ;
            switch($ctr){
                case 1:
                    echo 'Bytes';
                    break;
                case 2:
                    echo 'Kb';
                    break;
                case 3:
                    echo 'Mb';
                    break;
                default:
                    break;
            }
            ?>
            
        </td>
    </tr>
    <tr class="table-odd">
        <td>Hits</td>
        <td><?php echo $stats['get_hits'] ?></td>
    </tr>
    <tr class="table-even">
        <td>Misses</td>
        <td><?php echo $stats['get_misses'] ?></td>
    </tr>
</table>
<?php
}
?>