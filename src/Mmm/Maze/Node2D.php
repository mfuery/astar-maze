<?php

namespace Mmm\Maze;

use Mmm\AStar\NodeAbstract;

class Node2D extends NodeAbstract
{
	private $id;
	private $x;
	private $y;

	/**
	 * @param $x
	 * @param $y
	 */
	public function __construct($x, $y)
	{
		$this->x = $x;
		$this->y = $y;
		$this->id = $x . 'x' . $y;
	}

	/**
	 * @return string
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @return int
	 */
	public function getX()
	{
		return $this->x;
	}

	/**
	 * @return int
	 */
	public function getY()
	{
		return $this->y;
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return $this->getId();
	}

}

