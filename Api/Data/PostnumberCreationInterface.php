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
 * Allows creation of Postnumber objects.
 *
 * @internal Used in the repository class.
 */
interface PostnumberCreationInterface
{
    /**
     * Set the post number
     *
     * @param int $number
     * @return PostnumberCreationInterface
     */
    public function setNumber(int $number): PostnumberCreationInterface;

    /**
     * Set the post area
     *
     * @param string $area
     * @return PostnumberCreationInterface
     */
    public function setArea(string $area): PostnumberCreationInterface;

    /**
     * Set the type
     *
     * @param int $type
     * @return PostnumberCreationInterface
     */
    public function setType(int $type): PostnumberCreationInterface;

    /**
     * Set the municipality name
     *
     * @param string $municipality
     * @return PostnumberCreationInterface
     */
    public function setMunicipality(string $municipality): PostnumberCreationInterface;

    /**
     * Set the municipality number
     *
     * @param string $municipalityNum
     * @return PostnumberCreationInterface
     */
    public function setMunicipalityNumber(string $municipalityNum): PostnumberCreationInterface;

    /**
     * Creates and returns the Post Number object
     *
     * @return PostnumberInterface
     */
    public function create(): PostnumberInterface;
}
