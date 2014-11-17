<?php

namespace Mmm\AStar;

abstract class AStar
{
	private $openList;
	private $closedList;

	public function __construct()
	{
		$this->openList = new NodeList();
		$this->closedList = new NodeList();
	}

	/**
	 * @param Node $node
	 * @return Node[]
	 */
	abstract public function getAdjacentNodes(Node $node);

	/**
	 * @param Node $node
	 * @param Node $adjacent
	 * @return float
	 */
	abstract public function getRunningCost(Node $node, Node $adjacent);

	/**
	 * @param Node $start
	 * @param Node $end
	 * @return float
	 */
	abstract public function getEstimatedCost(Node $start, Node $end);

	/**
	 * @return NodeList
	 */
	public function getOpenList()
	{
		return $this->openList;
	}

	/**
	 * @return NodeList
	 */
	public function getClosedList()
	{
		return $this->closedList;
	}

	/**
	 * Sets the algorithm to its initial state
	 */
	public function clear()
	{
		$this->getOpenList()->clear();
		$this->getClosedList()->clear();
	}

	/**
	 * @param Node $origin
	 * @param Node $destination
	 * @return Node[]
	 */
	public function run(Node $origin, Node $destination)
	{
		$path = array();

		$this->clear();

		$origin->setG(0);
		$origin->setH($this->getEstimatedCost($origin, $destination));

		$this->getOpenList()->add($origin);

		while (!$this->getOpenList()->isEmpty()) {
			$currentNode = $this->getOpenList()->extractBest();

			$this->getClosedList()->add($currentNode);

			if ($currentNode->getId() === $destination->getId()) {
				$path = $this->generatePathFromStartNodeTo($currentNode);
				break;
			}

			$successors = $this->computeAdjacentNodes($currentNode, $destination);

			foreach ($successors as $successor) {
				if ($this->getOpenList()->contains($successor)) {
					$successorInOpenList = $this->getOpenList()->get($successor);

					if ($successor->getG() >= $successorInOpenList->getG()) {
						continue;
					}
				}

				if ($this->getClosedList()->contains($successor)) {
					$successorInClosedList = $this->getClosedList()->get($successor);

					if ($successor->getG() >= $successorInClosedList->getG()) {
						continue;
					}
				}

				$successor->setParent($currentNode);

				$this->getClosedList()->remove($successor);

				$this->getOpenList()->add($successor);
			}
		}

		return $path;
	}

	private function generatePathFromStartNodeTo(Node $node)
	{
		$path = array();

		$currentNode = $node;

		while ($currentNode !== null) {
			array_unshift($path, $currentNode);

			$currentNode = $currentNode->getParent();
		}

		return $path;
	}

	private function computeAdjacentNodes(Node $node, Node $goal)
	{
		$nodes = $this->getAdjacentNodes($node);

		foreach ($nodes as $adjacentNode) {
			$adjacentNode->setG($node->getG() + $this->getRunningCost($node, $adjacentNode));
			$adjacentNode->setH($this->getEstimatedCost($adjacentNode, $goal));
		}

		return $nodes;
	}

}
