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

namespace Marcuspi\Postnummer\Api\Data;

/**
 * Interface for interaction with a Postnumber
 *
 * @api
 */
interface PostnumberInterface
{
    /** @todo move these to the model class */
    const TYPE_COMBINED = 0;
    const TYPE_MULTIPLE = 1;
    const TYPE_STREET = 2;
    const TYPE_POSTBOX = 3;
    const TYPE_SERVICE = 4;

    /**
     * Returns the post number
     *
     * @return string
     */
    public function getNumber(): string;

    /**
     * Returns the post area
     *
     * @return string
     */
    public function getArea(): string;

    /**
     * Returns the municipality name
     *
     * @return string
     */
    public function getMunicipality(): string;

    /**
     * Returns the municipality number
     *
     * @return string
     */
    public function getMunicipalityNumber(): string;

    /**
     * Returns the post number type
     *
     * @return int
     */
    public function getType(): int;

    /**
     * Returns the post number type as a string
     *
     * @return string
     */
    public function getStringType(): string;

    /**
     * Returns an explanation for the post number type
     *
     * @return string
     */
    public function getTypeExplained(): string;

    /**
     * @return array
     */
    public function toArray(): array;
}
