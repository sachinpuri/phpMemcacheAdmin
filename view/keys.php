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

$keys=array();
if (isset($_GET['delete'])) {
    $mem->delete($_GET['delete']);
}

if (isset($_GET['slabId']) && is_numeric($_GET['slabId'])) {
    if(isset($_GET['server'])){
        $keys = $mem->getKeys($_GET['server'], $_GET['slabId'], $config['limit']);
        $keys = array_reverse($keys);
    }
}
?>

<?php if (isset($keys)) { ?>
    <?php if (isset($_GET['view'])) { ?>
        <div id="value">
            <div style="background:#000; color:#fff; padding:5px">
                <b>Key: </b><?php echo $_GET['view'] ?> 
                <span id="btnKeys">Keys
                    <div id="tblKeys">
                        <table cellspacing="0" cellpadding="5">
                            <?php
                            $i = 0;
                            foreach ($keys as $no => $key) {
                                $i++;
                                $url = 'index.php?action=keys&slabId=' . $_GET['slabId'] . '&view=' . $key;
                                ?>
                                <tr class="<?php echo ($i % 2 == 0) ? 'table-even' : 'table-odd' ?>">
                                    <td width="400" title="<?php echo $key ?>"><a href="<?php echo $url ?>" style="width:100%; display: inline-block"><?php echo substr($key, 0, 50) ?></a></td>
                                </tr>
                            <?php } ?>
                        </table>
                    </div>
                </span>
            </div>
            <div style="margin:5px; overflow:auto; height:90%">
                <?php
                $key = $_GET['view'];
                $value = $mem->get($key);
                if (is_array($value)) {
                    pr($value[$key]);
                } else {
                    echo $value;
                }
                ?>
            </div>
        </div>
    <?php } else { ?>
        <table cellspacing="0" cellpadding="5" style="margin:10px auto">
            <tr class="table-heading">
                <td width="50">#</td>
                <td width="600">Key</td>
                <td width="50">View</td>
                <td width="50">Delete</td>
            </tr>
            <?php
            $i = 0;
            foreach ($keys as $no => $key) {
                $i++;
                ?>
                <tr class="<?php echo ($i % 2 == 0) ? 'table-even' : 'table-odd' ?>">
                    <td width="50"><?php echo $no + 1 ?></td>
                    <td width="600"><?php echo $key ?></td>
                    <td width="50"><a href="index.php?action=keys&slabId=<?php echo $_GET['slabId'] ?>&view=<?php echo $key ?>">View</a></td>
                    <td width="50"><a href="index.php?action=keys&slabId=<?php echo $_GET['slabId'] ?>&delete=<?php echo $key ?>"  onclick="return confirm('Are you sure?')">Delete</a></td>
                </tr>
            <?php } ?>
        </table>
    <?php } ?>
<?php } ?>
