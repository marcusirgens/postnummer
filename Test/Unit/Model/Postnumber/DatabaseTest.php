<?php
/**
 * This file is part of marcuspi/module-postnummer.
 *
 * Postnummer is a Magento 2 module that facilitates post number searches.
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
