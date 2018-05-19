<?php
/**
 * Created by PhpStorm.
 * Date: 12/05/2018
 * Time: 17:59
 *
 * @author Marcus Pettersen Irgens <marcus@trollweb.no>
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
