<?php

namespace App\Repositories;

use App\Models\Orders\Orders;
use App\Models\Vehicles\Car;
use App\Services\Foreign\GeoLocation;

class OrdersRepository
{
    public function distanceFromOrderStartPointToCurrentCarPoint(Orders $order, Car $car)
    {
        $geoLocationService = new GeoLocation;

        if (!$order->plannedRoads->first()->latitude || !$order->plannedRoads->first()->longitude || !$car->latitude || !$car->longitude) {
            return 0;
        }

        $distanceMeters = $geoLocationService->getDistance(
            $order->plannedRoads->first()->latitude,
            $order->plannedRoads->first()->longitude,
            $car->latitude,
            $car->longitude
        );

        $distanceKilometers = round($distanceMeters / 1000, 2);

        return $distanceKilometers;
    }

}