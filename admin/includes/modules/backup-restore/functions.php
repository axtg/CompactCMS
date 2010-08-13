<?php
/* ************************************************************
Copyright (C) 2008 - 2009 by Xander Groesbeek (CompactCMS.nl)
Revision:	CompactCMS - v 1.4.1
	
This file is part of CompactCMS.

CompactCMS is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

CompactCMS is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

A reference to the original author of CompactCMS and its copyright
should be clearly visible AT ALL TIMES for the user of the back-
end. You are NOT allowed to remove any references to the original
author, communicating the product to be your own, without written
permission of the original copyright owner.

You should have received a copy of the GNU General Public License
along with CompactCMS. If not, see <http://www.gnu.org/licenses/>.
	
> Contact me for any inquiries.
> E: Xander@CompactCMS.nl
> W: http://community.CompactCMS.nl/forum
************************************************************ */

/*
  MySQL database backup class, version 1.0.0
  Written by Rochak Chauhan
  Released under GNU Public license
*/

class createZip  {  

    public $compressedData = array();
    public $centralDirectory = array(); // central directory   
    public $endOfCentralDirectory = "\x50\x4b\x05\x06\x00\x00\x00\x00"; //end of Central directory record
    public $oldOffset = 0;

    /**
     * Function to create the directory where the file(s) will be unzipped
     *
     * @param $directoryName string
     *
     */
    
    public function addDirectory($directoryName) {
        $directoryName = str_replace("\\", "/", $directoryName);  

        $feedArrayRow = "\x50\x4b\x03\x04";
        $feedArrayRow .= "\x0a\x00";    
        $feedArrayRow .= "\x00\x00";    
        $feedArrayRow .= "\x00\x00";    
        $feedArrayRow .= "\x00\x00\x00\x00";

        $feedArrayRow .= pack("V",0);
        $feedArrayRow .= pack("V",0);
        $feedArrayRow .= pack("V",0);
        $feedArrayRow .= pack("v", strlen($directoryName) );
        $feedArrayRow .= pack("v", 0 );
        $feedArrayRow .= $directoryName;  

        $feedArrayRow .= pack("V",0);
        $feedArrayRow .= pack("V",0);
        $feedArrayRow .= pack("V",0);

        $this -> compressedData[] = $feedArrayRow;
        
        $newOffset = strlen(implode("", $this->compressedData));

        $addCentralRecord = "\x50\x4b\x01\x02";
        $addCentralRecord .="\x00\x00";    
        $addCentralRecord .="\x0a\x00";    
        $addCentralRecord .="\x00\x00";    
        $addCentralRecord .="\x00\x00";    
        $addCentralRecord .="\x00\x00\x00\x00";
        $addCentralRecord .= pack("V",0);
        $addCentralRecord .= pack("V",0);
        $addCentralRecord .= pack("V",0);
        $addCentralRecord .= pack("v", strlen($directoryName) );
        $addCentralRecord .= pack("v", 0 );
        $addCentralRecord .= pack("v", 0 );
        $addCentralRecord .= pack("v", 0 );
        $addCentralRecord .= pack("v", 0 );
        $ext = "\x00\x00\x10\x00";
        $ext = "\xff\xff\xff\xff";  
        $addCentralRecord .= pack("V", 16 );

        $addCentralRecord .= pack("V", $this -> oldOffset );
        $this -> oldOffset = $newOffset;

        $addCentralRecord .= $directoryName;  

        $this -> centralDirectory[] = $addCentralRecord;  
    }    
    
    /**
     * Function to add file(s) to the specified directory in the archive
     *
     * @param $directoryName string
     *
     */
    
