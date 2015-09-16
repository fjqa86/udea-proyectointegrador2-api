<?php   
  $this->output->set_header('Content-Type: application/json; charset=utf-8');
  $this->output->set_header('Access-Control-Allow-Origin: *');
  echo json_encode($json);
?>