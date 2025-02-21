<?php

namespace Hichamagm\IzagentShared\Utils;

use Illuminate\Support\Facades\Log;

class ApiAggregator {


    /**
     * Aggregate many items based on the foreign key.
     *
     * @param \Illuminate\Support\Collection|\Illuminate\Contracts\Pagination\Paginator $items
     * @param string $foreignKey
     * @param \Hichamagm\IbeautyfiShared\Services\ApiModelBase $modelInstance
     * @param string $relationKey
     * @param string $externalKey
     * @return \Illuminate\Support\Collection|\Illuminate\Contracts\Pagination\Paginator
     */
    public static function aggregateOneToMany($items, $foreignKey, $modelInstance, $relationKey, $externalKey = "id")
    {
        // Fetch the unique foreign key values from the items
        $ids = $items->pluck($foreignKey)->unique()->toArray();

        Log::info("runnnings aggregation");

        // Call the modelInstance's getMany method to fetch data in bulk
        $response = $modelInstance->getMany([$externalKey => $ids]);

        if ($response->successful() && isset($response->json()["data"])) {
            $externalData = collect($response->json()["data"]);

            $items->transform(function ($item) use ($externalData, $foreignKey, $relationKey, $externalKey) {
                $item->$relationKey = $externalData->firstWhere($externalKey,  $item->$foreignKey);
                return $item;
            });
        }

        return $items;
    }


    /**
     * Aggregate one item based on the foreign key.
     *
     * @param \Illuminate\Database\Eloquent\Model $item
     * @param string $foreignKey
     * @param \Hichamagm\IbeautyfiShared\Services\ApiModelBase $modelInstance
     * @param string $relationKey
     * @return \Illuminate\Database\Eloquent\Model
     */
    public static function aggregateOneToOne($item, $foreignKey, $modelInstance, $relationKey)
    {
        // Fetch a single record based on the foreign key
        $response = $modelInstance->getOne($item->$foreignKey);

        if ($response->successful()) {
            // Convert the external data to a collection
            $externalData = collect($response->json());

            // Attach the first matching external data to the item
            $item->$relationKey = $externalData;
        }

        return $item;
    }

    /**
     * Aggregate one item based on the foreign key.
     *
     * @param \Illuminate\Database\Eloquent\Model $item
     * @param array $filters
     * @param \Hichamagm\IbeautyfiShared\Services\ApiModelBase $modelInstance
     * @param string $relationKey
     * @param string $externalKey
     * @return \Illuminate\Database\Eloquent\Model
     */
    public static function aggregateManyToOne($item, $filters, $modelInstance, $relationKey)
    {
        // Fetch a single record based on the foreign key
        $response = $modelInstance->getMany($filters);

        if ($response->successful() && isset($response->json()["data"])) {
            // Convert the external data to a collection
            $externalData = collect($response->json()["data"]);

            // Attach the first matching external data to the item
            $item->$relationKey = $externalData;
        }

        return $item;
    }
}