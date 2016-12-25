<?php
if(array_key_exists('locale', $_GET)){
  $_SERVER['SCRIPT_NAME'] = '/'.$_GET['locale'].$_SERVER['SCRIPT_NAME'];
}

