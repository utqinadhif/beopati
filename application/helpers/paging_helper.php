<?php defined('BASEPATH') OR exit('No direct script access allowed');

if(!function_exists('pagings'))
{
  function pagings(&$callback,  $per_page = 3, $url = '')
  {
    $cigniter =& get_instance();

    $actual_link = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $actual_link = preg_replace('~[\?&]page=[0-9]+~', '', $actual_link);
    $actual_link = strpos($actual_link, '?') ? $actual_link . '&page=' : $actual_link . '?page=';
    $url         = !empty($url) ? $url : $actual_link;

    $get_page = @intval($cigniter->security->xss_clean($cigniter->input->get('page')));
    $offset   = empty($get_page) ? $get_page : $get_page * $per_page - $per_page;
    $page     = empty($get_page) ? 1 : $get_page;
    $tot_rec  = COUNT($callback); 
    $tot_page = ceil($tot_rec/$per_page);
    $output   = '';

    $callback = $cigniter->db->query($cigniter->db->last_query() . ' limit ' . $offset . ', ' . $per_page)->result_array();

    if($tot_page > 1)
    {
      $part = '<ul class="pagination no_radius"><li><a href="'.$url;
      $last = '">&laquo;</a></li> <li><a href="'.$url.(1).'">First</a></li> ';

      if(($page-1) <= 1) $output .= $part.(1).$last;
      else $output .= $part . ($page-1) . $last;

      if($page<=4 and $page != 0)
      {
        if($tot_page == 1 || $tot_page == 2 || $tot_page == 3 || $tot_page == 4 || $tot_page == 5)
        {
          $total_pages = $tot_page;
          $i           = 1;
        }else{
          $total_pages = 5;
          $i           = 1;
        }
      }else 
      if($page >= 4 && $page < ($tot_page - 2))
      {
        $total_pages = $page + 2;
        $i           = $page - 2;
      }else
      if($page >= ($tot_page - 2))
      {
        $total_pages = $tot_page;
        $i           = $tot_page - 4;
      }

      for ($i ; $i <= $total_pages ; $i ++)
      {
        $li ="<li";

        if($page == $i)
        {
          $ac = "class='active' ";
          if($page==$tot_page)
          {
            $title= "title=' ".$tot_rec.' of '.$tot_rec."&nbsp;total records' ";
          }else{
            $title= "title=' ".$per_page*$page.' of '.$tot_rec. "&nbsp;total records ' ";
          }
        }else{
          $ac="";
          $title="";
        }
        $output .= $li.' '.$ac."><a ".$title." href='".$url.$i ."'>" . $i."</a> </li> " ;
      }
      if($page + 1 > $tot_page) $pp = $page;
      else $pp = $page + 1;
      $output .= '<li><a href="'.$url.$tot_page.'">Last</a></li> <li><a href="'.$url.$pp.'">&raquo;</a></li></ul>';
    }
    return '<div class="text-center">' . $output . '</div>';
  }
}