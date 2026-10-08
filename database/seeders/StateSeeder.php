<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $name = [
            "Andaman and Nicobar Islands",
	        "Andhra Pradesh",
	        "Arunachal Pradesh",
	        "Assam",
	        "Bihar",
	        "Chandigarh",
	        "Chhattisgarh",
	        "Dadra and Nagar Haveli",
	        "Daman and Diu",
	        "Delhi",
	        "Goa",
	        "Gujarat",
	        "Haryana",
	        "Himachal Pradesh",
	        "Jammu and Kashmir",
	        "Jharkhand",
	        "Karnataka",
	        "Kerala",
	        "Ladakh",
	        "Lakshadweep",
	        "Madhya Pradesh",
	        "Maharashtra",
	        "Manipur",
	        "Meghalaya",
	        "Mizoram",
	        "Nagaland",
	        "Odisha",
	        "Puducherry",
	        "Punjab",
	        "Rajasthan",
	        "Sikkim",
	        "Tamil Nadu",
	        "Telangana",
	        "Tripura",
	        "Uttar Pradesh",
	        "Uttarakhand",
	        "West Bengal"         
        ];

        foreach ($name as $n) {
            \App\Models\State::create(['state_name' => $n]);
        }
    }
}
