<?php

/* make sure no-one can run anything here if they didn't arrive through 'proper channels' */
if(!defined("COMPACTCMS_CODE")) { die('Illegal entry point!'); } /*MARKER*/

/* ************************************************************
Copyright (C) 2008 - 2010 by Xander Groesbeek (CompactCMS.nl)
Revision:   CompactCMS - v 1.4.2
	
This external class is part of CompactCMS

Copyright (C) 2005 - 2009 for STP Stefan Reich / Tobi Schulz
Project: http://www.script.gr/sc/scripts/STP/

Simple Template Parser is free software; you can redistribute it 
and/or modify it under the terms of the GNU General Public License 
as published by the Free Software Foundation; either version 2 of
the License, or (at your option) any later version.

STP is distributed in the hope that it will be useful, but
WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
General Public License for more details.

You should have received a copy of the GNU General Public License
along with CompactCMS. If not, see <http://www.gnu.org/licenses/>.
************************************************************ */

class ccmsParser {
  # Do NOT call variables from the outside ! (Use the public methods instead.)
  protected $params = array();
  protected $paramObject;
  protected $template;
  protected $output;
  protected $includePath;

  ########################################################
  ################## Internal Functions ##################
  ########################################################
  
  ## checkCondition()
  ## Function to check for conditions in IF Tags
  ## Possible conditions:
  #
  #  gt = greater (Numbers only)
  #  ge = greater or equal (Numbers only)
  #  lt = lower (Numbers only)
  #  le = lower or equal (Numbers only)
  #  eq = equal (Number or string)
  #  ne = not equal (Number or string)
  #  lk = exists in string (String only, functions like the 
  #       SQL LIKE '%string%'
  #
  # $value = value to check condition against
  #
  
  protected function checkCondition($value, $condition) {
    preg_match("/([^ ]+) +([^ ].*)/",$condition,$cond);
    $chh = $cond[1];
    $wert = $cond[2];
    $wert = preg_replace("( ['|\"] )","",$wert);
    if($chh == "gt") return ($value >  $wert);
    if($chh == "ge") return ($value >= $wert);
    if($chh == "lt") return ($value <  $wert);
    if($chh == "le") return ($value <= $wert);
    if($chh == "eq") return ($value == $wert);
    if($chh == "ne") return ($value != $wert);
    if($chh == "lk") return preg_match($wert,$value);
    return $wert;
  }

  ## colorSet(string $colorstring)
  ## Enables changing Colors f.e. in table rows
  ## initiating an array with the color values
  ## as given by the template in form
  ## {%ATTR COLOR1,COLOR2,COLOR3...%} 
  ## 
  #
  protected function colorSet($colorstring)  {
    $colorstring = preg_replace("( + )","",$colorstring);
    $this->colors = explode(',', $colorstring);
    $this->colorindex = 0;
    }
  
  ## colorChange()
  ## prints out the current value of array  $this->colors 
  ## Steps to next index or 0 if end is reached
  protected function colorChange()  {
    $currentColor = $this->colors[$this->colorindex];
    $this->colorindex = ($this->colorindex == (count($this->colors) - 1)) ? 0 : $this->colorindex + 1;
    return $currentColor;
    }
    
  # Splits the template $str into parts saves it to $this->template.
  # Afterwards each element contains either
  # - Only text
  # - an HTML comment
  # - exactly one parser tag
  protected function splitTemplate($str) {
    $sicherheitscounter = 0;
    $this->template = array();
    while ($str != '') {
      $result = preg_match('/^(.*?)({%.*?%}|<!--.*?-->|\n?$)/s', $str, $matches);
      $str = substr($str, strlen($matches[0]));
      if (strlen($matches[1])) $this->template[] = $matches[1];
      if (preg_match('/{%INCLUDE (.*)%}/', $matches[2], $matches2)) {
        $str = $this->loadInclude($matches2[1]).$str;
      } 
      elseif (preg_match('/{%ATTR (.*)%}/', $matches[2], $matches2))  {
        $this->colorSet($matches2[1]);
      }
      else {
        if (strlen($matches[2])) $this->template[] = $matches[2];
      }
      if (++$sicherheitscounter >= 2000) {
        print_r($matches);
        die("Parser stuck: '".$str."' $result ".strlen($str));
      }
    }
  }
  
  # loads an Include file and returns its contents
  protected function loadInclude($name) {
    $path = $this->includePath.$name;
    return file_get_contents($path);
  }

