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
