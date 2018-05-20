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
use Marcuspi\Postnummer\Model\PostnumberFactory;
use PHPUnit\Framework\TestCase;

class PostnumberCreationTest extends TestCase
{
    protected $factory;

    protected function setUp()
    {
        $mockPostnumber = $this->createMock(Postnumber::class);

        $mockFactory = $this->createMock(PostnumberFactory::class);
        $mockFactory->method("create")
            ->willReturn($mockPostnumber);

        $this->factory = $mockFactory;
    }

    public function testSettersAndGetters()
    {
        $creation = new PostnumberCreation($this->factory);

        $this->assertSame($creation, $creation->setNumber(5146));
        $this->assertSame($creation, $creation->setArea("FYLLINGSDALEN"));
        $this->assertSame($creation, $creation->setType(Postnumber::TYPE_STREET));
        $this->assertSame($creation, $creation->setMunicipality("BERGEN"));
        $this->assertSame($creation, $creation->setMunicipalityNumber(1201));

        $this->assertEquals(5146, $creation->getNumber());
        $this->assertEquals("FYLLINGSDALEN", $creation->getArea());
        $this->assertEquals(Postnumber::TYPE_STREET, $creation->getType());
        $this->assertEquals("BERGEN", $creation->getMunicipality());
        $this->assertEquals(1201, $creation->getMunicipalityNumber());
    }

    public function testReturnsNewPostnumber()
    {
        $creation = new PostnumberCreation($this->factory);

        $creation->setNumber(5146)
            ->setArea("FYLLINGSDALEN")
            ->setType(Postnumber::TYPE_STREET)
            ->setMunicipality("BERGEN")
            ->setMunicipalityNumber(1201);

        $output = $creation->create();

        $this->assertInstanceOf(Postnumber::class, $output);
    }

    public function testThrowsExceptionOnMissingData()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessageRegExp("/Missing data for instantiation(\:.*)?/");

        $creation = new PostnumberCreation($this->factory);

        $creation->setNumber(5146)
            ->setType(Postnumber::TYPE_STREET)
            ->setMunicipality("BERGEN")
            ->setMunicipalityNumber(1201);

        $output = $creation->create();
    }


}
