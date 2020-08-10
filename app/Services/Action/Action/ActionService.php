<?php

namespace App\Action;

use App\User;
use App\Action\IncomeService;
use App\Action\ExchangeService;
use App\Action\ForceExchangeService;
use App\Utils\OrderedLazyCollectionBuilder;
use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;



class ActionService
{
    protected IncomeService $incomeService;
    protected ExchangeService $exchangeService;
    protected ForceExchangeService $forceExchangeService;

    public function __construct(
        IncomeService $incomeService,
        ExchangeService $exchangeService,
        ForceExchangeService $forceExchangeService
    )
    {
        $this->incomeService = $incomeService;
        $this->exchangeService = $exchangeService;
        $this->forceExchangeService = $forceExchangeService;
    }

    public function isPossibleToCreateAction(
        User $user,
        string $type,
        string $currencyCode,
        string $date,
        float $amount,
        ?float $exchangeRate = null
    ): bool
    {
        switch ($type) {
            case 'income':
                return $this->incomeService->isPossibleToCreateIncome(
                    $user,
                    $currencyCode,
                    $date,
                    $amount
                );
            case 'exchange':
                return $this->exchangeService->isPossibleToCreateExchange(
                    $user,
                    $currencyCode,
                    $date,
                    $amount,
                    $exchangeRate
                );
            case 'forceExchange':
                return $this->forceExchangeService->isPossibleToCreateForceExchange(
                    $user,
                    $currencyCode,
                    $date,
                    $amount,
                    $exchangeRate
                );
        }
    }

    public function create(
        User $user,
        string $type,
        string $currencyCode,
        string $date,
        float $amount,
        ?float $exchangeRate
    )
    {
        switch ($type) {
            case 'income':
                return $this->incomeService->create(
                    $user,
                    $currencyCode,
                    $date,
                    $amount
                );
            case 'exchange':
                return $this->exchangeService->create(
                    $user,
                    $currencyCode,
                    $date,
                    $amount,
                    $exchangeRate
                );
            case 'forceExchange':
                return $this->forceExchangeService->create(
                    $user,
                    $currencyCode,
                    $date,
                    $amount,
                    $exchangeRate
                );
        }
    }

    public function delete($action)
    {
        switch ($action->type) {
            case 'income':
                $this->incomeService->delete($action);
                break;

            case 'exchange':
                $this->exchangeService->delete($action);
                break;

            case 'forceExchange':
                $this->forceExchangeService->delete($action);
                break;    
        }
    }

    public function indexFiltered(
        User $user,
        ?string $startDate,
        ?string $endDate,
        ?array $currencyCodes,
        array $types
    ): LazyCollection
    {
        $actionCollections = new Collection;
        if (in_array('income', $types)) {
            $actionCollections->push(
                $this->incomeService->indexFiltered(
                    $user,
                    $startDate,
                    $endDate,
                    $currencyCodes
                )
            );
        }
        if (in_array('exchange', $types)) {
            $actionCollections->push(
                $this->exchangeService->indexFiltered(
                    $user,
                    $startDate,
                    $endDate,
                    $currencyCodes
                )
            );
        }
        if (in_array('forceExchange', $types)) {
            $actionCollections->push(
                $this->forceExchangeService->indexFiltered(
                    $user,
                    $startDate,
                    $endDate,
                    $currencyCodes
                )
            );
        }
        $builder = new OrderedLazyCollectionBuilder(
            fn($action1, $action2) => ($action1->date->getTimestamp() > $action2->date->getTimestamp()),
            $actionCollections
        );
        return $builder->getCollection();
    }
}