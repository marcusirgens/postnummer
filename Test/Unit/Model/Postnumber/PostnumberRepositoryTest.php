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

use Magento\Framework\Exception\NoSuchEntityException;
use Marcuspi\Postnummer\Model\Postnumber;
use Marcuspi\Postnummer\Model\PostnumberFactory;
use PHPUnit\Framework\TestCase;

class PostnumberRepositoryTest extends TestCase
{
    protected $factory;
    protected $database;

    protected function setUp()
    {
        $mockNumber = $this->createMock(Postnumber::class);

        $mockCreation = $this->createMock(PostnumberCreation::class);
        $mockCreation->method("create")
            ->willReturn($mockNumber);

        $this->factory = $this->createMock(PostNumberCreationFactory::class);
        $this->factory->method("create")->willReturn($mockCreation);

        $this->database = $this->createMock(Database::class);
        $this->database->method("current")->willReturn(
            [
                "number" => "0001",
                "area" => "SOMEPLACE",
                "type" => Postnumber::TYPE_COMBINED,
                "municipality" => "RYFYLKE",
                "municipalityNum" => 1337
            ]
        );
        $this->database->method("valid")->willReturn(true, false);

    }

    public function testCanGetOneFromId()
    {
        $repo = new PostnumberRepository(
            new Database(),
            $this->factory
        );

        $this->assertInstanceOf(Postnumber::class, $repo->get("5146"));
    }

    public function testThrowsExceptionOnMissingNumber()
    {
        $this->expectException(NoSuchEntityException::class);
        $this->expectExceptionMessageRegExp("/^No such entity with .* = .*$/");

        $repo = new PostnumberRepository(
            new Database(),
            $this->factory
        );

        $this->assertInstanceOf(Postnumber::class, $repo->get("10000"));
    }


    public function testCombinedType()
    {
        $db = $this->createMock(Database::class);
        $db->method("current")->willReturn(
            [
                "number" => "0001",
                "area" => "SOMEPLACE",
                "type" => "B",
                "municipality" => "RYFYLKE",
                "municipalityNum" => 1337
            ]
        );
        $db->method("valid")->willReturn(true);

        $repo = new PostnumberRepository(
            $db,
            $this->factory
        );

        $this->assertInstanceOf(Postnumber::class, $repo->get("0001"));
    }

    public function testMultipleType()
    {
        $db = $this->createMock(Database::class);
        $db->method("current")->willReturn(
            [
                "number" => "0001",
                "area" => "SOMEPLACE",
                "type" => "F",
                "municipality" => "RYFYLKE",
                "municipalityNum" => 1337
            ]
        );
        $db->method("valid")->willReturn(true);

        $repo = new PostnumberRepository(
            $db,
            $this->factory
        );

        $this->assertInstanceOf(Postnumber::class, $repo->get("0001"));
    }

    public function testStreetType()
    {
        $db = $this->createMock(Database::class);
        $db->method("current")->willReturn(
            [
                "number" => "0001",
                "area" => "SOMEPLACE",
                "type" => "G",
                "municipality" => "RYFYLKE",
                "municipalityNum" => 1337
            ]
        );
        $db->method("valid")->willReturn(true);

        $repo = new PostnumberRepository(
            $db,
            $this->factory
        );

        $this->assertInstanceOf(Postnumber::class, $repo->get("0001"));
    }

    public function testBoxType()
    {
        $db = $this->createMock(Database::class);
        $db->method("current")->willReturn(
            [
                "number" => "0001",
                "area" => "SOMEPLACE",
                "type" => "P",
                "municipality" => "RYFYLKE",
                "municipalityNum" => 1337
            ]
        );
        $db->method("valid")->willReturn(true);

        $repo = new PostnumberRepository(
            $db,
            $this->factory
        );

        $this->assertInstanceOf(Postnumber::class, $repo->get("0001"));
    }


    public function testServiceType()
    {
        $db = $this->createMock(Database::class);
        $db->method("current")->willReturn(
            [
                "number" => "0001",
                "area" => "SOMEPLACE",
                "type" => "S",
                "municipality" => "RYFYLKE",
                "municipalityNum" => 1337
            ]
        );
        $db->method("valid")->willReturn(true);

        $repo = new PostnumberRepository(
            $db,
            $this->factory
        );

        $this->assertInstanceOf(Postnumber::class, $repo->get("0001"));
    }

    public function testInvalidTypeFromDatabase()
    {
        $db = $this->createMock(Database::class);
        $db->method("current")->willReturn(
            [
                "number" => "0001",
                "area" => "SOMEPLACE",
                "type" => "Å",
                "municipality" => "RYFYLKE",
                "municipalityNum" => 1337
            ]
        );
        $db->method("valid")->willReturn(true);

        $repo = new PostnumberRepository(
            $db,
            $this->factory
        );

        $this->expectException(\Exception::class);
        $repo->get(1);
    }

    public function testGetArea()
    {
        $db = $this->getMockBuilder(Database::class)
            ->disableOriginalConstructor()
            ->setMethods(["current", "valid", "rewind", "next"])
            ->getMock();
        $db->method("current")->willReturn(
            ["number" => "0001", "area" => "SOMEPLACE", "type" => "G", "municipality" => "RYFYLKE", "municipalityNum" => 1337],
            ["number" => "0002", "area" => "SOMEPLACE", "type" => "P", "municipality" => "RYFYLKE", "municipalityNum" => 1337],
            ["number" => "0003", "area" => "SOMEWHERE", "type" => "S", "municipality" => "ODDA", "municipalityNum" => 1231]
        );
        $db->method("valid")->willReturn(true, true, true, false);

        $repo = new PostnumberRepository(
            $db,
            $this->factory
        );

        $result = $repo->getArea("SOMEPLACE");
        $hits = collect(iterator_to_array($result));
        $this->assertInstanceOf(Postnumber::class, $hits->first());
        $this->assertInstanceOf(\Generator::class, $result);
    }
}
