<?php
/**
 * Created by PhpStorm.
 * Date: 18/05/2018
 * Time: 23:13
 *
 * @author Marcus Pettersen Irgens <marcus@trollweb.no>
 */

namespace Marcuspi\Postnummer\Api\Data;

interface PostnumberInterface
{
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
