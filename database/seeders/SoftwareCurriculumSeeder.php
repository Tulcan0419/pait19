<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;

class SoftwareCurriculumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpiar materias existentes de software
        Subject::where('career', 'software')->delete();

        // PRIMER SEMESTRE
        $primerSemestre = [
            [
                'name' => 'Álgebra Lineal',
                'credits' => 2,
                'academic_year' => 1,
                'semester' => 1,
                'curricular_unit' => 'basica',
                'career' => 'software',
                'description' => 'Fundamentos matemáticos para el desarrollo de software'
            ],
            [
                'name' => 'TIC\'s y Gestión del Conocimiento',
                'credits' => 1,
                'academic_year' => 1,
                'semester' => 1,
                'curricular_unit' => 'basica',
                'career' => 'software',
                'description' => 'Tecnologías de la Información y Comunicación'
            ],
            [
                'name' => 'Comunicación y Redacción Académica',
                'credits' => 1,
                'academic_year' => 1,
                'semester' => 1,
                'curricular_unit' => 'basica',
                'career' => 'software',
                'description' => 'Habilidades de comunicación y escritura académica'
            ],
            [
                'name' => 'Matemáticas',
                'credits' => 2,
                'academic_year' => 1,
                'semester' => 1,
                'curricular_unit' => 'basica',
                'career' => 'software',
                'description' => 'Matemáticas fundamentales para programación'
            ],
            [
                'name' => 'Fundamentos de Programación',
                'credits' => 3,
                'academic_year' => 1,
                'semester' => 1,
                'curricular_unit' => 'profesional',
                'career' => 'software',
                'description' => 'Introducción a la programación y lógica computacional'
            ],
            [
                'name' => 'Arquitectura Computacional',
                'credits' => 3,
                'academic_year' => 1,
                'semester' => 1,
                'curricular_unit' => 'profesional',
                'career' => 'software',
                'description' => 'Estructura y funcionamiento de sistemas computacionales'
            ],
            [
                'name' => 'Sistemas Operativos y Redes de Comunicación',
                'credits' => 3,
                'academic_year' => 1,
                'semester' => 1,
                'curricular_unit' => 'profesional',
                'career' => 'software',
                'description' => 'Sistemas operativos y fundamentos de redes'
            ],
        ];

        // SEGUNDO SEMESTRE
        $segundoSemestre = [
            [
                'name' => 'Probabilidad y Estadística',
                'credits' => 3,
                'academic_year' => 1,
                'semester' => 2,
                'curricular_unit' => 'basica',
                'career' => 'software',
                'description' => 'Estadística aplicada al desarrollo de software'
            ],
            [
                'name' => 'Fundamentos de Investigación',
                'credits' => 1,
                'academic_year' => 1,
                'semester' => 2,
                'curricular_unit' => 'basica',
                'career' => 'software',
                'description' => 'Metodología de investigación aplicada'
            ],
            [
                'name' => 'Programación Orientada a Objetos',
                'credits' => 3,
                'academic_year' => 1,
                'semester' => 2,
                'curricular_unit' => 'profesional',
                'career' => 'software',
                'description' => 'Programación orientada a objetos y patrones de diseño'
            ],
            [
                'name' => 'Análisis y Diseño de Software',
                'credits' => 2,
                'academic_year' => 1,
                'semester' => 2,
                'curricular_unit' => 'profesional',
                'career' => 'software',
                'description' => 'Metodologías de análisis y diseño de software'
            ],
            [
                'name' => 'SGBD I',
                'credits' => 3,
                'academic_year' => 1,
                'semester' => 2,
                'curricular_unit' => 'profesional',
                'career' => 'software',
                'description' => 'Sistemas de Gestión de Bases de Datos I'
            ],
            [
                'name' => 'Programación Web I',
                'credits' => 2,
                'academic_year' => 1,
                'semester' => 2,
                'curricular_unit' => 'profesional',
                'career' => 'software',
                'description' => 'Desarrollo web frontend y backend básico'
            ],
        ];

        // TERCER SEMESTRE
        $tercerSemestre = [
            [
                'name' => 'Educación Ambiental',
                'credits' => 1,
                'academic_year' => 2,
                'semester' => 3,
                'curricular_unit' => 'basica',
                'career' => 'software',
                'description' => 'Conciencia ambiental y sostenibilidad'
            ],
            [
                'name' => 'Computación en la nube',
                'credits' => 2,
                'academic_year' => 2,
                'semester' => 3,
                'curricular_unit' => 'profesional',
                'career' => 'software',
                'description' => 'Servicios en la nube y arquitecturas cloud'
            ],
            [
                'name' => 'Programación de Aplicaciones',
                'credits' => 2,
                'academic_year' => 2,
                'semester' => 3,
                'curricular_unit' => 'profesional',
                'career' => 'software',
                'description' => 'Desarrollo de aplicaciones móviles y de escritorio'
            ],
            [
                'name' => 'Programación Web III',
                'credits' => 3,
                'academic_year' => 2,
                'semester' => 3,
                'curricular_unit' => 'profesional',
                'career' => 'software',
                'description' => 'Desarrollo web avanzado y frameworks modernos'
            ],
            [
                'name' => 'SGBD II',
                'credits' => 3,
                'academic_year' => 2,
                'semester' => 3,
                'curricular_unit' => 'profesional',
                'career' => 'software',
                'description' => 'Sistemas de Gestión de Bases de Datos II'
            ],
            [
                'name' => 'Calidad de Software y Auditoría Informática',
                'credits' => 2,
                'academic_year' => 2,
                'semester' => 3,
                'curricular_unit' => 'profesional',
                'career' => 'software',
                'description' => 'Aseguramiento de calidad y auditoría de sistemas'
            ],
        ];

        // CUARTO SEMESTRE
        $cuartoSemestre = [
            [
                'name' => 'Emprendimiento y liderazgo',
                'credits' => 1,
                'academic_year' => 2,
                'semester' => 4,
                'curricular_unit' => 'basica',
                'career' => 'software',
                'description' => 'Habilidades de emprendimiento y liderazgo empresarial'
            ],
            [
                'name' => 'Introducción a la Inteligencia Artificial',
                'credits' => 1,
                'academic_year' => 2,
                'semester' => 4,
                'curricular_unit' => 'basica',
                'career' => 'software',
                'description' => 'Fundamentos de inteligencia artificial y machine learning'
            ],
            [
                'name' => 'Programación de Aplicaciones II',
                'credits' => 2,
                'academic_year' => 2,
                'semester' => 4,
                'curricular_unit' => 'profesional',
                'career' => 'software',
                'description' => 'Desarrollo avanzado de aplicaciones empresariales'
            ],
            [
                'name' => 'Sistemas Web',
                'credits' => 2,
                'academic_year' => 2,
                'semester' => 4,
                'curricular_unit' => 'profesional',
                'career' => 'software',
                'description' => 'Arquitecturas web escalables y sistemas distribuidos'
            ],
            [
                'name' => 'Seguridad de la Información',
                'credits' => 2,
                'academic_year' => 2,
                'semester' => 4,
                'curricular_unit' => 'profesional',
                'career' => 'software',
                'description' => 'Ciberseguridad y protección de datos'
            ],
            [
                'name' => 'Bussiness Intelligence',
                'credits' => 2,
                'academic_year' => 2,
                'semester' => 4,
                'curricular_unit' => 'profesional',
                'career' => 'software',
                'description' => 'Inteligencia empresarial y análisis de datos'
            ],
            [
                'name' => 'Unidad de Integración Curricular',
                'credits' => 2,
                'academic_year' => 2,
                'semester' => 4,
                'curricular_unit' => 'integracion',
                'career' => 'software',
                'description' => 'Proyecto integrador final de la carrera'
            ],
        ];

        // Insertar todas las materias
        $todasLasMaterias = array_merge(
            $primerSemestre,
            $segundoSemestre,
            $tercerSemestre,
            $cuartoSemestre
        );

        foreach ($todasLasMaterias as $materia) {
            Subject::create($materia);
        }

        $this->command->info('Curriculum de Software cargado exitosamente con ' . count($todasLasMaterias) . ' materias en 4 semestres.');
    }
} 