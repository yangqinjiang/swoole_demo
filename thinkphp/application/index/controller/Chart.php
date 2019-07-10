<?php
namespace app\index\controller;
use app\common\lib\Util;
class Chart
{
    public function index()
    {
        // 登录
        if(empty($_POST['game_id'])) {
            return Util::show(config('code.error'), 'error');
        }
        if(empty($_POST['content'])) {
            return Util::show(config('code.error'), 'error');
        }

        $data = [
            'user' => "用户".rand(0, 2000),
            'content' => $this->remove_xss($_POST['content']),
        ];
        //  todo
        foreach($_POST['http_server']->ports[1]->connections as $fd) {
            $_POST['http_server']->push($fd, json_encode($data));
        }

        return Util::show(config('code.success'), 'ok', $data);
    }

    //删除xss的特别字符
private function remove_xss($val) {
    // remove all non-printable characters. CR(0a) and LF(0b) and TAB(9) are allowed
    // this prevents some character re-spacing such as <java\0script>
    // note that you have to handle splits with \n, \r, and \t later since they *are* allowed in some inputs
    $val = preg_replace('/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/', '', $val);
  
    // straight replacements, the user should never need these since they're normal characters
    // this prevents like <IMG SRC=@avascript:alert('XSS')>
    $search = 'abcdefghijklmnopqrstuvwxyz';
    $search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $search .= '1234567890!@#$%^&*()';
    $search .= '~`";:?+/={}[]-_|\'\\';
    for ($i = 0; $i < strlen($search); $i++) {
       // ;? matches the ;, which is optional
       // 0{0,7} matches any padded zeros, which are optional and go up to 8 chars
  
       // @ @ search for the hex values
       $val = preg_replace('/(&#[xX]0{0,8}'.dechex(ord($search[$i])).';?)/i', $search[$i], $val); // with a ;
       // @ @ 0{0,7} matches '0' zero to seven times
       $val = preg_replace('/(&#0{0,8}'.ord($search[$i]).';?)/', $search[$i], $val); // with a ;
    }
  
    // now the only remaining whitespace attacks are \t, \n, and \r
    $ra1 = array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'style', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base');
    $ra2 = array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
    $ra = array_merge($ra1, $ra2);
  
    $found = true; // keep replacing as long as the previous round replaced something
    while ($found == true) {
       $val_before = $val;
       for ($i = 0; $i < sizeof($ra); $i++) {
          $pattern = '/';
          for ($j = 0; $j < strlen($ra[$i]); $j++) {
             if ($j > 0) {
                $pattern .= '(';
                $pattern .= '(&#[xX]0{0,8}([9ab]);)';
                $pattern .= '|';
                $pattern .= '|(&#0{0,8}([9|10|13]);)';
                $pattern .= ')*';
             }
             $pattern .= $ra[$i][$j];
          }
          $pattern .= '/i';
          $replacement = substr($ra[$i], 0, 2).'<x>'.substr($ra[$i], 2); // add in <> to nerf the tag
          $val = preg_replace($pattern, $replacement, $val); // filter out the hex tags
          if ($val_before == $val) {
             // no replacements were made, so exit the loop
             $found = false;
          }
       }
    }
    return $val;
 } 


}
