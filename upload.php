<?php
$data = str_replace(' ', '+', $_POST['data']);
$data = substr($data, strpos($data, ",") + 1);
$data = base64_decode($data);

$dir = $_SERVER['DOCUMENT_ROOT'] . '/Images/';

$list = scandir($dir);
$name=(count($list) - 1) . '.jpg';
file_put_contents($dir.$name, $data);
$list = scandir($dir);
unset($list[0]);
unset($list[1]);
foreach ($list as $file) {
    echo '<a target="_blank" href="/lab45/Images/'.$file.'">'.$file.'</a><br />';
}