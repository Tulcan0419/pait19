<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificado de {{ $practiceType }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        @page {
            size: A4 landscape;
            margin: 0;
        }
        
        body {
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
            color: #333;
            line-height: 1.6;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        
        .certificate-container {
            width: 297mm;
            height: 210mm;
            margin: 20px auto;
            border: 20px solid #1a3a5f;
            padding: 40px;
            background: #fff;
            position: relative;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            box-sizing: border-box;
            background-image: 
                radial-gradient(circle at 10% 20%, rgba(232, 242, 252, 0.5) 0%, transparent 20%),
                radial-gradient(circle at 90% 80%, rgba(232, 242, 252, 0.5) 0%, transparent 20%);
        }
        
        .border-design {
            position: absolute;
            top: 15px;
            left: 15px;
            right: 15px;
            bottom: 15px;
            border: 2px solid #c9ab5a;
            pointer-events: none;
        }
        
        .corner {
            position: absolute;
            width: 40px;
            height: 40px;
        }
        
        .corner.top-left {
            top: 0;
            left: 0;
            border-top: 3px solid #c9ab5a;
            border-left: 3px solid #c9ab5a;
        }
        
        .corner.top-right {
            top: 0;
            right: 0;
            border-top: 3px solid #c9ab5a;
            border-right: 3px solid #c9ab5a;
        }
        
        .corner.bottom-left {
            bottom: 0;
            left: 0;
            border-bottom: 3px solid #c9ab5a;
            border-left: 3px solid #c9ab5a;
        }
        
        .corner.bottom-right {
            bottom: 0;
            right: 0;
            border-bottom: 3px solid #c9ab5a;
            border-right: 3px solid #c9ab5a;
        }
        
        .certificate-header {
            text-align: center;
            margin-bottom: 30px;
            position: relative;
        }
        
        .institution-logo {
            width: 100px;
            height: 100px;
            margin: 0 auto 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #1a3a5f;
            border-radius: 50%;
            color: #fff;
            font-size: 40px;
            font-weight: bold;
            box-shadow: 0 0 0 5px rgba(201, 171, 90, 0.3);
        }
        
        .institution-name {
            font-family: 'Playfair Display', serif;
            font-size: 32px;
            font-weight: bold;
            color: #1a3a5f;
            margin-bottom: 5px;
            letter-spacing: 1px;
        }
        
        .institution-subtitle {
            font-size: 16px;
            color: #7f8c8d;
            margin-bottom: 3px;
        }
        
        .certificate-title {
            font-family: 'Playfair Display', serif;
            font-size: 36px;
            font-weight: bold;
            color: #1a3a5f;
            text-align: center;
            margin: 30px 0;
            text-transform: uppercase;
            letter-spacing: 3px;
            position: relative;
            padding-bottom: 15px;
        }
        
        .certificate-title:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 3px;
            background: linear-gradient(90deg, transparent, #c9ab5a, transparent);
        }
        
        .certificate-content {
            text-align: center;
            margin: 30px 0;
            font-size: 16px;
            line-height: 1.8;
        }
        
        .student-info {
            background-color: rgba(26, 58, 95, 0.05);
            padding: 25px;
            border-radius: 8px;
            margin: 25px 0;
            border-left: 5px solid #c9ab5a;
            text-align: center;
        }
        
        .student-name {
            font-size: 24px;
            font-weight: bold;
            color: #1a3a5f;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .student-details {
            font-size: 16px;
            color: #34495e;
            margin-bottom: 5px;
        }
        
        .hours-completed {
            background: linear-gradient(135deg, #1a3a5f 0%, #2c5282 100%);
            color: white;
            padding: 15px;
            border-radius: 8px;
            font-weight: bold;
            text-align: center;
            margin: 20px 0;
            font-size: 18px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .practice-details {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin: 25px 0;
        }
        
        .practice-info {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
            border-left: 3px solid #1a3a5f;
        }
        
        .practice-label {
            font-weight: bold;
            color: #1a3a5f;
            display: block;
            margin-bottom: 5px;
            font-size: 14px;
        }
        
        .practice-value {
            color: #34495e;
            font-size: 15px;
        }
        
        .documents-section {
            margin: 25px 0;
        }
        
        .documents-title {
            font-size: 18px;
            font-weight: bold;
            color: #1a3a5f;
            margin-bottom: 15px;
            text-align: center;
            position: relative;
            padding-bottom: 10px;
        }
        
        .documents-title:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 50px;
            height: 2px;
            background: #c9ab5a;
        }
        
        .documents-list {
            list-style: none;
            padding: 0;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
        }
        
        .document-item {
            background-color: rgba(201, 171, 90, 0.1);
            padding: 10px 15px;
            border-radius: 5px;
            border: 1px solid rgba(201, 171, 90, 0.3);
            font-size: 14px;
            flex: 1;
            min-width: 200px;
            text-align: center;
        }
        
        .signatures-section {
            display: flex;
            justify-content: center;
            gap: 100px;
            margin-top: 50px;
            padding-top: 30px;
            border-top: 2px solid #e0e0e0;
        }
        
        .signature-box {
            text-align: center;
            width: 250px;
        }
        
        .signature-line {
            border-top: 1px solid #333;
            margin: 40px 0 10px;
            width: 100%;
        }
        
        .signature-name {
            font-weight: bold;
            color: #1a3a5f;
            margin-bottom: 5px;
        }
        
        .signature-title {
            font-size: 12px;
            color: #7f8c8d;
        }
        
        .certificate-footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ecf0f1;
            font-size: 12px;
            color: #7f8c8d;
        }
        
        .certificate-number {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 12px;
            color: #7f8c8d;
            background-color: #f8f9fa;
            padding: 5px 10px;
            border-radius: 3px;
            border: 1px solid #e0e0e0;
        }
        
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 100px;
            color: rgba(26, 58, 95, 0.05);
            font-weight: bold;
            z-index: -1;
            font-family: 'Playfair Display', serif;
            letter-spacing: 5px;
            text-transform: uppercase;
        }
        
        .seal {
            position: absolute;
            bottom: 40px;
            left: 40px;
            width: 80px;
            height: 80px;
            border: 2px solid #c9ab5a;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            color: #c9ab5a;
            text-align: center;
            background: rgba(255, 255, 255, 0.7);
            transform: rotate(-15deg);
        }
        
        @media print {
            body {
                background-color: white;
                padding: 0;
                margin: 0;
            }
            
            .certificate-container {
                border: 20px solid #1a3a5f;
                box-shadow: none;
                margin: 0;
                padding: 40px;
                width: 100%;
                height: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        <div class="border-design"></div>
        <div class="corner top-left"></div>
        <div class="corner top-right"></div>
        <div class="corner bottom-left"></div>
        <div class="corner bottom-right"></div>
        
        <div class="watermark">CERTIFICADO</div>
        
        <div class="certificate-number">
            Cert. #{{ str_pad($student->id, 6, '0', STR_PAD_LEFT) }}-{{ date('Y') }}
        </div>
        
        <div class="certificate-header">
            <div class="institution-logo">IST</div>
            <div class="institution-name">Tecnológico Traversari</div>
            <div class="institution-subtitle">Instituto Superior Tecnológico</div>
            <div class="institution-subtitle">ISTPET</div>
        </div>
        
        <div class="certificate-title">
            Certificado de {{ $practiceType }}
        </div>
        
        <div class="certificate-content">
            <p>Se hace constar que:</p>
            
            <div class="student-info">
                <div class="student-name">{{ $student->name }}</div>
                <div class="student-details">Cédula: {{ $student->identification_number ?? 'N/A' }}</div>
                <div class="student-details">Carrera: {{ $career }}</div>
                <div class="student-details">Semestre: {{ $semester }}°</div>
            </div>
            
            <p>Ha completado exitosamente sus <strong>{{ $practiceType }}</strong> con un total de:</p>
            
            <div class="hours-completed">
                {{ $hoursCompleted }} horas de {{ $requiredHours }} horas requeridas
            </div>
            
            <p>Habiendo cumplido con todos los requisitos académicos establecidos por la institución.</p>
        </div>
        
        <div class="practice-details">
            <div class="practice-info">
                <span class="practice-label">Tipo de Práctica:</span>
                <span class="practice-value">{{ $practiceType }}</span>
            </div>
            <div class="practice-info">
                <span class="practice-label">Horas Completadas:</span>
                <span class="practice-value">{{ $hoursCompleted }} horas</span>
            </div>
            <div class="practice-info">
                <span class="practice-label">Horas Requeridas:</span>
                <span class="practice-value">{{ $requiredHours }} horas</span>
            </div>
            <div class="practice-info">
                <span class="practice-label">Fecha de Emisión:</span>
                <span class="practice-value">{{ $currentDate }}</span>
            </div>
            @if($tutor)
            <div class="practice-info">
                <span class="practice-label">Tutor Asignado:</span>
                <span class="practice-value">{{ $tutor->name }}</span>
            </div>
            @endif
        </div>
        
        <div class="documents-section">
            <div class="documents-title">Documentos Aprobados</div>
            <ul class="documents-list">
                @foreach($documents as $document)
                    <li class="document-item">
                        ✓ {{ ucfirst(str_replace('_', ' ', $document->document_type)) }}
                        @if($document->document_type === 'certificado_horas' && $document->hours_completed)
                            ({{ $document->hours_completed }} horas)
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
        
        <div class="signatures-section">
            <div class="signature-box">
                <div class="signature-line"></div>
                <div class="signature-name">{{ $tutor ? $tutor->name : 'Tutor Asignado' }}</div>
                <div class="signature-title">Tutor de Prácticas</div>
            </div>
            
            <div class="signature-box">
                <div class="signature-line"></div>
                <div class="signature-name">Coordinador Académico</div>
                <div class="signature-title">Coordinación de Prácticas</div>
            </div>
        </div>
        
        <div class="seal">
            SELLO<br>INSTITUCIONAL
        </div>
        
        <div class="certificate-footer">
            <p>Este certificado es válido y ha sido emitido por el Sistema de Gestión Académica del Tecnológico Traversari ISTPET.</p>
            <p>Para verificar la autenticidad de este certificado, contacte a la institución.</p>
        </div>
    </div>
</body>
</html>