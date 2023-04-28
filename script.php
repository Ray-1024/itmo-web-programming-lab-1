<?php


function checkXYR($x, $y, $r)
{
    if (is_string($x) && is_string($y) && is_string($r)) {
        if ($x != "-2" && $x != "-1.5" && $x != "-1" && $x != "-0.5" && $x != "0" && $x != "0.5" && $x != "1" && $x != "1.5" && $x != "2")
            return false;
        if ($r != "1" && $r != "2" && $r != "3" && $r != "4" && $r != "5")
            return false;
        if (trim('' . floatval($y)) != trim($y) || strlen(trim($y)) > 5 && floatval($y) <= -3.0 || floatval($y) >= 5.0)
            return false;
        return true;
    }
    return false;
}

function checkArea($x, $y, $r)
{
    if (($x >= 0.0) && ($x <= $r / 2.0) && ($y <= 0.0) && ($y >= $x - $r / 2.0)) {
        return true;
    }
    if (($x >= -$r / 2.0) && ($x <= 0.0) && ($y >= 0.0) && ($y <= $r)) {
        return true;
    }
    if (($x >= -$r / 2.0) && ($x <= 0.0) && ($y >= -$r / 2.0) && ($y <= 0.0) && (($x * $x + $y * $y) <= ($r * $r / 4.0)))
        return true;
    return false;
}


function loadResultsArray($filename)
{
    $text = file_get_contents($filename);
    if (!$text) return array();
    $array = unserialize(base64_decode($text));
    if (!$array) return array();
    return $array;
}


function saveResultsArray($array, $filename)
{
    $text = base64_encode(serialize($array));
    file_put_contents($filename, $text);
}


function fillResultsTable($document, $resultsArray)
{
    if (is_null($document)) return;
    $table = $document->getElementById("resultTable");
    if (is_null($table)) return;

    for ($i = 0; $i < count($resultsArray); ++$i) {

        $line = new DOMElement("tr");
        $table->appendChild($line);
        for ($j = 0; $j < count($resultsArray[$i]); ++$j) {
            $cell = new DOMElement("td");
            $cell->textContent = $resultsArray[$i][$j];
            $line->appendChild($cell);
        }
    }
}

function func()
{
    $curr = microtime(true);
    date_default_timezone_set('Europe/Moscow');
    $pageFilename = __DIR__ . '/page.html';
    $resultsFilename = __DIR__ . '/results.txt';
    $valid = true;
    $document = new DOMDocument();

    $document->loadHTMLFile($pageFilename);


    if (isset($_POST["xRadio"]) && isset($_POST["yText"]) && isset($_POST["rCheckbox"])) {

        $xRadio = $_POST["xRadio"];
        $yText = $_POST["yText"];
        $rCheckbox = null;
        if (is_array($_POST["rCheckbox"])) {
            foreach ($_POST["rCheckbox"] as $i) {
                if (!is_null($rCheckbox)) {
                    $valid = false;
                    break;
                }
                $rCheckbox = $i;
            }
        } else {
            $valid = false;
        }

        if ($valid && checkXYR($xRadio, $yText, $rCheckbox)) {

            $resultOfTest = checkArea($xRadio, $yText, $rCheckbox);
            $resultStr = "Точка не попала в область";
            if ($resultOfTest) {
                $resultStr = "Точка попала в область";
            }

            $arr = loadResultsArray($resultsFilename);
            array_push($arr, array("" . (count($arr) + 1), date('m/d/Y h:i:s a', time()), "" . (microtime(true) - $curr), "" . $xRadio, "" . $yText, "" . $rCheckbox, $resultStr));
        }
    }
    if (!$valid) {
        $arr = loadResultsArray($resultsFilename);
    }
    fillResultsTable($document, $arr);
    fillResultsTable($document, $arr);
    saveResultsArray($arr, $resultsFilename);

    echo $document->saveHTML();
}

func();

?>