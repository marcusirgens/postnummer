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

class DatabaseContentsTest extends TestCase
{
    /**
     * @var Database
     */
    protected $database;
    /**
     * @var PostnumberRepository
     */
    protected $repository;

    protected function setUp()
    {
        $this->database = new Database();
    }

    public function testData()
    {
        $this->assertEquals(4855, count($this->database));

        $nordHidle = "4173";
        $helgoey = "4167";
        $runde = "6096";
        $varangerbotn = "9820";

        foreach ($this->database as $row) {
            switch ($row["number"]) {
                case $helgoey:
                    $helgoey = $row;
                    break;
                case $nordHidle:
                    $nordHidle = $row;
                    break;
                case $runde:
                    $runde = $row;
                    break;
                case $varangerbotn:
                    $varangerbotn = $row;
                    break;
            }
        }

        // Assert that we have the data.
        $this->assertTrue(is_iterable($helgoey));
        $this->assertTrue(is_iterable($nordHidle));
        $this->assertTrue(is_iterable($runde));
        $this->assertTrue(is_iterable($varangerbotn));

        $this->assertEquals("HELGØY I RYFYLKE", $helgoey["area"]);
        $this->assertEquals("NORD-HIDLE", $nordHidle["area"]);
        $this->assertEquals("HERØY (MØRE OG ROMSDAL)", $runde["municipality"]);
        $this->assertEquals("UNJARGGA NESSEBY", $varangerbotn["municipality"]);
    }
}
