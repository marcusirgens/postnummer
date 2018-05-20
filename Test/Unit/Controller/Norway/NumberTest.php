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
use PHPUnit\Framework\TestCase;

class NumberTest extends TestCase
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

        $controller = new Number(
            $this->context,
            $this->postnumberRepo,
            $this->resultFactory
        );

        $this->assertInstanceOf(ResultInterface::class, $controller->execute());
    }

    public function testExecute()
    {
        $this->setRunTestInSeparateProcess(true);

        $mockRequestObject = $this->createMock(\Magento\Framework\App\Request\Http::class);
        $mockRequestObject->method("getParams")->willReturn(["5146" => ""]);

        $this->context->method("getRequest")->willReturn($mockRequestObject);

        $controller = new Number(
            $this->context,
            $this->postnumberRepo,
            $this->resultFactory
        );

        $this->assertInstanceOf(ResultInterface::class, $controller->execute());
    }


    public function testExecuteWithInvalidId()
    {
        $this->setRunTestInSeparateProcess(true);
        $mockRequestObject = $this->createMock(\Magento\Framework\App\Request\Http::class);
        $mockRequestObject->method("getParams")->willReturn(["5140" => ""]);

        $this->context->method("getRequest")->willReturn($mockRequestObject);

        $repo = $this->createMock(Postnumber\PostnumberRepository::class);
        $repo->method("get")
            ->willThrowException(NoSuchEntityException::singleField("postnumber", "51406"));

        $controller = new Number(
            $this->context,
            $repo,
            $this->resultFactory
        );

        $result = $controller->execute();



        $this->assertInstanceOf(ResultInterface::class, $result);
    }
}
