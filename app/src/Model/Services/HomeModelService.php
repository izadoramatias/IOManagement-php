<?php

namespace App\Model\Services;

use App\Model\HomeModel;
use App\Model\TransactionModel;

class HomeModelService
{
    public function __construct(
        public HomeService $transactionRepository
    ) {}

    public function getHomeModel(): HomeModel
    {
        $homeModel = new HomeModel();

        $homeModel->totalInputTransactions = $this->calculateTotalInputTransactions();
        $homeModel->totalOutputTransactions = $this->calculateTotalOutputTransactions();
        $homeModel->addTransactions( $this->convertTransactionsFromArrayToObject());

        return $homeModel;
    }

    public function calculateTotalInputTransactions(): float
    {
        $inputsArray = $this->transactionRepository->getTotalInputTransactions();

        $total = 0;
        foreach ($inputsArray as $price) {
            $total += $price;
        }

        return $total;
    }

    public function calculateTotalOutputTransactions(): float
    {
        $outputsArray = $this->transactionRepository->getTotalOutputTransactions();

        $total = 0;
        foreach ($outputsArray as $price) {
            $total += $price;
        }

        return $total;
    }

    public function convertTransactionsFromArrayToObject(): array
    {
        $arrayTransactions = [];
        $transactions = $this->transactionRepository->getTransactions();
        foreach ($transactions as $transaction) {
            extract($transaction, EXTR_OVERWRITE);

            $transactionToObject = new TransactionModel($description, $price, $category, (new \DateTimeImmutable)::createFromFormat('d/m/Y', $date), $type);
            $arrayTransactions[] = $transactionToObject;
        }

        return $arrayTransactions;
    }
}

//class RepositoryFake implements HomeService{
//
//    public function getTotalInputTransactions(): array
//    {
//        return [
//            new TransactionModel(price: 2),
//            new TransactionModel(price: 2)
//        ];
//    }
//
//    public function getTotalOutputTransactions(): array
//    {
//        return [
//            new TransactionModel(price: 3, type: TransactionModel::TYPE_OUTPUT),
//            new TransactionModel(price: 5, type: TransactionModel::TYPE_OUTPUT),
//        ];
//    }
//
//    public function getTransactions(): array
//    {
//        return [
//            new TransactionModel(price: 2),
//            new TransactionModel(price: 2),
//            new TransactionModel(price: 3, type: TransactionModel::TYPE_OUTPUT),
//            new TransactionModel(price: 5, type: TransactionModel::TYPE_OUTPUT),
//        ];
//    }
//}
//
//$r = new RepositoryFake();
//
//$s = new TransactionServices($r);
//
//$h = $s->getHomeModel();
