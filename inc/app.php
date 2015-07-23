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
ini_set('max_execution_time',300);
session_start();

$fp=fopen('inc/cfg.php','r');
$config=fread($fp,1024);
fclose($fp);
$config=unserialize($config);

include "inc/memcache.php";

function route($action){
    switch($action)
    {
        case 'slabs':
            return "view/slabs.php";
            break;
        case 'keys':
            return "view/keys.php";
            break;
        case 'search':
            return "view/search.php";
            break;
        case 'config':
            return "view/config.php";
            break;
        case 'serverinfo':
            return "view/serverinfo.php";
            break;                
        default:                   
            break;
    }
}

function pr($cont) {
    echo "<pre>";
    print_r($cont);
    echo "</pre>";
}

function connection_error($config){
    echo '<div id="server-connect-error">Can\'t connect to cache server ' . $config['servers'][0]['host'] . ':'  . $config['servers'][0]['port'];
    echo '<a href="index.php?action=config">Change Config</a>';
    echo '</div>';
}

function getServerId($serverIp){
    GLOBAL $config;
    foreach($config['servers'] as $serverId=>$serverDetails){
        if($serverDetails['host']==$serverIp){
            return $serverId;
        }
    }
}
?>