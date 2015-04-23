<?php

namespace Mmm\Maze;

use Mmm\AStar\AStar;
use Mmm\AStar\Node;

class AStar2D extends AStar
{
	const ORIGIN_TOKEN = 'O';
	const DESTINATION_TOKEN = 'D';
	const OPEN_TOKEN = '*';
	const BOUNDARY_TOKEN = 'B';

	/**
	 * @var int width
	 */
	protected $w = 0;

	/**
	 * @var int height
	 */
	protected $h = 0;

	protected $msg = '';

	/**
	 * Parse text into nested array of cells within rows
	 * @var array (y1 => (x1, x2, x3, ..), ...)
	 */
	protected $asciiMap = array();

	/**
	 * @inheritdoc
	 */
	public function getAdjacentNodes(Node $node)
	{
		$freeMovementTokens = array(static::ORIGIN_TOKEN, static::DESTINATION_TOKEN, static::OPEN_TOKEN);
		$adjacent = array();

		// width and height of maze, 0-indexed
		$wi = $this->w - 1;
		$hi = $this->h - 1;

		$x = $node->getX();
		$y = $node->getY();

		if ($x > 0) {
			// check to left
			if (in_array($this->asciiMap[$y][$x-1], $freeMovementTokens)) {
				$adjacent[] = new Node2D($x-1, $y);
				//echo 'l';
			}
		}
		if ($x < $wi) {
			// check to right
			if (in_array($this->asciiMap[$y][$x+1], $freeMovementTokens)) {
				$adjacent[] = new Node2D($x+1, $y);
				//echo 'r';
			}
		}
		if ($y > 0) {
			// check up
			if (in_array($this->asciiMap[$y-1][$x], $freeMovementTokens)) {
				$adjacent[] = new Node2D($x, $y-1);
				//echo 'u';
			}
		}
		if ($y < $hi) {
			// check down
			if (in_array($this->asciiMap[$y+1][$x], $freeMovementTokens)) {
				$adjacent[] = new Node2D($x, $y+1);
				//echo 'd';
			}
		}

		return $adjacent;
	}

	/**
	 * @inheritdoc
	 */
	public function getRunningCost(Node $origin, Node $node)
	{
		return static::getDistance($origin, $node);
	}

	/**
	 * @inheritdoc
	 */
	public function getEstimatedCost(Node $start, Node $end)
	{
		return static::getDistance($start, $end);
	}

	/**
	 * @param Node $start
	 * @param Node $end
	 * @return float
	 */
	public static function getDistance(Node $start, Node $end)
	{
		$xFactor = pow($start->getX() - $end->getX(), 2);
		$yFactor = pow($start->getY() - $end->getY(), 2);

		return sqrt($xFactor + $yFactor);
	}

	/**
	 * @param $str
	 * @return bool TRUE if success, FALSE if not
	 *   Call getMessage() to see error result
	 */
	public function parse($str) {
		$j = 0;
		//echo "\n---\n$str\n---\n";

		foreach (preg_split("/((\r?\n)|(\r\n?))/", $str) as $line) {

			if (empty($line) || is_numeric($line)) {
				continue;

			} else {
				$this->asciiMap[$j] = str_split($line);
				$rowWidth = count($this->asciiMap[$j]);
				if ($this->w == 0) {
					$this->w = $rowWidth;
				}
				if ($rowWidth != $this->w) {
					$this->msg = "Something wrong with your input. Expected width: " . $this->w .
						". Actual width: " . $rowWidth . " in line: " . $line;
					return false;
				}

				$j++;
				$this->h++;
			}
		}

//		PR($this->asciiMap);

		return TRUE;
	}

	/**
	 * @return string widthxheight
	 */
	public function getDimensions()
	{
		return $this->w . 'x' . $this->h;
	}


	/**
	 * @return Node[]
	 */
	public function getSolution()
	{
		$origin = null;
		$destination = null;

		for ($j = 0; $j < $this->h; $j++) {
			for ($i = 0; $i < $this->w; $i++) {
				if ($this->asciiMap[$j][$i] == static::ORIGIN_TOKEN) {
					$origin = new Node2D($i, $j);
				} else if ($this->asciiMap[$j][$i] == static::DESTINATION_TOKEN) {
					$destination = new Node2D($i, $j);
				}
			}
		}

		if (!$origin || !$destination) {
			return array();
		}

		return $this->run($origin, $destination);
	}

	public function generateRandomMaze($w, $h = null)
	{
		$bWeight = 0.3;
		//$starWeight = 0.75;
		$maze = array();

		if (!$h) {
			$h = $w;
		}

		for ($j = 0; $j < $h; $j++) {
			$maze[$j] = array();
			for ($i = 0; $i < $w; $i++) {
				$n = mt_rand() / mt_getrandmax();
				$char = ($n < $bWeight) ? static::BOUNDARY_TOKEN : static::OPEN_TOKEN;
				$maze[$j][$i] = $char;
			}
		}

		$maze[0][0] = static::ORIGIN_TOKEN;
		$maze[$w-1][$h-1] = static::DESTINATION_TOKEN;

		return $maze;
	}

	public function asciiMapToText($asciiMap)
	{
		$h = count($asciiMap);
		$w = count($asciiMap[0]);
		$str = '';

		for ($j = 0; $j < $h; $j++) {
			for ($i = 0; $i < $w; $i++) {
				$str .= $asciiMap[$j][$i];
			}
			$str .= "\n";
		}

		return $str;
	}

	/**
	 * @return string
	 */
	public function getMessage()
	{
		return $this->msg;
	}

}
