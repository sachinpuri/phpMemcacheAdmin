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
include 'inc/app.php'; 
?>
<html>
    <head>
        <link rel="stylesheet" href="inc/main.css" />
        <script type="text/javascript" src="inc/main.js"></script>
    </head>
    <body>
        <?php        
        include "inc/header.php";
        include "inc/nav.php";
        if(isset($_GET['action'])){
            if($_GET['action']!='config'){                
                $mem = new MemcacheClient($config['servers']);
            }
            include route($_GET['action']);
        }else{
             include "view/home.php";
        }
        ?>
    </body>
</html>
<?php
if(isset($mem)){
    $mem->disconnect();
}
?>