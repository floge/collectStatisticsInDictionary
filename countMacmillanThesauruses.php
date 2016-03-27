<?php

$startTime = microtime(true);

require "vendor/autoload.php";

use DiDom\Document;
use DiDom\Query;

//error handler function
function customError($errno, $errstr)
{
    echo PHP_EOL . "<b>Error:</b> [$errno] $errstr<br>";
    echo PHP_EOL . "Ending Script" . PHP_EOL;
    die();
}

//set error handler
set_error_handler("customError");

$lettersUrl = 'http://www.macmillandictionary.com/browse/british/';
$letters = new Document($lettersUrl, true);

$wordsUrl = array();

foreach ($letters->find('#letters a') as $letterUrl) {

    $groupResults = new Document($letterUrl->getAttribute('href'), true);

    foreach ($groupResults->find('#groupResult a') as $groupResult) {
        $result = new Document($groupResult->getAttribute('href'), true);

        foreach ($result->find('#result a') as $wordUrl) {
            $wordsUrl[] = $wordUrl->getAttribute('href');
        }
    }
}

$totalEntriesHaveSynonyms = 0;
$totalEntries = 0;
foreach ($wordsUrl as $wordUrl) {

    if ($wordUrl != 'http://www.macmillandictionary.com/dictionary/british/huntington-s-disease' && $wordUrl != 'http://www.macmillandictionary.com/dictionary/british/za-atar') { //this link is NOT FOUND

        $wordDocument = new Document($wordUrl, true);

        if (count($elements = $wordDocument->find('h1 .BASE')) != 0) { //check if word has name

            $entryName = $wordDocument->find('h1 .BASE')[0]->text();

            $totalEntries++;

            if (count($elements = $wordDocument->find('.moreButton')) != 0) { //check if word has synonym
                $totalEntriesHaveSynonyms++;

                echo 'Current entry has thesauruses: ' . $entryName . PHP_EOL;
            }
        }
    }
}

echo 'Total entries have thesauruses/Total entries: ' . $totalEntriesHaveSynonyms . '/' . $totalEntries . PHP_EOL;
echo 'Statistics from Macmillan English Dictionary' . PHP_EOL;

$endTime = microtime(true);
$executionTime = ($endTime - $startTime) / 60;

echo 'Total Execution Time: ' . $executionTime . ' minutes' . PHP_EOL;

exit;
