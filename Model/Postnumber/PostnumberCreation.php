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
 * Date: 19/05/2018
 * Time: 00:44
 *
 * @author Marcus Pettersen Irgens <marcus.pettersen.irgens@gmail.com>
 */

namespace Marcuspi\Postnummer\Model\Postnumber;

use Marcuspi\Postnummer\Api\Data\PostnumberCreationInterface;
use Marcuspi\Postnummer\Api\Data\PostnumberInterface;
use Marcuspi\Postnummer\Model\PostnumberFactory;

/**
 * Class PostnumberCreation
 * @package Marcuspi\Postnummer\Model\Postnumber
 */
class PostnumberCreation implements PostnumberCreationInterface
{
    /**
     * @var int
     */
    protected $number;

    /**
     * @var string
     */
    protected $area;

    /**
     * @var int
     */
    protected $type;

    /**
     * @var string
     */
    protected $municipality;

    /**
     * @var int
     */
    protected $municipalityNum;

    /**
     * @var PostnumberFactory
     */
    private $postnumberFactory;

    public function __construct(
        PostnumberFactory $postnumberFactory
    ) {
        $this->postnumberFactory = $postnumberFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function setNumber(int $number): PostnumberCreationInterface
    {
        $this->number = $number;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setArea(string $area): PostnumberCreationInterface
    {
        $this->area = $area;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setType(int $type): PostnumberCreationInterface
    {
        $this->type = $type;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setMunicipality(string $municipality): PostnumberCreationInterface
    {
        $this->municipality = $municipality;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setMunicipalityNumber(string $municipalityNum): PostnumberCreationInterface
    {
        $this->municipalityNum = $municipalityNum;
        return $this;
    }

    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    public function create(): PostnumberInterface
    {
        if (!$this->validate()) {
            throw new \Exception("Missing data for instantiation");
        }

        return $this->postnumberFactory->create([
            "number" => $this->getNumber(),
            "area" => $this->getArea(),
            "type" => $this->getType(),
            "municipality" => $this->getMunicipality(),
            "municipalityNum" => $this->getMunicipalityNumber()
        ]);
    }

    /**
     * @return int
     */
    public function getNumber(): int
    {
        return $this->number;
    }

    /**
     * @return string
     */
    public function getArea(): string
    {
        return $this->area;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getMunicipality(): string
    {
        return $this->municipality;
    }

    /**
     * @return int
     */
    public function getMunicipalityNumber(): int
    {
        return $this->municipalityNum;
    }

    /**
     * Checks whether the object can be instantiated.
     *
     * @return bool
     */
    protected function validate(): bool
    {
        return (
            !is_null($this->number)
            && !is_null($this->area)
            && !is_null($this->municipality)
            && !is_null($this->municipalityNum)
            && !is_null($this->type)
        );
    }
}
