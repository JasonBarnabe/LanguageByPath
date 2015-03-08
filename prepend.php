<?php
if(array_key_exists('locale', $_GET)){
  $_SERVER['PHP_SELF'] = '/'.$_GET['locale'].$_SERVER['PHP_SELF'];
}

