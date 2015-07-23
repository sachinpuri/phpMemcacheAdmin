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
if(count($_POST)>0){
    $txtHost=$_POST['txtHost'];    
    $txtHost=explode(",",$txtHost);
    $servers=array();
    foreach($txtHost as $host){
        list($host,$port)=explode(":",$host);
        $server['host']=$host;
        $server['port']=$port;
        $servers[]=$server;
    }
    $config['servers']=$servers;
    $config['limit']=$_POST['txtLimit'];    
    $fp=fopen('inc/cfg.php','w');
    fwrite($fp,serialize($config));
    fclose($fp);
}

$fp=fopen('inc/cfg.php','r');
$config=fread($fp,1024);
fclose($fp);
$config=unserialize($config);

$len=count($config['servers']);
$servers='';
for($i=0; $i<$len; $i++){
    $servers.=implode(":",$config['servers'][$i]);
    if($i<$len-1){
        $servers.=",";
    }
}
?>

<form method="post">
    <fieldset>
        <legend>Config</legend>
        <label>Host:Port</label><input type="text" size="40" value="<?php echo $servers  ?>" name="txtHost"><br>
        <label>Limit</label><input type="text" size="40" value="<?php echo $config['limit'] ?>" name="txtLimit"><br>
        <label>&nbsp;</label><input type="submit" value="Save"/>
    </fieldset>
</form>