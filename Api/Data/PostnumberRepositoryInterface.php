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
 * Interface for interaction with a PostnumberRepository.
 *
 * @package Marcuspi\Postnummer\Api\Data
 * @api
 */
interface PostnumberRepositoryInterface
{
    /**
     * Finds a post number.
     *
     * @param int $number
     * @return PostnumberInterface
     */
    public function get(int $number): PostnumberInterface;


    /**
     * Finds post numbers for a place
     *
     * @param string $place
     * @return \Generator<PostnumberInterface>
     */
    public function getArea(string $place);
}
