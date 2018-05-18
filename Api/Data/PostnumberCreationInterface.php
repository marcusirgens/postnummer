<?php
/**
 * Created by PhpStorm.
 * Date: 18/05/2018
 * Time: 23:13
 *
 * @author Marcus Pettersen Irgens <marcus@trollweb.no>
 */

namespace Marcuspi\Postnummer\Api\Data;

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
