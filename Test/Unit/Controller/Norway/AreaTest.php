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

namespace Marcuspi\Postnummer\Controller\Norway;

use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Marcuspi\Postnummer\Model\Postnumber;
use Marcuspi\Postnummer\Model\Postnumber\Database;
use Marcuspi\Postnummer\Model\Postnumber\PostnumberCreation;
use Marcuspi\Postnummer\Model\Postnumber\PostNumberCreationFactory;
use Marcuspi\Postnummer\Model\Postnumber\PostnumberRepository;
use PHPUnit\Framework\TestCase;

class AreaTest extends TestCase
{
    protected $context;
    protected $postnumberRepo;
    protected $resultFactory;

    protected function setUp()
    {
        $this->context = $this->createMock(\Magento\Framework\App\Action\Context::class);
        $this->context->method("getResponse")
            ->willReturn($this->createMock(\Magento\Framework\App\Response\Http::class));

        $mockJsonResponse = $this->createMock(Json::class);

        $mockJsonResponse->method("setData")->willReturnSelf();
        $mockJsonResponse->method("setHttpResponseCode")->willReturnSelf();

        $this->resultFactory = $this->createMock(ResultFactory::class);
        $this->resultFactory->method("create")
            ->willReturn($mockJsonResponse);

        $this->postnumberRepo = $this->createMock(Postnumber\PostnumberRepository::class);
    }

    public function testExecuteWithNoParams()
    {
        $this->setRunTestInSeparateProcess(true);

        $this->context->method("getRequest")
            ->willReturn($this->createMock(\Magento\Framework\App\Request\Http::class));

        $controller = new Area(
            $this->context,
            $this->postnumberRepo,
            $this->resultFactory
        );

        $result = $controller->execute();
        $this->assertInstanceOf(ResultInterface::class, $result);
        $this->assertInstanceOf(Json::class, $result);

    }

    public function testExecute()
    {
        $this->setRunTestInSeparateProcess(true);

        $mockRequestObject = $this->createMock(\Magento\Framework\App\Request\Http::class);
        $mockRequestObject->method("getParams")->willReturn(["SOMEPLACE" => ""]);

        $this->context->method("getRequest")->willReturn($mockRequestObject);

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

        $mockNumber = $this->createMock(Postnumber::class);

        $mockCreation = $this->createMock(PostnumberCreation::class);
        $mockCreation->method("create")
            ->willReturn($mockNumber);

        $mockFactory = $this->createMock(PostNumberCreationFactory::class);
        $mockFactory->method("create")->willReturn($mockCreation);

        $repo = new PostnumberRepository(
            $db,
            $mockFactory
        );

        $controller = new Area(
            $this->context,
            $repo,
            $this->resultFactory
        );

        $this->assertInstanceOf(ResultInterface::class, $result = $controller->execute());
        $this->assertInstanceOf(Json::class, $result);

    }


    public function testExecuteWithInvalidId()
    {
        $this->setRunTestInSeparateProcess(true);
        $mockRequestObject = $this->createMock(\Magento\Framework\App\Request\Http::class);
        $mockRequestObject->method("getParams")->willReturn(["oslol" => ""]);

        $this->context->method("getRequest")->willReturn($mockRequestObject);

        $repo = $this->createMock(Postnumber\PostnumberRepository::class);
        $repo->method("get")
            ->willThrowException(NoSuchEntityException::singleField("area", "oslol"));

        $controller = new Area(
            $this->context,
            $repo,
            $this->resultFactory
        );

        $result = $controller->execute();


        $this->assertInstanceOf(ResultInterface::class, $result);
        $this->assertInstanceOf(Json::class, $result);
    }
}
