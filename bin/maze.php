#!/usr/bin/env php
<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';

use Mmm\Maze\AStar2D;

// Globals //

$sampleInput = array(<<<TEXT
5
**B*O
****B
*BBB*
*B***
***BD
TEXT
,<<<TEXT
5
**B*O
****B
*BBB*
*B***
**BBD
TEXT
,<<<TEXT
5
O**
BB*
***
*BB
**D
TEXT
,
);


// Global functions //

function PR($s) {
	print_r($s);
}


// Begin Main // CLI Input //

$input = $sampleInput[0];
$command = (isset($argv[1])) ? $argv[1] : FALSE;
$idx = (int)$command;

if ($command === FALSE || !is_int($idx)) {
	// Take user input
	$input = '';
	$nLines = 0;
	$expectedLines = 0;
	while (false !== ($line = fgets(STDIN))) {
		if ($nLines == 0) {
			$l = trim($line);
			if (is_numeric($l)) {
				$expectedLines = (int)$l;
			} else {
				echo "Unexpected input\n";
				exit;
			}
		}

		$input .= $line . PHP_EOL;
		if ($nLines == $expectedLines) {
			break;
		}

		$nLines++;
	}

} else {

	// Use canned sample input
	if (isset($sampleInput[$idx])) {
		$input = $sampleInput[$idx];
	}
}


// Run algorithm

$aStar = new AStar2D();
$result = $aStar->parse($input);
if (!$result) {
	echo "Something wrong with your input.\n" . $aStar->getMessage();
	exit;
}

$solution = $aStar->getSolution();


// Print solution

if ($command !== FALSE) {
	echo implode(' => ', $solution) . "\n";
}
//PR($solution);

$msg = (empty($solution) ? 'No' : 'Yes, ' . (count($solution) - 1)) . "\n";
echo $msg;
