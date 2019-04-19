<?php
include './src/Parser.php';
include './src/Meta.php';

if($argc < 2){
    die("Please specify text\n");
}
$text = $argv[1];

$parser = new Parser($text);

$parser->getText();