  # calls a variable or constant
  #
  # supports nested references to, for example, fetch values from a multidimensional variable array,
  # by delimiting the subsequent indices with a ':' colon like
  #
  # {%lang:backend:gethelp%}
  protected function getvar(&$vars, $var) {
    if ($var == "ATTR")
      return $this->colorChange();
    elseif (array_key_exists($var, $vars))
      return $vars[$var];
    elseif ($this->paramObject)
      return $this->paramObject->getVar($var);
    elseif (preg_match('/^G_/', $var) && defined($var))
      return constant($var);
    else
	{
	  $v = explode(':', $var, 2);
	  if (is_array($v) && count($v) == 2 && array_key_exists($v[0], $vars))
	  {
		return $this->getvar($vars[$v[0]], $v[1]);
	  }
      return '';
	}
  }
  
  protected function findEndOfIF($j, $to, $var, $tag) {
    $nest = 1;
    while ($j < $to) {
      if (preg_match("|^{%IF |", $this->template[$j])) {
        ++$nest;
        #echo "nest+ $nest: ".$this->template[$j]."<br>";
      } elseif (preg_match("|^{%/IF |", $this->template[$j])) {
        --$nest;
        #echo "nest- $nest: ".$this->template[$j]."<br>";
      }
      if ($nest <= 0) break;
      ++$j;
    }
    #while ($j < $to && !preg_match("|{%/IF !?$var%}|", $this->template[$j])) ++$j;
    
    if ($j >= $to) {
      echo "<br>WARNING: $tag not closed<br>"; 
    }
    
    return $j;
  }
  
  # works its way through the entries $this->template[$from] until $this->template[$to-1]
  # using the parameters $vars and appends the result to $this->output
  # $enable: Output mode: 0=disabled; 1=active; -1=disabled, to be enabled with ELSE 
  protected function process($from, $to, $vars, $enable = 1) {
    for ($i = $from; $i < $to; $i++) {
      $p = $this->template[$i];
      if ($enable != 1) {
        # only look for ELSE and nested IFs
        if ($p == "{%ELSE%}") {
          $enable = -$enable;
        } elseif (preg_match('/^{%IF (!?)(.*)?%}/', $p, $matches)) {
          $var = $matches[2];
          if (preg_match('/(\S*)\s+\(?(.*?)\)?$/', $var, $matches))
            $var = $matches[1];
          
          ++$i;
          $j = $this->findEndOfIF($i, $to, $var, $p);
          
          # call process() recursively but don't output anything
          $this->process($i, $j, $vars, 0);
            
          # Proceed after closing tag
          $i = $j;
        }        
      } elseif (preg_match("/^{%FOR (.*)%}/", $p, $matches)) {
        # find ends of FOR tags
        $var = $matches[1];
        $value = $this->getvar($vars, $var);
        $j = ++$i;
        while ($j < $to && $this->template[$j] != "{%/FOR $var%}") ++$j;
        if ($j >= $to) die("Lacking a closing tag for $p");
        
        # call process() recursively for each line
        if (is_array($value)) foreach ($value as $row) {
          if (!is_array($row)) $row = array('ROW' => $row);
          $this->process($i, $j, $row + $vars, 1);
        }
          
        # Proceed after closing tag
        $i = $j;
      } elseif (preg_match('/^{%IF (!?)(.*)?%}/', $p, $matches)) {
        # Split tag
        $neg = $matches[1];
        $var = $matches[2];
        $cond = '';
        if (preg_match('/(\S*)\s+\(?(.*?)\)?$/', $var, $matches)) {
          $var = $matches[1];
          $cond = $matches[2];
        }
        $value = $this->getvar($vars, $var);
        if ($neg) $value = !$value;
        if ($cond) $value = $this->checkCondition($value, $cond);
        
        ++$i;
        $j = $this->findEndOfIF($i, $to, $var, $p);
        
        # call process() recursively if variable is set
        $this->process($i, $j, $vars, $value ? 1 : -1);
          
        # Proceed after closing tag
        $i = $j;
      } elseif ($p == "{%ELSE%}") {
        $enable = -$enable;
      } elseif (preg_match("/^{%(.*)%}/", $p, $matches)) {
        # print variable value
        $this->append($this->getvar($vars, $matches[1]));
      } else { # Regular text
        $this->append($p);
      }
    }
  }

  ## Prints PHP code to the output page
  protected function CheckPHP($text) {
    eval('?>'.$text.'<?php '); 
    //echo $text;
  }
  
  protected function append($text) {
    if (is_array($this->output))
      $this->output[] = $text;
    else
      echo $text;
  }  

