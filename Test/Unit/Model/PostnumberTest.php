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

namespace Marcuspi\Postnummer\Model;

use PHPUnit\Framework\TestCase;

class PostnumberTest extends TestCase
{
    public function testConstructor()
    {
        $object = new Postnumber("5146", "FYLLINGSDALEN", Postnumber::TYPE_STREET, "BERGEN", "1201");

        $this->assertEquals("5146", $object->getNumber());
        $this->assertEquals("BERGEN", $object->getMunicipality());
        $this->assertEquals("1201", $object->getMunicipalityNumber());
        $this->assertEquals("G", $object->getStringType());
        $this->assertEquals(Postnumber::TYPE_STREET, $object->getType());
        $this->assertTrue(is_string($object->getTypeExplained()));
        $this->assertEquals("FYLLINGSDALEN", $object->getArea());
    }

    public function testOtherTypes()
    {
        $postbox = new Postnumber("0001", "OSLO", Postnumber::TYPE_POSTBOX, "OSLO", "0301");
        $service = new Postnumber("0040", "OSLO", Postnumber::TYPE_SERVICE, "OSLO", "0301");
        $combined = new Postnumber("0037", "OSLO", Postnumber::TYPE_COMBINED, "OSLO", "0301");
        $multiple = new Postnumber("0002", "NOTAPLACE", Postnumber::TYPE_MULTIPLE, "SOMEWHERE", "0301");

        $this->assertEquals(Postnumber::TYPE_POSTBOX, $postbox->getType());
        $this->assertEquals("P", $postbox->getStringType());
        $this->assertTrue(is_string($postbox->getTypeExplained()));

        $this->assertEquals(Postnumber::TYPE_SERVICE, $service->getType());
        $this->assertEquals("S", $service->getStringType());
        $this->assertTrue(is_string($service->getTypeExplained()));

        $this->assertEquals(Postnumber::TYPE_COMBINED, $combined->getType());
        $this->assertEquals("B", $combined->getStringType());
        $this->assertTrue(is_string($combined->getTypeExplained()));

        $this->assertEquals(Postnumber::TYPE_MULTIPLE, $multiple->getType());
        $this->assertEquals("F", $multiple->getStringType());
        $this->assertTrue(is_string($multiple->getTypeExplained()));
    }

    public function testTypeThrowsException()
    {
        $this->expectException(\Exception::class);
        $number = new Postnumber("0002", "NOTAPLACE", 9000, "SOMEWHERE", "0301");
        $type = $number->getType();
    }

    public function testStringTypeThrowsException()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessageRegExp("/^Invalid type for post number [0-9]+$/i");
        $number = new Postnumber("0002", "NOTAPLACE", 9000, "SOMEWHERE", "0301");
        $type = $number->getStringType();
    }

    public function testExceptionIsCatchable()
    {
        $number = new Postnumber("0002", "NOTAPLACE", 9000, "SOMEWHERE", "0301");
        try {
            $type = $number->getTypeExplained();
        } catch (\Exception $e) {
            $this->assertInstanceOf("Exception", $e);
        }
    }

    public function testTypeExplainedThrowsException()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessageRegExp("/^Invalid type for post number [0-9]+$/i");
        $number = new Postnumber("0002", "NOTAPLACE", 9000, "SOMEWHERE", "0301");
        $type = $number->getTypeExplained();
    }

    public function testCanReturnArray()
    {
        $object = new Postnumber("5146", "FYLLINGSDALEN", Postnumber::TYPE_STREET, "BERGEN", "1201");
        $this->assertArrayHasKey("number", $object->toArray());
        $this->assertArrayHasKey("area", $object->toArray());
        $this->assertArrayHasKey("type", $object->toArray());
        $this->assertArrayHasKey("municipality", $object->toArray());
        $this->assertArrayHasKey("municipalityNumber", $object->toArray());
    }

}
