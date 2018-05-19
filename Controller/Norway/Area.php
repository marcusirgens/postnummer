<?php

namespace Marcuspi\Postnummer\Controller\Norway;

use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Marcuspi\Postnummer\Api\Data\PostnumberRepositoryInterface;

/**
 * View controller for the navimage/creditmemo/list endpoint
 *
 * Simply lists all the creditmemos that have not been picked up by the ERP supplier.
 *
 * @see \Trollweb\Navimage\Controller\Creditmemo\ListAction\Interceptor
 * @package Marcuspi\Postnummer
 */
class Area extends \Magento\Framework\App\Action\Action
{

    /**
     * @var PostnumberRepositoryInterface
     */
    private $postnumberRepository;

    /**
     * Endpoint for /postnummer/norway/nummer/[0-9]{4}
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
                    "message" => "Please provide a valid post area (a-zæøå, case insensitive)"
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
