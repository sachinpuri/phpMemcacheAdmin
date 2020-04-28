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
class MemcacheClient {

    private $con = null;
    private $host = null;
    private $port = null;
    public $servers=null;
    public $debug=false;

    function __construct($servers) {        
        $this->servers=$servers;
    }

    function connect($host, $port) {
        $this->con = @fsockopen($host, $port, $errno, $fsockerr, 1.0);
        if (!is_resource($this->con)) {
            $this->con=false;
            return false;
        } else {
            return true;
        }
    }

    function add($key, $value, $expiry) {
        if (!is_scalar($value)) {
            $value = serialize($value);
            $flag = 1;
        } else {
            $flag = 0;
        }
        $len = strlen($value);
        $out = "set $key $flag $expiry $len\r\n$value";
        return $this->execute($out);
    }

    function getHash($key) {
        return sprintf("%u", crc32($key));
    }
    
    function getServer($hash){
        return $hash%count($this->servers);
    }
    
    function connectToServerNo($serverNo){
        return $this->connect($this->servers[$serverNo]['host'], $this->servers[$serverNo]['port']);
    }

    function get($key) {
        $hash=$this->getHash($key);
        if(!$this->connectToServerNo($this->getServer($hash))){
            return "NO_RESULT_FOUND";
        }
        $value = $this->execute("get $key");
        $lines = explode("\r\n", $value);
        #pr($lines);
        $result = array();
        $keys = explode(' ', $key);
        $ctr = 0;
        if ($lines[0] == 'END') {
            return 'NO_RESULT_FOUND';
        } else {
            for ($i = 0; $i < count($lines); $i++) {
                if (substr($lines[$i], 0, 5) == 'VALUE') {
                    list($meta, $key, $flag, $length) = explode(' ', $lines[$i]);
                    $i++;
                    $result[$keys[$ctr]]['size'] = $length . ' bytes';
                    if ($flag == 1) {
                        $result[$keys[$ctr]]['value'] = unserialize($lines[$i]);
                    } elseif ($flag == 3) {
                        $result[$keys[$ctr]]['value'] = unserialize(gzuncompress($lines[$i]));
                    } else {
                        $result[$keys[$ctr]]['value'] = $lines[$i];
                    }
                    $ctr++;
                }
            }
        }

        return $result;
        die;
    }

    function format($data) {
        $lines = explode("\r\n", $data);

        foreach ($lines as $key => $line) {
            $data = explode(' ', $line, 3);
            if (count($data) == 3) {
                $formatted[$data[0]][$data[1]] = $data[2];
            }
        }

        return $formatted;
    }

    function stats() {
        return $this->execute('stats');
    }

    function statsi() {
        return $this->execute('stats items');
    }

    function statss() {
        return $this->execute('stats slabs');
    }

    function dump($id, $limit) {
        return $this->execute("stats cachedump $id $limit");
    }

    function delete($key) {
        return $this->execute("delete $key");
    }

    function flush() {
        return $this->execute("flush_all");
    }

    function execute($command) {
       if($this->con==false){
        $this->connectToServerNo(0);
        //    $this->log($command);
        //    echo "Not connected to server";
        //    die;
       }
        fwrite($this->con, $command . "\r\n");
        $data = $this->read();
        return $data;
    }

    function read() {
        $result = '';

        while ($this->con && !feof($this->con)) {
            $result.=fgets($this->con, 128);
            if (strpos($result, "END\r\n") !== false) { // stat says end
                break;
            }
            if (strpos($result, "DELETED\r\n") !== false || strpos($result, "NOT_FOUND\r\n") !== false) { // delete says these
                break;
            }
            if (strpos($result, "OK\r\n") !== false) { // flush_all says ok
                break;
            }
            if (strpos($result, "STORED\r\n") !== false) {
                break;
            }
        }
        #$result=fread($this->con, 1000000);
        return $result;
    }

    function getSlabs() {
        $itemIds = array();
        foreach($this->servers as $server){
            if(!$this->connect($server['host'],$server['port'])){
                continue;
            }
            $items=$this->statsi();
            $lines = explode("\r\n", $items);
            foreach ($lines as $v) {
                if ($v == "END") {//Break the loop if we reached end.
                    break;
                }
                list($param1, $itemNoItemName, $value) = explode(' ', $v);
                #if (!isset($param1) || $param1 != 'STAT') {continue;}
                list(, $itemNo, $itemName) = explode(':', $itemNoItemName);
                $itemIds[$server['host']][$itemNo][$itemName] = $value;
            }
        }
        if($this->debug){
            $this->log($itemIds);
        }
        return $itemIds;
    }

    function getKeys($server, $slabId, $limit = 100) {        
        $this->connectToServerNo($server);
        $items = $this->dump($slabId, $limit);
        $lines = explode("\r\n", $items);
        $itemIds = array();
        foreach ($lines as $k => $v) {
            $items = explode(' ', $v);

            if (!isset($items[0]) || $items[0] != 'ITEM') {
                continue;
            }

            $itemIds[] = $items[1];
        }
        #$this->log($itemIds);
        return $itemIds;
    }

    function disconnect() {
        return $this->execute("quit\r\n");
    }
    
    function log($value){
        echo "<pre>";
        print_r($value);
        echo "</pre>";
    }

}

?>