    public function addFile($data, $directoryName)   {

        $directoryName = str_replace("\\", "/", $directoryName);  
    
        $feedArrayRow = "\x50\x4b\x03\x04";
        $feedArrayRow .= "\x14\x00";    
        $feedArrayRow .= "\x00\x00";    
        $feedArrayRow .= "\x08\x00";    
        $feedArrayRow .= "\x00\x00\x00\x00";

        $uncompressedLength = strlen($data);  
        $compression = crc32($data);  
        $gzCompressedData = gzcompress($data);  
        $gzCompressedData = substr( substr($gzCompressedData, 0, strlen($gzCompressedData) - 4), 2);
        $compressedLength = strlen($gzCompressedData);  
        $feedArrayRow .= pack("V",$compression);
        $feedArrayRow .= pack("V",$compressedLength);
        $feedArrayRow .= pack("V",$uncompressedLength);
        $feedArrayRow .= pack("v", strlen($directoryName) );
        $feedArrayRow .= pack("v", 0 );
        $feedArrayRow .= $directoryName;  

        $feedArrayRow .= $gzCompressedData;  

        $feedArrayRow .= pack("V",$compression);
        $feedArrayRow .= pack("V",$compressedLength);
        $feedArrayRow .= pack("V",$uncompressedLength);

        $this -> compressedData[] = $feedArrayRow;

        $newOffset = strlen(implode("", $this->compressedData));

        $addCentralRecord = "\x50\x4b\x01\x02";
        $addCentralRecord .="\x00\x00";    
        $addCentralRecord .="\x14\x00";    
        $addCentralRecord .="\x00\x00";    
        $addCentralRecord .="\x08\x00";    
        $addCentralRecord .="\x00\x00\x00\x00";
        $addCentralRecord .= pack("V",$compression);
        $addCentralRecord .= pack("V",$compressedLength);
        $addCentralRecord .= pack("V",$uncompressedLength);
        $addCentralRecord .= pack("v", strlen($directoryName) );
        $addCentralRecord .= pack("v", 0 );
        $addCentralRecord .= pack("v", 0 );
        $addCentralRecord .= pack("v", 0 );
        $addCentralRecord .= pack("v", 0 );
        $addCentralRecord .= pack("V", 32 );

        $addCentralRecord .= pack("V", $this -> oldOffset );
        $this -> oldOffset = $newOffset;

        $addCentralRecord .= $directoryName;  

        $this -> centralDirectory[] = $addCentralRecord;  
    }

    /**
     * Fucntion to return the zip file
     *
     * @return zipfile (archive)
     */

    public function getZippedfile() {

        $data = implode("", $this -> compressedData);  
        $controlDirectory = implode("", $this -> centralDirectory);  

        return   
            $data.  
            $controlDirectory.  
            $this -> endOfCentralDirectory.  
            pack("v", sizeof($this -> centralDirectory)).     
            pack("v", sizeof($this -> centralDirectory)).     
            pack("V", strlen($controlDirectory)).             
            pack("V", strlen($data)).                
            "\x00\x00";                             
    }


}

/*
  MySQL database backup class, version 1.0.0
  Written by Vagharshak Tozalakyan <vagh@armdex.com>
  Released under GNU Public license
*/

define('MSB_VERSION', '1.0.0');

define('MSB_NL', "\r\n");

define('MSB_STRING', 0);
define('MSB_DOWNLOAD', 1);
define('MSB_SAVE', 2);

class MySQL_Backup
{

  var $server = 'localhost';
  var $port = 3306;
  var $username = 'root';
  var $password = '';
  var $database = '';
  var $link_id = -1;
  var $connected = false;
  var $tables = array();
  var $drop_tables = true;
  var $struct_only = false;
  var $comments = true;
  var $backup_dir = '';
  var $fname_format = 'd_m_y__H_i_s';
  var $error = '';


  function Execute($task = MSB_STRING, $fname = '', $compress = false)
  {
    if (!($sql = $this->_Retrieve()))
    {
      return false;
    }
    if ($task == MSB_SAVE)
    {
      if (empty($fname))
      {
        $fname = $this->backup_dir;
        $fname .= date($this->fname_format);
        $fname .= ($compress ? '.sql.gz' : '.sql');
      }
      return $this->_SaveToFile($fname, $sql, $compress);
    }
    elseif ($task == MSB_DOWNLOAD)
    {
      if (empty($fname))
      {
        $fname = date($this->fname_format);
        $fname .= ($compress ? '.sql.gz' : '.sql');
      }
      return $this->_DownloadFile($fname, $sql, $compress);
    }
    else
    {
      return $sql;
    }
  }


  function _Connect()
  {
    $value = false;
    if (!$this->connected)
    {
      $host = $this->server . ':' . $this->port;
      $this->link_id = mysql_connect($host, $this->username, $this->password);
    }
    if ($this->link_id)
    {
      if (empty($this->database))
      {
        $value = true;
      }
      elseif ($this->link_id !== -1)
      {
        $value = mysql_select_db($this->database, $this->link_id);
      }
      else
      {
        $value = mysql_select_db($this->database);
      }
    }
    if (!$value)
    {
      $this->error = mysql_error();
    }
    return $value;
  }


  function _Query($sql)
  {
    if ($this->link_id !== -1)
    {
      $result = mysql_query($sql, $this->link_id);
    }
    else
    {
      $result = mysql_query($sql);
    }
    if (!$result)
    {
      $this->error = mysql_error();
    }
    return $result;
  }


