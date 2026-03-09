<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locations = [
            [
                'name' => 'Radiance Dentistry - Main Branch',
                'address' => '123 MG Road, Andheri West',
                'city' => 'Mumbai',
                'state' => 'Maharashtra',
                'pincode' => '400058',
                'phone' => '+91 22 2674 5678',
                'email' => 'main@radiance.com',
                'working_hours' => "Mon-Fri: 9:00 AM - 8:00 PM\nSat: 9:00 AM - 6:00 PM\nSun: Closed",
                'is_active' => true,
            ],
            [
                'name' => 'Radiance Dentistry - Bandra',
                'address' => '456 Hill Road, Bandra West',
                'city' => 'Mumbai',
                'state' => 'Maharashtra',
                'pincode' => '400050',
                'phone' => '+91 22 2640 1234',
                'email' => 'bandra@radiance.com',
                'working_hours' => "Mon-Sat: 10:00 AM - 7:00 PM\nSun: 10:00 AM - 2:00 PM",
                'is_active' => true,
            ],
        ];

        foreach ($locations as $location) {
            Location::create($location);
        }
    }
}
