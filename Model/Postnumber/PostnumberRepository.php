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

/**
 * Created by PhpStorm.
 * Date: 18/05/2018
 * Time: 23:16
 *
 * @author Marcus Pettersen Irgens <marcus.pettersen.irgens@gmail.com>
 */

namespace Marcuspi\Postnummer\Model\Postnumber;

use Magento\Framework\Exception\NoSuchEntityException;
use Marcuspi\Postnummer\Api\Data\PostnumberInterface;
use Marcuspi\Postnummer\Api\Data\PostnumberRepositoryInterface;

/**
 * Class PostnumberRepository
 * @package Marcuspi\Postnummer\Model\Postnumber
 */
class PostnumberRepository implements PostnumberRepositoryInterface
{

    /**
     * @var Database
     */
    private $database;
    /**
     * @var PostNumberCreationFactory
     */
    private $creationFactory;

    /**
     * PostnumberRepository constructor.
     *
     * @param Database $database
     * @param PostNumberCreationFactory $creationFactory
     */
    public function __construct(
        Database $database,
        PostNumberCreationFactory $creationFactory
    ) {
        $this->database = $database;
        $this->creationFactory = $creationFactory;
    }

    private function typeCast(string $type): int
    {
        switch ($type) {
            case "B":
                return PostnumberInterface::TYPE_COMBINED;
            case "F":
                return PostnumberInterface::TYPE_MULTIPLE;
            case "G":
                return PostnumberInterface::TYPE_STREET;
            case "P":
                return PostnumberInterface::TYPE_POSTBOX;
            case "S":
                return PostnumberInterface::TYPE_SERVICE;
            default:
                throw new \Exception("Invalid post number type {$type}");
        }
    }

    /**
     * @param int $number
     * @return PostnumberInterface
     * @throws NoSuchEntityException
     * @throws \Exception
     */
    public function get(int $number): PostnumberInterface
    {
        foreach ($this->database as $item) {
            if ($item["number"] == str_pad($number, 4, "0", STR_PAD_LEFT)) {
                $creation = $this->creationFactory->create();

                $creation->setNumber($item["number"])
                    ->setArea($item["area"])
                    ->setMunicipalityNumber($item["municipalityNum"])
                    ->setMunicipality($item["municipality"])
                    ->setType($this->typeCast($item["type"]));
                return $creation->create();
            }
        }

        throw NoSuchEntityException::singleField("postnumber", $number);
    }

    /**
     * Finds post numbers for a place
     *
     * @param string $place
     * @return \Generator<PostnumberInterface>
     * @throws \Exception If the data in the db file is corrupt or unsupported.
     */
    public function getArea(string $place)
    {
        foreach ($this->database as $item) {
            if (mb_strtolower($item["area"]) == mb_strtolower(trim($place))) {
                $creation = $this->creationFactory->create();

                $creation->setNumber($item["number"])
                    ->setArea($item["area"])
                    ->setMunicipalityNumber($item["municipalityNum"])
                    ->setMunicipality($item["municipality"])
                    ->setType($this->typeCast($item["type"]));
                yield $creation->create();
            }
        }
    }
}
