<?php defined('BASEPATH') OR exit('No direct script access allowed');

if(!function_exists('addslashesr'))
{
  function addslashesr($var)
  {
    $var = is_array($var) ? array_map(__FUNCTION__, $var) : addslashes($var);
    return $var;
  }
}

if(!function_exists('stripslashesr'))
{
  function stripslashesr($var)
  {
    $var = is_array($var) ? array_map(__FUNCTION__, $var) : stripslashes($var);
    return $var;
  }
}

if(!function_exists('urldecoder'))
{
  function urldecoder($var)
  {
    $var = is_array($var) ? array_map(__FUNCTION__, $var) : urldecode($var);
    return $var;
  }
}

if(!function_exists('urlencoder'))
{
  function urlencoder($var)
  {
    $var = is_array($var) ? array_map(__FUNCTION__, $var) : urlencode($var);
    return $var;
  }
}

if(!function_exists('date_formater'))
{
  function date_formater($date)
  {
    $def  = '';
    $date = strtotime($date);
    if($date > 0)
    {
      $def = date('M jS, Y', $date);
    }
    return $def;
  }
}
if(!function_exists('alert'))
{
  function alert($text, $type = 'info')
  {
    $out = '';
    if(!empty($text))
    {
      $out = '<div class="no_radius alert alert-'.$type.'"><strong><i class="fa fa-exclamation-triangle"></i></strong> '.$text.'</div>';
    }
    return $out;
  }
}
if(!function_exists('money'))
{
  function money($price = 0)
  {
    $output = number_format(floatval($price), 2, '.', ',');
    $output = preg_replace('~0+$~', '',  $output);
    $output = preg_replace('~\.$~', '',  $output);
    return $output;
  }
}

if(!function_exists('date_ind'))
{
  function date_ind($date)
  {
    $datetime = new DateTime($date);
    list($date, $month, $years) = explode(' ', $datetime->format('j m Y'));

    $cap = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
      
    return $date . ' ' . $cap[$month - 1] . ' ' . $years;
  }
}

if(!function_exists('clock_ind'))
{
  function clock_ind($clock)
  {
    $datetime = new DateTime($clock);
    return $datetime->format('H:i:s');
  }
}

if(!function_exists('option'))
{
  function option($array, $select = '')
  {
    $output = '';

    foreach ($array as $key => $value)
    {
      if($key == $select) $selected = ' selected="selected"';
      else $selected = '';
      $output .= '<option value="'.$key.'"'.$selected.'>'.$value.'</option>';
    }

    return $output;    
  }
}
if(!function_exists('is_upload'))
{
  function is_upload($field)
  {
    return !empty($_FILES[$field]['name']);
  }
}
if(!function_exists('j_decode'))
{
  function j_decode($string)
  {
    return urldecoder(json_decode($string, 1));
  }
}
if(!function_exists('j_encode'))
{
  function j_encode($array)
  {
    return json_encode(urlencoder($array));
  }
}
if(!function_exists('is_url'))
{
  function is_url($url)
  {
    return filter_var($url, FILTER_VALIDATE_URL);
  }
}
if(!function_exists('actual_link'))
{
  function actual_link()
  {
    $actual_link = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    return base64_encode(urlencoder($actual_link));
  }
}
if(!function_exists('share'))
{
  function share()
  {
    add_js('http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4edbbcb7772a1026');
    ob_start();
    ?>
    <div class="addthis_toolbox addthis_default_style">
      <a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
      <a class="addthis_button_tweet"></a>
      <a class="addthis_button_google_plusone" g:plusone:size="medium"></a>
      <a class="addthis_counter addthis_pill_style"></a>
    </div>
    <hr />
    <?php
    return ob_get_clean();
  } 
}

if(!function_exists('fpublish'))
{
  function fpublish($num)
  {
    return $num == 1 ? 'Yes' : 'No';
  }
}