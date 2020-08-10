<?php

namespace App\Utils;

use Illuminate\Support\LazyCollection;
use Illuminate\Support\Collection; 

class OrderedLazyCollectionBuilder
{
    protected $collectionGenerator;

    public function __construct($comparsionCallback, Collection $orderedLazyCollections)
    {
        $this->collectionGenerator = function () use ($orderedLazyCollections, $comparsionCallback){
            $lazyCollectionsIterators = 
                $orderedLazyCollections
                    ->map(fn($collection) => $collection->getIterator());
            while (
                $greatestCurrentIterator = 
                    $lazyCollectionsIterators
                        ->first(fn($iterator) => $iterator->valid())
            ){
                foreach ($lazyCollectionsIterators as $iterator) {
                    if (!$iterator->valid()) continue;
                    if ($comparsionCallback(
                        $iterator->current(),
                        $greatestCurrentIterator->current()
                    ))
                        $greatestCurrentIterator = $iterator;
                }
                yield $greatestCurrentIterator->current();
                $greatestCurrentIterator->next();
            }
        };
    }

    public function getCollection(): LazyCollection
    {
        return LazyCollection::make($this->collectionGenerator);
    }
}