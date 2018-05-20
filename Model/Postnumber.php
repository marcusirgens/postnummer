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
 * Date: 18/05/2018
 * Time: 23:14
 *
 * @author Marcus Pettersen Irgens <marcus.pettersen.irgens@gmail.com>
 */

namespace Marcuspi\Postnummer\Model;

use Exception;
use Marcuspi\Postnummer\Api\Data\PostnumberInterface;

class Postnumber implements PostnumberInterface
{
    /**
     * @var int
     */
    private $number;
    /**
     * @var string
     */
    private $area;
    /**
     * @var int
     */
    private $type;
    /**
     * @var string
     */
    private $municipality;
    /**
     * @var int
     */
    private $municipalityNum;

    /**
     * Creates a new post number
     *
     * @param int $number
     * @param string $area
     * @param int $type
     * @param string $municipality
     * @param int $municipalityNum
     */
    public function __construct(
        int $number,
        string $area,
        int $type,
        string $municipality,
        int $municipalityNum
    ) {
        $this->number = $number;
        $this->area = $area;
        $this->type = $type;
        $this->municipality = $municipality;
        $this->municipalityNum = $municipalityNum;
    }

    /**
     * {@inheritdoc}
     */
    public function getNumber(): string
    {
        return str_pad($this->number, 4, "0", STR_PAD_LEFT);
    }

    /**
     * {@inheritdoc}
     */
    public function getArea(): string
    {
        return $this->area;
    }

    /**
     * {@inheritdoc}
     */
    public function getType(): int
    {
        if (!in_array($this->type, [
            self::TYPE_COMBINED,
            self::TYPE_MULTIPLE,
            self::TYPE_STREET,
            self::TYPE_SERVICE,
            self::TYPE_POSTBOX
        ])) {
            throw new Exception("Invalid type for post number {$this->getNumber()}");
        }

        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function getStringType(): string
    {
        switch ($this->type) {
            case self::TYPE_COMBINED:
                return "B";
            case self::TYPE_MULTIPLE:
                return "F";
            case self::TYPE_STREET:
                return "G";
            case self::TYPE_POSTBOX:
                return "P";
            case self::TYPE_SERVICE:
                return "S";
            default:
                throw new Exception("Invalid type for post number {$this->getNumber()}");
                break;
        }
    }

    /**
     * {@inheritdoc}
     * @throws Exception
     */
    public function getTypeExplained(): string
    {
        switch ($this->type) {
            case self::TYPE_COMBINED:
                return "Both street addresses and post boxes";
            case self::TYPE_MULTIPLE:
                return "Used for several purposes";
            case self::TYPE_STREET:
                return "Street addresses (and place addresses), typically \"green post boxes\"";
            case self::TYPE_POSTBOX:
                return "Post boxes";
            case self::TYPE_SERVICE:
                return "Service post number (these numbers are not used for post addresses)";
            default:
                throw new Exception("Invalid type for post number {$this->getNumber()}");
                break;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getMunicipality(): string
    {
        return $this->municipality;
    }

    /**
     * {@inheritdoc}
     */
    public function getMunicipalityNumber(): string
    {
        return str_pad($this->municipalityNum, 4, "0", STR_PAD_LEFT);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            "number" => $this->getNumber(),
            "area" => $this->getArea(),
            "type" => $this->getStringType(),
            "typeExplained" => $this->getTypeExplained(),
            "municipality" => $this->getMunicipality(),
            "municipalityNumber" => $this->getMunicipalityNumber()
        ];
    }
}
