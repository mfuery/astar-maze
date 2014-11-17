<?php

namespace Mmm\AStar;

interface Node
{
	/**
	 * Obtains the node's unique ID
	 * @return string
	 */
	public function getId();

	/**
	 * @param Node $parent
	 */
	public function setParent(Node $parent);

	/**
	 * @return Node | null
	 */
	public function getParent();

	/**
	 * @return float
	 */
	public function getF();

	/**
	 * @param float $score
	 */
	public function setG($score);

	/**
	 * @return float
	 */
	public function getG();

	/**
	 * @param float $score
	 */
	public function setH($score);

	/**
	 * @return float
	 */
	public function getH();
}
