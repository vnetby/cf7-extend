<?php



class CFext_Common
{


  public function pre($str = '', $attach = false)
  {
    $class_name = 'php-code-display';
    if ($attach) $class_name .= ' php-code-fixed';
    echo '<pre class="' . $class_name . '">';
?>
    <style>
      .php-code-display {
        background-color: #4d535c !important;
        color: #fff !important;
        width: 100% !important;
        padding: 20px !important;
        overflow: auto !important;
      }

      .php-code-fixed {
        position: fixed !important;
        bottom: 0 !important;
        left: 0 !important;
        z-index: 10000 !important;
        height: 100vh !important;
        max-height: 400px !important;
      }
    </style>
<?php
    print_r($str);
    echo '</pre>';
  }







  public function get_from_object(&$obj, $key, $def = false, $callback = false, $callBackArgs = false)
  {
    if (!is_object($obj)) return $def;
    if (!property_exists($obj, $key)) return $def;
    if (!$obj->$key) return $def;
    if (!$callback) return $obj->$key;
    return call_user_func($callback, $obj->$key, $callBackArgs);
  }









  public function get_from_array(&$arr = false, $key, $def = false, $callback = false, $callBackArgs = false)
  {
    if (!is_array($arr)) return $def;
    if (!isset($arr[$key])) return $def;
    if (!$arr[$key]) return $def;
    if (!$callback) return $arr[$key];
    return call_user_func($callback, $arr[$key], $callBackArgs);
  }





  public function get_array_from_array(&$arr, $key, $def = false)
  {
    return $this->get_from_array($arr, $key, $def, function ($item) {
      return is_array($item) ? $item : false;
    });
  }






  public function get_from_post($key, $def = false)
  {
    return $this->get_from_array($_POST, $key, $def);
  }






  public function get_from_get($key, $def = false)
  {
    return $this->get_from_array($_GET, $key, $def);
  }






  public function get_from_request($key, $def = false)
  {
    return $this->get_from_array($_REQUEST, $key, $def);
  }






  public function get_from_server($key, $def = false)
  {
    return $this->get_from_array($_SERVER, $key, $def);
  }






  public function debug($var)
  {
    ob_start();
    print_r($var);
    $res = ob_get_clean();

    $file = CFEXT_PATH . '__DEBUG';
    file_put_contents($file, $res);
  }




  public function debug_post()
  {
    $this->debug($_POST);
  }




  public function debug_request()
  {
    $this->debug($_REQUEST);
  }



  public function debug_get()
  {
    $this->debug($_GET);
  }



  public function debug_server()
  {
    $this->debug($_SERVER);
  }
}
