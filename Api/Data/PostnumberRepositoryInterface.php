<?php
/**
 * Created by PhpStorm.
 * Date: 18/05/2018
 * Time: 23:17
 *
 * @author Marcus Pettersen Irgens <marcus@trollweb.no>
 */

namespace Marcuspi\Postnummer\Api\Data;

use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Interface PostnumberRepositoryInterface
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
