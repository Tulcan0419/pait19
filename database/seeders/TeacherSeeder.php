<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Teacher;
use Illuminate\Support\Facades\Hash;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teachers = [
            [
                'name' => 'Dr. Carlos Mendoza',
                'email' => 'carlos.mendoza@istpet.edu.ec',
                'password' => Hash::make('password'),
                'teacher_code' => 'T001',
            ],
            [
                'name' => 'Ing. María González',
                'email' => 'maria.gonzalez@istpet.edu.ec',
                'password' => Hash::make('password'),
                'teacher_code' => 'T002',
            ],
            [
                'name' => 'Lic. Roberto Silva',
                'email' => 'roberto.silva@istpet.edu.ec',
                'password' => Hash::make('password'),
                'teacher_code' => 'T003',
            ],
            [
                'name' => 'MSc. Ana Torres',
                'email' => 'ana.torres@istpet.edu.ec',
                'password' => Hash::make('password'),
                'teacher_code' => 'T004',
            ],
            [
                'name' => 'Ing. Luis Ramírez',
                'email' => 'luis.ramirez@istpet.edu.ec',
                'password' => Hash::make('password'),
                'teacher_code' => 'T005',
            ],
            [
                'name' => 'Lic. Carmen Vega',
                'email' => 'carmen.vega@istpet.edu.ec',
                'password' => Hash::make('password'),
                'teacher_code' => 'T006',
            ],
        ];

        foreach ($teachers as $teacherData) {
            Teacher::create($teacherData);
        }

        $this->command->info('Se crearon ' . count($teachers) . ' profesores de ejemplo.');
    }
}
