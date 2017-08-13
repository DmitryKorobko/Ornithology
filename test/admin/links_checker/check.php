<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Link Checker</title>
<style>
body
{
    font-family: Arial, Helvetica, sans-serif;
    font-size: 12px;
    padding: 10px;
    color: #555555;
}

a
{
    color: #000000;
}

.SectionTable
{
    border: 1px solid grey;
    width: 100%;
}

.SectionTable th
{
    background-color: #DDFFDD;
    text-align: left;
    border-bottom: 1px solid grey;
    padding: 2px;
}

.SectionTable td
{
    padding: 2px;
}

.RecheckSectionTable
{
    border: 1px solid grey;
    width: 100%;
}

.RecheckSectionTable th
{
    background-color: #FFFFDD;
    text-align: left;
    border-bottom: 1px solid grey;
    padding: 2px;
}

.RecheckSectionTable td
{
    padding: 2px;
}

.FinalCheckSectionTable
{
    border: 1px solid grey;
    width: 100%;
}

.FinalCheckSectionTable th
{
    background-color: #FFDDDD;
    text-align: left;
    border-bottom: 1px solid grey;
    padding: 2px;
}

.FinalCheckSectionTable td
{
    padding: 2px;
}

.Error
{
    color: #990000;
    font-weight: bold;
    text-align: right;
    padding: 2px;
}

.Success
{
    color: #009900;
    font-weight: bold;
    text-align: right;
    padding: 2px;
}
</style>
<?
set_time_limit(0);
$bTest = false; //isset($_GET['test']);
$verbose = '';//($bDebug) ? '?verbose' : '';
?>
<script type="text/javascript">
    function SCR(anchor) {
        window.scrollTo(0,999999);
    }
</script>
<?php
//print_r($_SERVER);
error_reporting(E_ALL);
$dirs = explode(',', $_GET['directories']);
$directories = array();
foreach($dirs as $dir){
    $directories[$dir] = array('subdirs'   => true,
                                  'filetypes' => array('html'));
}


function flush_buffers(){
    @ob_end_flush();
    @ob_flush();
    @flush();
    @ob_start();
}

function is_type($entry, $types=array())
{
    if (count($types) < 1) return true;
    foreach($types as $type)
    {
        if (substr(strtolower($entry), -strlen($type)) == strtolower($type)) return true;
    }
    return false;
}

function read_dir($dir, $subdirs=true, $filetypes=array()) {
   $array = array();
   $d = dir($dir);
   while (false !== ($entry = $d->read())) {
       if($entry!='.' && $entry!='..') {
           $entry = $dir.'/'.$entry;
           if(is_dir($entry)) {
               //$array[] = $entry;
               if ($subdirs) $array = array_merge($array, read_dir($entry, $subdirs, $filetypes));
           } else {
               if (is_type($entry, $filetypes)) $array[] = $entry;
           }
       }
   }
   $d->close();
   return $array;
}

function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

function check_url($link, $fasttest=1)
{
    //return 'ok';
    $main = array();
    $ch = curl_init();
    curl_setopt ($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)');
    curl_setopt ($ch, CURLOPT_URL, $link);
    curl_setopt ($ch, CURLOPT_HEADER, 1);
    curl_setopt ($ch, CURLOPT_NOBODY, $fasttest);
    curl_setopt ($ch, CURLOPT_AUTOREFERER, 1 - $fasttest);
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER,1);
    curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1 - $fasttest);
    curl_setopt ($ch, CURLOPT_NETRC, 1 - $fasttest);  // omit if you know no urls are FTP links...
    curl_setopt ($ch, CURLOPT_TIMEOUT, 20 - (10 * $fasttest));
    curl_setopt ($ch, CURLOPT_MAXREDIRS, 10);

    $time = microtime_float();
    $response = curl_exec ($ch);
    $time = microtime_float() - $time;
    curl_close ($ch);
    //echo $link."($time)";
    if (preg_match('/HTTP\/1\.\d+\s+(\d+)/', $response, $matches))
    {
        //print_r($matches);
        $code = intval($matches[1]);
        $ok = (($code>=200) && ($code<400));
        if (!$ok)
        {
            //echo 'code('.$code.')';
            return $code;
        }
        else
        {
            if ($time > 8)
                return 'OK (Slow)';
            else
                return 'OK';
        }
    }
    else
    {
        // echo 'response('.$response.')';
        if (strlen(trim($response)) == 0)
        {
            if ($time > 8)
            {
                return 'Slow Null';
            }
            else
            {
                return 'Fast Null';
            }
        }
        else
        {
            $sp = explode("\n", $response, 2);
            return $sp[0];
        }
        //return 'Invalid Header';
    }

} // function

