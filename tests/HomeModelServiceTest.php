<?php

use App\Model\Repository\TransactionRepository;
use App\Model\Services\HomeModelService;
use App\Model\TransactionModel;
use PHPUnit\Framework\TestCase;

class HomeModelServiceTest extends TestCase
{
    public function testTotalInputTransactionsShouldBeHowTheExpected(): void
    {
        // arrange
        $transactionRepository = $this->getMockBuilder(TransactionRepository::class)->getMock();

        // act
        $transactionRepository->method('getTotalInputTransactions')->willReturn([
            0 => 2,
            1 => 2
        ]);
        $homeModelService = new HomeModelService($transactionRepository);

        // assert
        $this->assertEquals(4, $homeModelService->calculateTotalInputTransactions());
    }

    public function testTotalOutputTransactionsShouldBeHowTheExpected(): void
    {
        // arrange
        $transactionRepository = $this->getMockBuilder(TransactionRepository::class)->getMock();

        // act
        $transactionRepository->method('getTotalOutputTransactions')->willReturn([
            0 => 3,
            1 => 5
        ]);
        $homeModelService = new HomeModelService($transactionRepository);

        // assert
        $this->assertEquals(8, $homeModelService->calculateTotalOutputTransactions());

    }

    public function testShouldReturnAnArrayOfTransactionModelObjects(): void
    {
        // arrange
        $transactionRepository = $this->getMockBuilder(TransactionRepository::class)->getMock();

        // act
        $transactionRepository->method('getTransactions')->willReturn([
            0 => [
                'description' => 'boneco do naruto fazendo rasengan',
                'price' => 2,
                'category' => 'decoração',
                'date' => '12/12/2022',
                'type' => TransactionModel::TYPE_OUTPUT
            ],
            1 => [
                'description' => 'patinho de borracha com oculos aviador',
                'price' => 6,
                'category' => 'decoração',
                'date' => '12/12/2022',
                'type' => TransactionModel::TYPE_OUTPUT
            ],
            2 => [
                'description' => 'camiseta',
                'price' => 4,
                'category' => 'vestuario',
                'date' => '12/12/2022',
                'type' => TransactionModel::TYPE_INPUT
            ]
        ]);
        $homeModelService = new HomeModelService($transactionRepository);

        // assert
        $this->assertContainsOnlyInstancesOf(TransactionModel::class, $homeModelService->convertTransactionsFromArrayToObject());
    }

    public function testShouldReturnAHomeModel(): void
    {
        // arrange
        $transactionRepository = $this->getMockBuilder(TransactionRepository::class)->getMock();

        // act
        $transactionRepository->method('getTotalInputTransactions')
                                ->willReturn([
                                    0 => 2,
                                    1 => 2
                                ]);
        $transactionRepository->method('getTotalOutputTransactions')
                                ->willReturn([
                                    0 => 3,
                                    1 => 5
                                ]);
        $transactionRepository->method('getTransactions')->willReturn([
            0 => [
                'description' => 'boneco do naruto fazendo rasengan',
                'price' => 2,
                'category' => 'decoração',
                'date' => '12/12/2022',
                'type' => TransactionModel::TYPE_OUTPUT
            ],
            1 => [
                'description' => 'patinho de borracha com oculos aviador',
                'price' => 6,
                'category' => 'decoração',
                'date' => '12/12/2022',
                'type' => TransactionModel::TYPE_OUTPUT
            ],
            2 => [
                'description' => 'camiseta',
                'price' => 4,
                'category' => 'vestuario',
                'date' => '12/12/2022',
                'type' => TransactionModel::TYPE_INPUT
            ]
        ]);
        $homeModelService = new HomeModelService($transactionRepository);
        $homeModel = $homeModelService->getHomeModel();

        // assert
        $this->assertEquals(4, $homeModel->totalInputTransactions);
        $this->assertEquals(8, $homeModel->totalOutputTransactions);
        $this->assertEquals(-4, $homeModel->getDiffInputOutputTransactions());
        $this->assertContainsOnlyInstancesOf(TransactionModel::class, $homeModel->getTransactions());
    }
}
