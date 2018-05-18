<?php
/**
 * Created by PhpStorm.
 * Date: 18/05/2018
 * Time: 23:16
 *
 * @author Marcus Pettersen Irgens <marcus@trollweb.no>
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

    /**
     * @param int $number
     * @return PostnumberInterface
     * @throws NoSuchEntityException
     * @throws \Exception
     */
    public function get(int $number): PostnumberInterface
    {
        foreach ($this->database as $item) {
            if ($item[0] == str_pad($number, 4, "0", STR_PAD_LEFT)) {
                $creation = $this->creationFactory->create();

                $type = null;

                switch ($item[4]) {
                    case "B":
                        $type = PostnumberInterface::TYPE_COMBINED;
                        break;
                    case "F":
                        $type = PostnumberInterface::TYPE_MULTIPLE;
                        break;
                    case "G":
                        $type = PostnumberInterface::TYPE_STREET;
                        break;
                    case "P":
                        $type = PostnumberInterface::TYPE_POSTBOX;
                        break;
                    case "S":
                        $type = PostnumberInterface::TYPE_SERVICE;
                        break;
                }

                $creation->setNumber($item[0])
                    ->setArea($item[1])
                    ->setMunicipalityNumber($item[2])
                    ->setMunicipality($item[3])
                    ->setType($type);
                return $creation->create();
            }
        }

        throw NoSuchEntityException::singleField("postnumber", $number);
    }
}
