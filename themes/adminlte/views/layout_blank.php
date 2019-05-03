<!DOCTYPE html>
<html lang="en">
  <head>
    <?php
    $meta = array(
      array('name' => 'utf-8', 'type' => 'charset'),
      array('name' => 'X-UA-Compatible', 'content' => 'IE=edge', 'type' => 'http-equiv'),
      array('name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no'),
      array('name' => 'description', 'content' => 'SIM RSI Pati'),
      array('name' => 'robots', 'content' => 'no-cache'),
      array('name' => 'keywords', 'content' => 'ahmad nadhif hakim'),
      array('name' => 'Content-type', 'content' => 'text/html; charset=utf-8', 'type' => 'equiv'),
      array('name' => 'expires', 'content' => '0', 'type' => 'equiv'),
      array('name' => 'revisit-after', 'content' => '2 Days'),
      array('name' => 'language', 'content' => 'en-us'),
      array('name' => 'author', 'content' => 'ahmad nadhif hakim'),
      array('name' => 'developer', 'content' => 'ahmad nadhif hakim'),
      array('name' => 'email', 'content' => 'nadhif.ahm@gmail.com'),
      array('name' => 'tel', 'content' => '085311330126')
      );
    echo meta($meta);
    echo link_tag(base_url('themes/adminlte/css/style.css'));
    echo link_tag(base_url('image/icon.ico'), 'shortcut icon', 'image/ico');
    ?>
    <script type="text/javascript">
      window.base_url = '<?php echo base_url();?>';
      eval(function(p,a,c,k,e,r){e=function(c){return c.toString(a)};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('0 1(a){"3"==4 2.5?2.6(0(){1(a)},7):$(8).9(0(b){a(b)})}',12,12,'function|_RUN|window|undefined|typeof|jQuery|setTimeout|100|document|ready||'.split('|'),0,{}))
    </script>
    <title>SIMRSI</title>
  </head>
  <body class="hold-transition login-page">
    <?php echo $template['body'];?>
    <script src="<?php echo base_url('home/js');?>"></script>
    <?php echo show_js();?>
  </body>
</html>