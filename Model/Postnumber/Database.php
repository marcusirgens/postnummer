<?php
/**
 * This file is part of marcuspi/module-postnummer.
 *
 * Postnummer is a Magento 2 module that facilitates post number searches.
 * Copyright (C) 2018 Marcus Pettersen Irgens
 *
 * This program is free software: you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software
 * Foundation, either version 3 of the License, or (at your option) any later
 * version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
 * PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * this program. If not, see <https://www.gnu.org/licenses/>.
 *
 * @author Marcus Pettersen Irgens <marcus.pettersen.irgens@gmail.com>
 * @copyright 2018 Marcus Pettersen Irgens
 * @license GPL-3.0-or-later GNU General Public License version 3
 */

/**
 * Created by PhpStorm.
 * Date: 18/05/2018
 * Time: 23:45
 *
 * @author Marcus Pettersen Irgens <marcus.pettersen.irgens@gmail.com>
 */

namespace Marcuspi\Postnummer\Model\Postnumber;

use Countable;
use Iterator;

/**
 * Class Database
 * @package Marcuspi\Postnummer\Model\Postnumber
 */
class Database implements Iterator, Countable
{
    const POSTNUMBER_FILE = "Data/postnummerregister-ansi.txt";
    const LINE_EXPRESSION = '/^\s*(\S+)\s+(\S+)\s+(\S+)\s+(\S+)\s+(\S+)\s*$/i';

    /**
     * @var \SplFileObject
     */
    protected $file;
    protected $current;
    protected $lineNumber;

    public function __construct()
    {
        $databaseFile = realpath(__DIR__ . '/../../' . static::POSTNUMBER_FILE);
        $this->file = new \SplFileObject($databaseFile, "r");
    }

    /**
     * @param string $line
     * @return bool
     */
    private function lineIsValid(string $line): bool
    {
        return boolval(preg_match(self::LINE_EXPRESSION, $line));
    }

    /**
     * @param string $line
     * @return array
     */
    private function getLineContents(string $line): array
    {
        preg_match(self::LINE_EXPRESSION, $line, $matches);

        return [
            "number" => $matches[1],
            "area" => $matches[2],
            "municipalityNum" => $matches[3],
            "municipality" => $matches[4],
            "type" => $matches[5],
        ];
    }

    /**
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current()
    {
        return $this->getLineContents($this->current);
    }

    /**
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next()
    {
        do {
            $this->current = trim(mb_convert_encoding($this->file->fgets(), "UTF-8", "ISO-8859-1"));
        } while (!$this->file->eof() && !$this->lineIsValid($this->current));

        $this->lineNumber = $this->lineNumber + 1;
    }

    /**
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key()
    {
        return $this->lineNumber;
    }

    /**
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid()
    {
        // TODO: Implement valid() method.
        return !$this->file->eof();
    }

    /**
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind()
    {
        // TODO: Implement rewind() method.
        $this->file->fseek(0);
        $this->lineNumber = -1;
        $this->next();
    }

    /**
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     * @since 5.1.0
     */
    public function count()
    {
        $i = 0;
        foreach (new static as $line) {
            $i++;
        }

        return $i;
    }
}
