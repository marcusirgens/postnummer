<?php
/**
 * Created by PhpStorm.
 * Date: 19/05/2018
 * Time: 00:44
 *
 * @author Marcus Pettersen Irgens <marcus@trollweb.no>
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
