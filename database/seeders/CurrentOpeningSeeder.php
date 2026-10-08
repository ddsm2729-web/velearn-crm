<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\CurrentOpening;
use App\Models\Company;
use Carbon\Carbon;

class CurrentOpeningSeeder extends Seeder
{
    public function run(): void
    {
        $companies = Company::all();

        if ($companies->count() == 0) {
            $company = Company::create([
                'company_name' => 'Tech Innovations Inc',
                'company_mail_id' => 'hr@techinnovations.com',
                'hr_name' => 'Jane Doe',
                'hr_contact_number' => '9876543210',
                'hr_email_id' => 'janedoe@techinnovations.com',
                'city_location' => 'Chennai',
                'industry_sector' => 'IT',
                'company_website' => 'https://techinnovations.com',
                'linkedin_url' => 'https://linkedin.com/company/techinnovations',
                'notes' => 'Fallback company created by seeder',
                'source_id' => \App\Models\LeadSource::first()?->id ?? 1,
            ]);
            $companies = collect([$company]);
        }

        $roles = ['Software Engineer', 'Data Analyst', 'Frontend Developer', 'Backend Developer', 'Full Stack Developer', 'DevOps Engineer'];
        $locations = ['Chennai', 'Bangalore', 'Hyderabad', 'Mumbai', 'Pune', 'Remote'];
        $statuses = ['Active', 'Active', 'Active', 'Hold', 'Filled', 'Closed', 'Old'];
        
        foreach ($roles as $role) {
            foreach (range(1, 2) as $index) {
                $company = $companies->random();
                
                CurrentOpening::create([
                    'company_id' => $company->id,
                    'job_role' => $role,
                    'company_mail_id' => $company->company_mail_id,
                    'hr_name' => $company->hr_name,
                    'hr_contact_number' => $company->hr_contact_number,
                    'hr_email_id' => $company->hr_email_id,
                    'job_location' => $locations[array_rand($locations)],
                    'skills_required' => 'Java, Spring Boot, MySQL',
                    'minimum_qualification' => 'B.Tech / BCA',
                    'experience_required' => ['Fresher', '0-1 yr', '1-3 yrs', '3+ yrs'][array_rand(['Fresher', '0-1 yr', '1-3 yrs', '3+ yrs'])],
                    'ctc_salary' => rand(3, 12) . ' LPA',
                    'number_of_openings' => rand(1, 10),
                    'application_deadline' => Carbon::now()->addDays(rand(10, 30)),
                    'interview_mode' => ['Online', 'Offline'][rand(0, 1)],
                    'interview_process' => 'Aptitude -> Tech -> HR',
                    'bond_service_agmt' => '1 yr',
                    'opening_status' => $statuses[array_rand($statuses)],
                    'notes' => 'Looking for immediate joiners.',
                ]);
            }
        }
    }
}
