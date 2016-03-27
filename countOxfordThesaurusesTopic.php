<?php

$startTime = microtime(true);

require "vendor/autoload.php";

use DiDom\Document;

$subCategoryUrl = 'http://www.oxfordlearnersdictionaries.com/topic/animal_homes';
$document = new Document($subCategoryUrl, true);

$numberOfSubjects = 0;
foreach ($document->find('#rightcolumn div div ul li') as $subject) {
    $numberOfSubjects++;
}

$homePageUrl = 'http://www.oxfordlearnersdictionaries.com/topic/';
$document = new Document($homePageUrl, true);

$numberOfCategories = 0;
foreach ($document->find('#topic-list dd dl dt') as $category) {
    $numberOfCategories++;
}

$subCategoriesUrl = array();
foreach ($document->find('#topic-list dd dl dd ul li a') as $subCategoryUrl) {
    $subCategoriesUrl[] = $subCategoryUrl->getAttribute('href');
}

$entriesUrl = array();
foreach ($subCategoriesUrl as $subCategoryUrl) {
    $document = new Document($subCategoryUrl, true);

    foreach ($document->find('.wordpool li a') as $entryUrl) {
        $entriesUrl[] = $entryUrl->getAttribute('href');
    }
}

echo 'Total thesauruses subjects: ' . $numberOfSubjects . PHP_EOL;
echo 'Total thesauruses categories: ' . $numberOfCategories . PHP_EOL;
echo 'Total thesauruses subcategories/Total words in thesauruses subcategories: ' . count($subCategoriesUrl) . '/' . count($entriesUrl) . PHP_EOL;
echo 'Statistics from Oxford Advanced Learner’s Dictionary' . PHP_EOL;

$endTime = microtime(true);
$executionTime = ($endTime - $startTime) / 60;

echo 'Total Execution Time: ' . $executionTime . ' minutes' . PHP_EOL;

exit;