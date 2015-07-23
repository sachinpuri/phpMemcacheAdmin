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
<div style="height:50px; background: #514745; margin-bottom:0px">
    <h1 style="float:left">phpMemcacheAdmin-V1</h1>
    <form method="get" action='index.php' style="float:right; margin:10; padding:0">
        <?php
        $key = '';
        if (isset($_GET['action']) && $_GET['action'] == 'search') {
            $key = $_GET['search'];
        }
        ?>          
        <input type="hidden" name="action" value="search"/>
        <input type="text" name="search" placeholder="key" value="<?php echo $key ?>"/>
        <input type="submit" value="Search"/>
    </form>
</div>