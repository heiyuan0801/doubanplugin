<?php
#调用方式  http://你的网站域名/db.php?id=35230876
#本文件只用于交流学习，请勿作违法违规

@header("Access-Control-Allow-Origin: https://soju.ee");
@header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
@header("Access-Control-Allow-Headers: Content-Type, Authorization");
error_reporting(0);
@header("Content-type:text/json;charset=utf8");
define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));
define('FCPATH', str_replace("\\", "/", str_replace(SELF, '', __FILE__)));
if($_GET['id']){
$id=$_GET['id']; //豆瓣ID
$callback=$_GET['callback'];
if(stristr($id,".com")==true){
$id=str_substr('subject/','/', $id);
}else{
$id=$id;
}
$file = FCPATH.'/douban/'.$id.'.txt';
if(file_exists($file)){
$vurl = file_get_contents($file);
}else{
$data = geturl('https://movie.douban.com/subject/'.$id.'/');
$data=str_replace(" / ",",",$data); //别名
$txt3 = str_substr("首播:</span> ","<br/>", $data);
if($txt3==''){
$vod_pic = str_substr('"image": "','",', $data);
$vod_score=str_substr('<strong class="ll rating_num" property="v:average">','</strong>', $data); //评分
$vod_reurl=str_substr('data-url="','"', $data); //豆瓣地址
$a_name=str_substr('<i class="">','</i>', $data);
if(baohan($a_name,'剧情简介')=='1'){
$d_name=str_substr('<div>','的剧情简介', '<div>'.$a_name);
}elseif(baohan($a_name,'的分集短评')=='1'){
$d_name=str_substr('<div>','的分集', '<div>'.$a_name);
}elseif(baohan($a_name,'的演职员')=='1'){
$d_name=str_substr('<div>','的演职员', '<div>'.$a_name);
}elseif(baohan($a_name,'的图片')=='1'){
$d_name=str_substr('<div>','的图片', '<div>'.$a_name);
}elseif(baohan($a_name,'的短评')=='1'){
$d_name=str_substr('<div>','的短评', '<div>'.$a_name);
}elseif(baohan($a_name,'的影评')=='1'){
$d_name=str_substr('<div>','的影评', '<div>'.$a_name);
}
$a_name=str_substr('<span property="v:itemreviewed">','</span>', $data);
$vod_year=str_substr('<span class="year">(',')</span>', $data); //年代
$txt1 = str_substr("导演</span>: <span class='attrs'>","</span></span><br/>", $data);
$vod_director=preg_replace("/<a[^>]*>(.*)<\/a>/isU",'${1}',$txt1); //导演
$txt = str_substr("主演</span>: <span class='attrs'>","</span></span><br/>", $data);
$d_starring=preg_replace("/<a[^>]*>(.*)<\/a>/isU",'${1}',$txt);
$vod_actor=str_replace("'",',',$d_starring); //主演
$txt5 = str_substr("编剧</span>: <span class='attrs'>","</span></span><br/>", $data);
$vod_writer=preg_replace("/<a[^>]*>(.*)<\/a>/isU",'${1}',$txt5); //编辑
$txt2 = str_substr("类型:</span> ","<br/>", $data);
$vod_class=preg_replace("/<span[^>]*>(.*)<\/span>/isU",'${1}',$txt2); //分类
$vod_area = str_substr("制片国家/地区:</span> ","<br/>", $data); //地区
$vod_lang = str_substr("语言:</span> ","<br/>", $data); //语言
$d_subname=str_substr("又名:</span> ","<br/>", $data);
$vod_sub=str_replace("'","’",$d_subname); //别名
$txt3 = str_substr("上映日期:</span> ","<br/>", $data);
$vod_pubdate=preg_replace("/<span[^>]*>(.*)<\/span>/isU",'${1}',$txt3); //上映日期
$vod_duration=str_substr('片长:</span> <span property="v:runtime" content="','">', $data); //片长
$d_content=str_substr('<span property="v:summary" class="">','</span>', $data);
if($d_content==''){
$d_content=str_substr('<span class="all hidden">','</span>', $data);
}
$vod_content=str_replace("'","’",$d_content); //简介
$vod_total=''; //集数
if(strstr($vod_area,"中国")==true){
    $vod_remarks='高清国语';
}elseif(strstr($vod_area,"台湾")==true){
    $vod_remarks='高清国语';
}else{
    $vod_remarks='高清中字';
}
}else{
$vod_pic = str_substr('"image": "','",', $data);
$vod_score=str_substr('<strong class="ll rating_num" property="v:average">','</strong>', $data); //评分
$vod_reurl=str_substr('data-url="','"', $data); //豆瓣地址
$a_name=str_substr('<i class="">','</i>', $data);
if(baohan($a_name,'剧情简介')=='1'){
$d_name=str_substr('<div>','的剧情简介', '<div>'.$a_name);
}elseif(baohan($a_name,'的分集短评')=='1'){
$d_name=str_substr('<div>','的分集', '<div>'.$a_name);
}elseif(baohan($a_name,'的演职员')=='1'){
$d_name=str_substr('<div>','的演职员', '<div>'.$a_name);
}elseif(baohan($a_name,'的图片')=='1'){
$d_name=str_substr('<div>','的图片', '<div>'.$a_name);
}elseif(baohan($a_name,'的短评')=='1'){
$d_name=str_substr('<div>','的短评', '<div>'.$a_name);
}elseif(baohan($a_name,'的影评')=='1'){
$d_name=str_substr('<div>','的影评', '<div>'.$a_name);
}
$a_name=str_substr('<span property="v:itemreviewed">','</span>', $data);
$vod_year=str_substr('<span class="year">(',')</span>', $data); //年代
$txt1 = str_substr("导演</span>: <span class='attrs'>","</span></span><br/>", $data);
$vod_director=preg_replace("/<a[^>]*>(.*)<\/a>/isU",'${1}',$txt1); //导演
$txt = str_substr("主演</span>: <span class='attrs'>","</span></span><br/>", $data);
$vod_actor=preg_replace("/<a[^>]*>(.*)<\/a>/isU",'${1}',$txt); //主演
$txt5 = str_substr("编剧</span>: <span class='attrs'>","</span></span><br/>", $data);
$vod_writer=preg_replace("/<a[^>]*>(.*)<\/a>/isU",'${1}',$txt5); //编辑
$txt2 = str_substr("类型:</span> ","<br/>", $data);
$vod_class=preg_replace("/<span[^>]*>(.*)<\/span>/isU",'${1}',$txt2); //分类
$vod_area = str_substr("制片国家/地区:</span> ","<br/>", $data); //国家
$vod_lang = str_substr("语言:</span> ","<br/>", $data); //语言
$d_subname=str_substr("又名:</span> ","<br/>", $data);
$vod_sub=str_replace("'","’",$d_subname); //别名
$txt3 = str_substr("首播:</span> ","<br/>", $data);
$vod_pubdate=preg_replace("/<span[^>]*>(.*)<\/span>/isU",'${1}',$txt3); //上映日期
$vod_duration=str_substr('单集片长:</span> ','<br/>', $data); //片长
$vod_duration=str_replace("分钟","",$vod_duration);
$d_content=str_substr('<span property="v:summary" class="">','</span>', $data);
if($d_content==''){
$d_content=str_substr('<span class="all hidden">','</span>', $data);
}
$vod_content=str_replace("'","’",$d_content); //简介
$vod_total=str_substr('集数:</span> ','<br/>', $data); //集数
$vod_remarks='总集数'.$vod_total;
}
if($vod_sub==''){
$vod_sub=$a_name.$vod_year;
}
if($vod_year==''){
$vod_year='内详';
}
if($vod_score==''){
$vod_score=rand(1,4).'.'.rand(0,9);
}
if($vod_director==''){
$vod_director='内详';
}
if($vod_actor==''){
$vod_actor='内详';
}
if($vod_content==''){
$vod_content='内详';
}
if($d_name==''){
$vurl='({"code":102,"auth":"内部API数据接口！","msg":"请输入有效 id"});';
}else{
$info['vod_name'] = $d_name;
$info['vod_sub'] = $vod_sub.','.$a_name;
$info['vod_pic'] = $vod_pic;
$info['vod_year'] = $vod_year;
$info['vod_lang'] = $vod_lang;
$info['vod_area'] = $vod_area;
$info['vod_remarks'] = $vod_remarks;
$info['vod_total'] = $vod_total;
$info['vod_serial'] = '';
$info['vod_isend'] = 1;
$info['vod_class'] = $vod_class;
$info['vod_tag'] = '';
$info['vod_actor'] = $vod_actor;
$info['vod_director'] = $vod_director;
$info['vod_pubdate'] = $vod_pubdate;
$info['vod_writer'] = $vod_writer;
$info['vod_score'] = $vod_score;
$info['vod_score_num'] = rand(100,1000);
$info['vod_score_all'] = rand(200,500);
$info['vod_douban_score'] = $vod_score;
$info['vod_duration'] = strip_tags($vod_duration);
$info['vod_reurl'] = $vod_reurl;
$info['vod_author'] = $vod_author;
$info['vod_content'] = cutstr_html($vod_content);
$info['vod_douban_id'] = $id;
$vurl='({"code":1,"auth":"爱云：200702731","msg":"解析成功！","data":'.json_encode($info, 456).'});';
}
file_put_contents($file,$vurl);
}
echo $callback.$vurl;
}else{
echo $callback.'({"code":102,"auth":"请输入有效 id","msg":"解析失败！"});';
}
function baohan($str,$needle){
$tmparray = explode($needle,$str);
if(count($tmparray)>1){
    $yyy='1';
}else{
    $yyy='2';
}
return $yyy;
}
function tugeturl($url){
     if(function_exists('curl_init')){
         $ch = curl_init();
         $timeout = 30;
         curl_setopt ($ch,CURLOPT_URL,$url);
         curl_setopt ($ch,CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查  
         curl_setopt ($ch,CURLOPT_SSL_VERIFYHOST, true);
         curl_setopt ($ch,CURLOPT_RETURNTRANSFER,1);
         curl_setopt ($ch,CURLOPT_REFERER, $url);  
         curl_setopt ($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
         $header = [
         "X-FORWARDED-FOR:".long2ip(mt_rand(1884815360, 1884890111)),
         "CLIENT-IP:".long2ip(mt_rand(1884815360, 1884890111)),
         "X-Real-IP:".long2ip(mt_rand(1884815360, 1884890111)),
         "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.198 Safari/537.36",
         ];
         curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
         $handles = curl_exec($ch);
         curl_close($ch);
      }else{
         $handles = @file_get_contents($url);
      }
  return $handles;
}
function geturl($url){
     if(function_exists('curl_init')){
         $ch = curl_init();
         $timeout = 30;
         curl_setopt ($ch,CURLOPT_URL,$url);
         curl_setopt ($ch,CURLOPT_RETURNTRANSFER,1);
         curl_setopt ($ch,CURLOPT_REFERER, $url);  
         curl_setopt ($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
         $header = [
         "X-FORWARDED-FOR:".long2ip(mt_rand(1884815360, 1884890111)),
         "CLIENT-IP:".long2ip(mt_rand(1884815360, 1884890111)),
         "X-Real-IP:".long2ip(mt_rand(1884815360, 1884890111)),
         "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.198 Safari/537.36",
         ];
         curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
         $handles = curl_exec($ch);
         curl_close($ch);
      }else{
         $handles = @file_get_contents($url);
      }
  return $handles;
}
function getvrl($url){
    $vurl = str_replace(array('"',"'"),array("'","‘"),$url);
    return $vurl;
}
function cutstr_html($string){
    $string = strip_tags($string);
    $string = trim($string);
    $string = str_replace("\t","",$string);
    $string = str_replace("\r\n","",$string);
    $string = str_replace("\r","",$string);
    $string = str_replace("\n","",$string);
    $string = str_replace(" ","",$string);
    $string = str_replace("　","",$string);
    return trim($string);
}
function str_substr($start, $end, $str){
    $temp = explode($start, $str, 2);
    $content = explode($end, $temp[1], 2);
    return $content[0];
}

?>