  ########################################################
  ################ PUBLIC FUNCTIONS ################
  ########################################################
  
  # constructor
  public function __construct() {
    global $ADM_SESS;
    if(isset($ADM_SESS['PERM_USERNAME'])) $this->params['ADMIN_USERNAME'] = $ADM_SESS['PERM_USERNAME'];
  }
  
  # Returns all set parameters
  public function getParams() {
    return $this->params;
  }
  
  # Returns ONE set parameter
  public function getParam($name) {
    return $this->params[$name];
  }

  # Sets one parameter
  public function setParam($name, $value) {
    $this->params[$name] = $value;
  }
  
  # sets several parameters at once
  # accepts an array or an object that supports the method getVar($name)
  public function setParams(&$params) {
    if (is_array($params))
	{
      $this->params = $params + $this->params;
	  return true;
	}
    elseif (is_object($params) && method_exists($params, 'getVar'))
	{
      $this->paramObject = $params;
	  return true;
	}
	return false;
  }
  
  # Deletes all parameters (no argument)
  # or a list of parameters from an array
  # (the parameters can be keys or values)
  public function clearParams($array = 'all') {
    if ($array == 'all') {
      $this->params = array();
      $this->paramObject = null;
    } else {
      foreach ($array as $k => $v) {
        unset($this->params[$k]);
        unset($this->params[$v]);
      }
    }
  }
  
  # Deletes one parameter
  public function clearParam($name) {
    unset($this->params[$name]);
  }
  
  # Assembles a template from a frame document and fragments
  public function assemble($frame, $frags) {
    $tmpl = file_get_contents($frame);
    
    foreach ($frags AS $fragname => $fragpath) {
      $cmd = "|<!--INSERT_$fragname-->|";
      if (preg_match($cmd, $tmpl)) {
  	    $tmpl = preg_replace($cmd, file_get_contents($fragpath), $tmpl);
      }
    }
    
    $this->splitTemplate($tmpl);
  }
  
  # Load a monolithic template
  public function setTemplate($tmpl, $leadin = '', $leadout = '') {
    if (!is_file($tmpl))
      die("Template not found: $tmpl");
    $idx = strrpos($tmpl, '/');
    if (!isset($this->includePath))
      $this->includePath = substr($tmpl, 0, $idx === false ? 0 : $idx+1);
    $this->splitTemplate($leadin . file_get_contents($tmpl) . $leadout);
  }
  
  # Sets the template content directly (not through a file)
  public function setTemplateText($text) {
    $this->splitTemplate($text);
  }
  
  # Parse template and return the contents
  public function parseAndReturn() {
    $this->output = array();
    $this->process(0, count($this->template), $this->params);
    return @join('', $this->output);
  }
  
  # Parse template and ECHO the result
  public function parseAndEcho() {
    $this->output = null;
    $this->process(0, count($this->template), $this->params);
  }
  
  # Parse template and ECHO the result;
  # Eval inline PHP code
  public function parseAndEchoPHP() {
    $this->CheckPHP($this->parseAndReturn());
  }
  
  # Parse template and save the result to the file $file
  public function parseAndSave($file) {
    $outf = fopen($file, "w");
    fputs($outf, $this->parseAndReturn());
    fclose($outf);
  }
  
  # Set include path (only 1 directory possible)
  # Call this before setTemplate!
  public function setIncludePath($path) {
    $this->includePath = $path;
    if (substr($path, -1, 1) != '/') $this->includePath .= '/';
  }
  
  ########################################################
  ################# STATIC FUNCTIONS #################
  ########################################################
  
  # Does everything at once: assemble, setParams and parseAndEcho
  public static function assembleAndEcho($frame, $frags, $params) {
    $parser = new ccmsParser;
    $parser->assemble($frame, $frags);
    $parser->setParams($params);
    $parser->parseAndEcho();
  }
  
  # Does everything at once: setTemplate, setParams and parseAndEcho
  public static function setTemplateAndEcho($tmpl, $params) {
    $parser = new ccmsParser;
    $parser->setTemplate($tmpl);
    $parser->setParams($params);
    $parser->parseAndEcho();
  }
  
} # End class ccmsParser

# a parser that uses [ ] instead of {% %}
class AlternativeParser extends ccmsParser {
  public function splitTemplate($str) {
    $str = preg_replace('/\[(.*?)\]/e', "'{%'.strtolower('\\1').'%}'", $str);
    parent::splitTemplate($str);
  }
}
  
?>