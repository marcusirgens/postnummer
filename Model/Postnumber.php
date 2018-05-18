<?php
/**
 * Created by PhpStorm.
 * Date: 18/05/2018
 * Time: 23:14
 *
 * @author Marcus Pettersen Irgens <marcus@trollweb.no>
 */

namespace Marcuspi\Postnummer\Model;

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
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function getStringType(): string
    {
        switch ($this->getType()) {
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
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getTypeExplained(): string
    {
        switch ($this->getType()) {
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
}