$root = $_SERVER['DOCUMENT_ROOT'];
$url = 'http://www.fatbirder.com';
$folders = array();   $files = array();
$keys = array_keys($directories);
$directory = $directories[$keys[0]];
$directories = (count($directories) > 0) ? array_slice($directories, 1) : array();

$files = read_dir($root.'/'.$keys[0], $directory['subdirs'], $directory['filetypes']);


$files_processed = 0;
$broken_links = array();
echo count($files).' files to process...<br/><br/>';

foreach($files as $file)
{
    $errors_found = 0;
    ?><table class="SectionTable"><tr><th colspan="2">Checking &quot;<?= str_ireplace($root, '', $file); ?>&quot;<script>SCR();</script></th></tr><tr><?
    flush_buffers();
    $regexp = "<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>";
    $input = file_get_contents($file);
    if (preg_match_all("/$regexp/siU", $input, $matches, PREG_SET_ORDER))
    {
        //print_r($matches);
        flush_buffers();
        foreach($matches as $match)
        {
            if ($match[2] == '#' || stripos($match[2], 'phpads') > 0 || stripos($match[2], 'mailto') > 0) continue;
            if (substr(trim($match[2]), 0, 1) == '/') {
                $check_url = $url.$match[2];
            } elseif (substr(trim($match[2]), 0, 1) == '.') {
                $parts = explode('/', $file);
                $path = implode('/', array_slice($parts, 0, count($parts) - 1));
                $path = str_ireplace($root, $url, $path);
                $check_url = $path.'/'.$match[2];
            } else {
                $check_url = $match[2];
            }
            ?><tr><td><a href="<?= $check_url; ?>" target="_blank"><?= $check_url; ?></a></td><?
            flush_buffers();
            $html = check_url($check_url);

            if (stripos($html, 'ok') === false)
            {
                if (!array_key_exists($file, $broken_links)) $broken_links[$file] = array();
                $broken_links[$file][] = $check_url;
                ?><td class="Error"><?= ucfirst($html); ?><script>SCR();</script></td><?
                $errors_found++;
            }
            else
            {
                ?><td class="Success">OK<script>SCR();</script></td><?
            }
            ?></tr><?
            flush_buffers();
        }

        if ($errors_found == 0)
        {
            ?><tr><td class="Success" colspan="2">All links resolved OK.<script>SCR();</script></td></tr><?
        }
    }
    else
    {
        ?><tr><td class="Error" colspan="2">No links found on page.<script>SCR();</script></td></tr><?
    }
    ?></tr></table><br /><br /><?
    $files_processed++;
    if ($bTest === true && $files_processed > 3) break;
}

$still_broken_links = array();
foreach($broken_links as $file => $links)
{
    $errors_found = 0;
    ?><table class="RecheckSectionTable"><tr><th colspan="2">Checking &quot;<?= str_ireplace($root, '', $file); ?>&quot; closely<script>SCR();</script></th></tr><tr><?
    flush_buffers();
    foreach($links as $link)
    {
        $html = check_url($link, 0);

        if (stripos($html, 'ok') === false)
        {
            if (!array_key_exists($file, $still_broken_links)) $still_broken_links[$file] = array();
            $still_broken_links[$file][$link] = $html;
            ?><tr><td><a href="<?= $link; ?>" target="_blank"><?= $link; ?></a></td><td class="Error"><?= ucfirst($html); ?><script>SCR();</script></td></tr><?
            flush_buffers();
            $errors_found++;
        }
        else
        {
            ?><tr><td><?= $link; ?></td><td class="Success">OK<script>SCR();</script></td></tr><?
            flush_buffers();
        }
    }
    if ($errors_found == 0)
    {
        ?><tr><td class="Success" colspan="2">All links resolved OK.<script>SCR();</script></td></tr><?
    }
    ?></tr></table><br /><br /><?
}
$fp = fopen('results/'.str_replace('/', '_', $keys[0]).'.csv', 'w+');
foreach($still_broken_links as $file => $links)
{
    fwrite($fp, '"'.$file.'", ""'."\n");
    $errors_found = 0;
    ?><table class="FinalCheckSectionTable"><tr><th colspan="2">Summary for <?= str_ireplace($root, '', $file); ?><script>SCR();</script></th></tr><tr><?
    flush_buffers();

    foreach($links as $link => $html)
    {
        fwrite($fp, '"'.$link.'", "'.$html.'"'."\n");
        ?><tr><td><a href="<?= $link; ?>" target="_blank"><?= $link; ?></a></td><td class="Error"><?= ucfirst($html); ?><script>SCR();</script></td></tr><?
        flush_buffers();
    }
    ?></tr></table><br /><br /><?
}
fclose($fp);
if (count($directories) > 0)
{
    $keys= array_keys($directories);
    ?><script>document.location = 'check.php?directories=<?= implode(',', $keys); ?>';</script><?
}
?>
