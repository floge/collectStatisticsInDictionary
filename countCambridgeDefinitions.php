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

$lettersUrl = 'http://dictionary.cambridge.org/browse/english/';
$letters = new Document($lettersUrl, true);

$wordsUrl = array();

foreach ($letters->find('.cdo-browse-letters a') as $letterUrl) {

    $groupResults = new Document($letterUrl->getAttribute('href'), true);

    foreach ($groupResults->find('.cdo-browse-groups a') as $groupResult) {
        $result = new Document($groupResult->getAttribute('href'), true);

        foreach ($result->find('.cdo-browse-entries a') as $wordUrl) {
            $wordsUrl[] = $wordUrl->getAttribute('href');
        }
    }
}

$totalWords = 0;
$totalDefinitions = 0;
foreach ($wordsUrl as $wordUrl) {

    $wordDocument = new Document($wordUrl, true);

    if (count($elements = $wordDocument->find('#dataset-british .headword')) != 0) { //check if word has name

        $entryName = $wordDocument->find('#dataset-british .headword')[0]->text();

        $totalWords += count($wordDocument->find('#dataset-british .headword'));
        $totalWords += count($wordDocument->find('#dataset-british .runon'));

        if (count($elements = $wordDocument->find('#dataset-british .sense-block .def-head')) != 0) { //check if word has definitions
            $totalDefinitions += count($wordDocument->find('#dataset-british .sense-block .def-head'));

            echo 'Current entry has definitions: ' . $entryName . PHP_EOL;
        }
    }

}

echo 'Total words/Total definitions: ' . $totalWords . '/' . $totalDefinitions . PHP_EOL;
echo 'Statistics from Cambridge Advanced Learner’s Dictionary & Thesaurus' . PHP_EOL;

$endTime = microtime(true);
$executionTime = ($endTime - $startTime) / 60;

echo 'Total Execution Time: ' . $executionTime . ' minutes' . PHP_EOL;

exit;
