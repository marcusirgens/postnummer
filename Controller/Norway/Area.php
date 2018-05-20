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

namespace Marcuspi\Postnummer\Controller\Norway;

use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Marcuspi\Postnummer\Api\Data\PostnumberRepositoryInterface;

/**
 * View controller for the /postnummer/norway/area/{[a-zæøåÆØÅ]+} endpoint
 *
 * Simply lists all the creditmemos that have not been picked up by the ERP supplier.
 *
 * @see \Marcuspi\Postnummer\Controller\Norway\Area\Interceptor
 * @package Marcuspi\Postnummer
 */
class Area extends \Magento\Framework\App\Action\Action
{

    /**
     * @var PostnumberRepositoryInterface
     */
    private $postnumberRepository;

    /**
     * Endpoint for /postnummer/norway/area/{[a-zæøåÆØÅ]+}
     *
     * @param \Magento\Framework\App\Action\Context $context
     * @param PostnumberRepositoryInterface $postnumberRepository
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        PostnumberRepositoryInterface $postnumberRepository,
        ResultFactory $resultFactory
    ) {
        parent::__construct($context);
        $this->postnumberRepository = $postnumberRepository;
        $this->resultFactory = $resultFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        /** @var Json $response */
        $response = $this->resultFactory->create(ResultFactory::TYPE_JSON);


        // the first one that's a string
        $area = (new \Illuminate\Support\Collection($this->getRequest()->getParams()))
            ->keys()
            ->map(function($value){
                $value = strval($value);
                return urldecode($value);
            })
            ->filter(function ($value) {
                return preg_match("/^[a-zøæåÆØÅ\-\ ]+$/i", $value);
            })
            ->first();

        if (is_null($area)) {
            return $this->resultFactory
                ->create(ResultFactory::TYPE_JSON)
                ->setHttpResponseCode(400)
                ->setData([
                    "status" => "error",
                    "message" => "Please provide a valid post area (a-z, æøå, spaces allowed, case insensitive)"
                ]);
        }

        $num = collect($this->postnumberRepository->getArea($area));
        if(count($num) == 0) {
            return $this->resultFactory
                ->create(ResultFactory::TYPE_JSON)
                ->setHttpResponseCode(404)
                ->setData([
                    "status" => "error",
                    "message" => "That post area could not be found",
                ]);
        }
        $response->setData([
            "status" => "OK",
            "data" => [
                $num
                    ->mapWithKeys(function ($postnumber) {
                        return [
                            $postnumber->getNumber() => collect($postnumber->toArray())
                        ];
                    })
                    ->toArray()
            ]
        ]);

        return $response;
    }
}
