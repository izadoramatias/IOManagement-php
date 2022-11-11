<?php

namespace App\Controller;

use App\Model\Repository\TransactionsFindAll;

class Transaction
{
    public static function processRequest()
    {

        $transactionsRepository = new TransactionsFindAll();
        $all = $transactionsRepository::findAll();

        return $all;
    }
}