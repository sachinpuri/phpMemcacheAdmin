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
?>
<?php
$allSlabs = $mem->getSlabs();
$ctr=0;
foreach($allSlabs as $server=>$slabs){
?>
<div>
    <h2 class="slabs_h2">Server: <?php echo $server ?></h2>
    <ul id="slabs">
        <?php if (count($slabs) == 0) { ?>
            <h3>No key exists</h3>
        <?php } ?>
        <?php foreach ($slabs as $slabId => $slabDetails) { ?>
            <li>
                <a href="index.php?action=keys&slabId=<?php echo $slabId ?>&server=<?php echo getServerId($server) ?>" style="border-radius: 0 5px 0 0">Slab <?php echo $slabId ?></a>
                <table style="border:1px solid; height:70px; width:200px; background-color: #cdcdcd; display:block" cellpadding="5">
                    <tr>
                        <td>No of keys:</td>
                        <td><?php echo $slabDetails['number'] ?></td>
                    </tr>
                    <tr>
                        <td>Age: </td>
                        <td><?php echo round($slabDetails['age'] / 60) ?> Minutes</td>
                    </tr>
                </table>
                <?php #pr($slabDetails) ?>
            </li>
        <?php } ?>
    </ul>
    <div style="clear:both"></div>
</div>
<?php $ctr++; } ?>