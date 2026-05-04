<?php

namespace App\Services;

use App\Models\AttendanceSetting;

class LocationService
{
    public function distanceInMeters(
        ?float $latitude,
        ?float $longitude,
        ?float $officeLatitude,
        ?float $officeLongitude
    ): ?float {
        if ($latitude === null || $longitude === null || $officeLatitude === null || $officeLongitude === null) {
            return null;
        }

        $earthRadius = 6371000;
        $latFrom = deg2rad($latitude);
        $lonFrom = deg2rad($longitude);
        $latTo = deg2rad($officeLatitude);
        $lonTo = deg2rad($officeLongitude);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(
            pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)
        ));

        return round($angle * $earthRadius, 2);
    }

    public function validate(?float $latitude, ?float $longitude, AttendanceSetting $setting): array
    {
        $distance = $this->distanceInMeters(
            $latitude,
            $longitude,
            $setting->office_latitude,
            $setting->office_longitude
        );

        if (! $setting->validate_location) {
            return [
                'allowed' => true,
                'distance_meter' => $distance,
                'message' => null,
            ];
        }

        if ($latitude === null || $longitude === null) {
            return [
                'allowed' => false,
                'distance_meter' => null,
                'message' => 'Latitude dan longitude wajib diisi saat validasi lokasi aktif.',
            ];
        }

        if ($setting->office_latitude === null || $setting->office_longitude === null) {
            return [
                'allowed' => false,
                'distance_meter' => null,
                'message' => 'Lokasi kantor/desa belum diatur.',
            ];
        }

        if ($distance !== null && $distance > $setting->allowed_radius_meter) {
            return [
                'allowed' => false,
                'distance_meter' => $distance,
                'message' => 'Lokasi berada di luar radius absensi.',
            ];
        }

        return [
            'allowed' => true,
            'distance_meter' => $distance,
            'message' => null,
        ];
    }
}
