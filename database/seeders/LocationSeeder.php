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
                'name' => 'RADIANCE DENTISTRY Dental Clinic',
                'address' => '15 , Shiv shakti nagar, Iskcon Rd, behind kasana sweets, Jaipur, Rajasthan 302020                                 ',
                'city' => 'Jaipur',
                'state' => 'Rajasthan',
                'pincode' => '302020',
                'phone' => '8296504553',
                'email' => 'hi@radiancedentistryclinic.com',
                'working_hours' => "Mon-Fri: 9:00 AM - 8:00 PM\nSat: 9:00 AM - 6:00 PM\nSun: Closed",
                'is_active' => true,
            ]
        ];

        foreach ($locations as $location) {
            Location::create($location);
        }
    }
}
