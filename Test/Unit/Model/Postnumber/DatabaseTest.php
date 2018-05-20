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

namespace Marcuspi\Postnummer\Model\Postnumber;

use Marcuspi\Postnummer\Model\Postnumber;
use PHPUnit\Framework\TestCase;

class DatabaseTest extends TestCase
{
    public function testDatabase()
    {
        $db = new Database();

        $db->rewind();
        if($db->valid()) {
            $postnumber = $db->current();
        }

        $this->assertArrayHasKey("number", $postnumber);
        $this->assertRegExp("/^[0-9]{1,4}$/", (string) $postnumber["number"]);
        $this->assertRegExp("/^[0-9]+$/", (string) $db->key());
        $this->assertEquals($db->current(), $db->current());
        // Count the database
        $size = 0;

        foreach ($db as $entry) {
            $size++;
        }

        $this->assertEquals($db->count(), $size);
    }
}