  function _GetTables()
  {
    $value = array();
    if (!($result = $this->_Query('SHOW TABLES')))
    {
      return false;
    }
    while ($row = mysql_fetch_row($result))
    {
      if (empty($this->tables) || in_array($row[0], $this->tables))
      {
        $value[] = $row[0];
      }
    }
    if (!sizeof($value))
    {
      $this->error = 'No tables found in database.';
      return false;
    }
    return $value;
  }


  function _DumpTable($table)
  {
    $value = '';
    $this->_Query('LOCK TABLES ' . $table . ' WRITE');
    if ($this->comments)
    {
      $value .= '#' . MSB_NL;
      $value .= '# Table structure for table `' . $table . '`' . MSB_NL;
      $value .= '#' . MSB_NL . MSB_NL;
    }
    if ($this->drop_tables)
    {
      $value .= 'DROP TABLE IF EXISTS `' . $table . '`;' . MSB_NL;
    }
    if (!($result = $this->_Query('SHOW CREATE TABLE ' . $table)))
    {
      return false;
    }
    $row = mysql_fetch_assoc($result);
    $value .= str_replace("\n", MSB_NL, $row['Create Table']) . ';';
    $value .= MSB_NL . MSB_NL;
    if (!$this->struct_only)
    {
      if ($this->comments)
      {
        $value .= '#' . MSB_NL;
        $value .= '# Dumping data for table `' . $table . '`' . MSB_NL;
        $value .= '#' . MSB_NL . MSB_NL;
      }
            
      $value .= $this->_GetInserts($table);
    }
    $value .= MSB_NL . MSB_NL;
    $this->_Query('UNLOCK TABLES');
    return $value;
  }


  function _GetInserts($table)
  {
    $value = '';
    if (!($result = $this->_Query('SELECT * FROM ' . $table)))
    {
      return false;
    }
    while ($row = mysql_fetch_row($result))
    {
      $values = '';
      foreach ($row as $data)
      {
        $values .= '\'' . addslashes($data) . '\', ';
      }
      $values = substr($values, 0, -2);
      $value .= 'INSERT INTO ' . $table . ' VALUES (' . $values . ');' . MSB_NL;
    }
    return $value;
  }


  function _Retrieve()
  {
    $value = '';
    if (!$this->_Connect())
    {
      return false;
    }
    if ($this->comments)
    {
      $value .= '#' . MSB_NL;
      $value .= '# MySQL database dump' . MSB_NL;
      $value .= '# Created for CompactCMS (www.compactcms.nl)';
      $value .= '#' . MSB_NL;
      $value .= '# Host: ' . $this->server . MSB_NL;
      $value .= '# Generated: ' . date('M j, Y') . ' at ' . date('H:i') . MSB_NL;
      $value .= '# MySQL version: ' . mysql_get_server_info() . MSB_NL;
      $value .= '# PHP version: ' . phpversion() . MSB_NL;
      if (!empty($this->database))
      {
        $value .= '#' . MSB_NL;
        $value .= '# Database: `' . $this->database . '`' . MSB_NL;
      }
      $value .= '#' . MSB_NL . MSB_NL . MSB_NL;
    }
    if (!($tables = $this->_GetTables()))
    {
      return false;
    }
    foreach ($tables as $table)
    {
      if (!($table_dump = $this->_DumpTable($table)))
      {
        $this->error = mysql_error();
        return false;
      }
      $value .= $table_dump;
    }
    return $value;
  }


  function _SaveToFile($fname, $sql, $compress)
  {
    if ($compress)
    {
      if (!($zf = gzopen($fname, 'w9')))
      {
        $this->error = 'Can\'t create the output file.';
        return false;
      }
      gzwrite($zf, $sql);
      gzclose($zf);
    }
    else
    {
      if (!($f = fopen($fname, 'w')))
      {
        $this->error = 'Can\'t create the output file.';
        return false;
      }
      fwrite($f, $sql);
      fclose($f);
    }
    return true;
  }

} 

function directoryToArray($directory, $recursive) {
    $array_items = array();
    if ($handle = opendir($directory)) {
        while (false !== ($file = readdir($handle))) {
            if ($file != "." && $file != "..") {
                if (is_dir($directory. "/" . $file)) {
                    if($recursive) {
                        $array_items = array_merge($array_items, directoryToArray($directory. "/" . $file, $recursive));
                    }
                    $file = $directory . "/" . $file ."/";
                    $array_items[] = preg_replace("/\/\//si", "/", $file);
                } else {
                    $file = $directory . "/" . $file;
                    $array_items[] = preg_replace("/\/\//si", "/", $file);
                }
            }
        }
        closedir($handle);
    }
    return $array_items;
}

function pr($val)
{
    echo '<pre>';
    print_r($val);
    echo '</pre>';
}  
?> 