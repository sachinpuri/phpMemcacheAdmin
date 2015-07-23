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

if (isset($_POST['cmdDelete']) && isset($_POST['keys']) && count($_POST['cmdDelete']) > 0) {
    foreach ($_POST['keys'] as $key) {
        $mem->delete($key);
    }
}
if (strlen($_GET['search']) == 0) {
    include "home.php";
    die;
}

if (isset($_GET['delete'])) {
    $mem->delete($_GET['delete']);
}

$allSlabs = $mem->getSlabs();
#pr($allSlabs);die;
$allKeys = array();
$ctr=0;
foreach($allSlabs as $server=>$slabs){
    foreach ($slabs as $slabId => $slabDetails) {        
        $keys = $mem->getKeys(getServerId($server), $slabId);
        $keys = array_reverse($keys);
        foreach ($keys as $key) {
            if (is_numeric(strpos($key, $_GET['search']))) {
                $allKeys[$server][] = $key;
            }
        }
    }
    $ctr++;
}
#pr($allKeys);die;
if (isset($_GET['key'])) {
    $value = $mem->get($_GET['key']);
} else {
    $value = $mem->get($_GET['search']);
}
?>

<?php if ($value != 'NO_RESULT_FOUND') { ?>
    <div id="value">
        <div style="background:#000; color:#fff; padding:5px">
            <b>Search Result for: </b><?php echo $_GET['search'] ?>
            <span id="btnKeys">Similar Keys
                <div id="tblKeys" style="; overflow-x: hidden">
                    <table cellspacing="0" cellpadding="5" style="width:100%; margin:5px; float: left">
                        <tr>
                            <td class="table-heading" colspan="4">Search Result for <?php echo $_GET['search'] ?></td>
                        </tr>
                        <?php
                        $i = 0;
                        foreach ($allKeys as $no => $key) {
                            $i++;
                            $url = 'index.php?action=search&search=' . $_GET['search'] . '&key=' . $key;
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
            pr($value);
            ?>
        </div>
    </div>
<?php } else { ?>
    <form method="post">
        <input type="submit" name="cmdDelete" value="Delete Selected"/>
        <table cellspacing="0" cellpadding="5" style="margin:10px auto">
            <tr>
                <td class="table-heading" colspan="5" align="center">Search Result for <?php echo $_GET['search'] ?></td>
            </tr>
            <tr class="table-heading">
                <td width="10"><input type="checkbox" name="keys[]"/></td>
                <td width="50">#</td>
                <td width="600">Key</td>
                <td width="50">View</td>
                <td width="50">Delete</td>
            </tr>
            <?php
            $i = 0;
            foreach ($allKeys as $server => $keys) {
            foreach ($keys as $no => $key) {
                $i++;
                ?>
                <tr class="<?php echo ($i % 2 == 0) ? 'table-even' : 'table-odd' ?>">
                    <td width="10"><input type="checkbox" name="keys[]" value="<?php echo $key ?>"/></td>
                    <td width="50"><?php echo $no + 1 ?></td>
                    <td width=""><?php echo $key ?></td>
                    <td width="50"><a href="index.php?action=search&search=<?php echo $_GET['search'] ?>&key=<?php echo $key ?>">View</a></td>
                    <td width="50"><a href="index.php?action=search&search=<?php echo $_GET['search'] ?>&delete=<?php echo $key ?>">Delete</a></td>
                </tr>
            <?php }} ?>
        </table>  
    </form>
<?php } ?>