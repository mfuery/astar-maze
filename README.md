astar-maze
==========

PHP 2D Maze Pathfinding using AStar algorithm

To Run:

composer update
php bin/maze.php


Coding Challenge
----------------

Given a maze made of ASCII characters fed through standard input (console), find the path that is the shortest distance
from Origin to Destination. This must be done without going through any barriers. The movements you are permitted to do
are right, left, up, down. You are not allowed to move diagonally.

The maze is in the form of a grid. The first line of the standard input specifies the number of columns and rows. As an
example, if the first row is 7, it indicates a 7 x 7 grid. The actual maze (grid) follows on the next line of the input.
The starting point is the character ‘O’ (Origin) and the goal is to make your way to the ‘D’ character (for Destination).
You are not allowed to pass through any barriers (the character ‘B’). Stars (the character '*') are passable spaces.

If there is no possible solution, the output should read “No”. However, if there is a possible solution, the output
should read “Yes” followed by the number indicating the shortest path to the destination.

Write an algorithm that navigates the maze and finds the shortest path to reach the destination.

Sample Input:
5
**B*O
****B
*BBB*
*B***
***BD
Output:
Yes, 14
