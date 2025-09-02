<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foto de Perfil - Tecnológico Traversari - ISTPET</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            padding: 40px;
            max-width: 500px;
            width: 100%;
            position: relative;
            overflow: hidden;
        }

        .container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea, #764ba2);
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 2rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 8px;
        }

        .header p {
            color: #718096;
            font-size: 1rem;
        }

        .alert {
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 25px;
            font-weight: 500;
        }

        .alert-success {
            background: linear-gradient(135deg, #48bb78, #38a169);
            color: white;
            box-shadow: 0 4px 15px rgba(72, 187, 120, 0.3);
        }

        .alert-danger {
            background: linear-gradient(135deg, #f56565, #e53e3e);
            color: white;
            box-shadow: 0 4px 15px rgba(245, 101, 101, 0.3);
        }

        .alert ul {
            list-style: none;
            margin-top: 8px;
        }

        .alert li {
            margin-bottom: 4px;
        }

        .current-photo-section {
            text-align: center;
            margin-bottom: 30px;
            padding: 25px;
            background: linear-gradient(135deg, #f7fafc, #edf2f7);
            border-radius: 16px;
            border: 2px dashed #e2e8f0;
        }

        .photo-preview {
            position: relative;
            display: inline-block;
            margin-bottom: 20px;
        }

        .photo-preview img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #fff;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
        }

        .photo-preview:hover img {
            transform: scale(1.05);
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.2);
        }

        .remove-btn {
            background: linear-gradient(135deg, #f56565, #e53e3e);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(245, 101, 101, 0.3);
        }

        .remove-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(245, 101, 101, 0.4);
        }

        .upload-section {
            background: white;
            border-radius: 16px;
            padding: 30px;
            border: 2px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .upload-section:hover {
            border-color: #667eea;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.1);
        }

        .upload-area {
            border: 3px dashed #cbd5e0;
            border-radius: 16px;
            padding: 40px 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: linear-gradient(135deg, #f7fafc, #edf2f7);
        }

        .upload-area:hover {
            border-color: #667eea;
            background: linear-gradient(135deg, #edf2f7, #e2e8f0);
        }

        .upload-area.dragover {
            border-color: #667eea;
            background: linear-gradient(135deg, #e6fffa, #b2f5ea);
        }

        .upload-icon {
            font-size: 3rem;
            color: #667eea;
            margin-bottom: 15px;
        }

        .upload-text {
            color: #4a5568;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .upload-subtext {
            color: #718096;
            font-size: 0.9rem;
        }

        .file-input {
            display: none;
        }

        .actions {
            display: flex;
            gap: 15px;
            margin-top: 25px;
            justify-content: center;
        }

        .btn {
            padding: 12px 24px;
            border-radius: 25px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            font-size: 1rem;
        }

        .btn-cancel {
            background: linear-gradient(135deg, #a0aec0, #718096);
            color: white;
            box-shadow: 0 4px 15px rgba(160, 174, 192, 0.3);
        }

        .btn-cancel:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(160, 174, 192, 0.4);
        }

        .btn-upload {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .btn-upload:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none !important;
        }

        .progress-bar {
            width: 100%;
            height: 6px;
            background: #e2e8f0;
            border-radius: 3px;
            overflow: hidden;
            margin-top: 15px;
            display: none;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #667eea, #764ba2);
            width: 0%;
            transition: width 0.3s ease;
        }

        .loading {
            display: none;
            text-align: center;
            margin-top: 15px;
        }

        .spinner {
            border: 3px solid #f3f4f6;
            border-top: 3px solid #667eea;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            animation: spin 1s linear infinite;
            margin: 0 auto 10px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @media (max-width: 600px) {
            .container {
                padding: 25px;
                margin: 10px;
            }

            .header h1 {
                font-size: 1.5rem;
            }

            .actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }

        .floating-shapes {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
        }

        .shape {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        .shape:nth-child(1) {
            width: 80px;
            height: 80px;
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }

        .shape:nth-child(2) {
            width: 120px;
            height: 120px;
            top: 60%;
            right: 10%;
            animation-delay: 2s;
        }

        .shape:nth-child(3) {
            width: 60px;
            height: 60px;
            bottom: 20%;
            left: 20%;
            animation-delay: 4s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }
    </style>
</head>
<body>
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <div class="container">
        <div class="header">
            <h1><i class="fas fa-user-circle"></i> Foto de Perfil</h1>
            <p>Personaliza tu imagen de perfil</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle"></i>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="current-photo-section">
            <div class="photo-preview">
                <img id="profile-preview" 
                     src="{{ \App\Http\Controllers\ProfilePhotoController::getProfilePhotoUrl($user) }}" 
                     alt="Foto de perfil actual">
            </div>
            
            @if($user->profile_photo)
                <form action="{{ $routes['remove'] }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="remove-btn" onclick="return confirm('¿Estás seguro de que quieres eliminar tu foto de perfil?')">
                        <i class="fas fa-trash"></i> Eliminar Foto
                    </button>
                </form>
            @endif
        </div>

        <div class="upload-section">
            <form action="{{ $routes['upload'] }}" method="POST" enctype="multipart/form-data" id="upload-form">
                @csrf
                
                <div class="upload-area" id="upload-area">
                    <div class="upload-icon">
                        <i class="fas fa-cloud-upload-alt"></i>
                    </div>
                    <div class="upload-text">Haz clic para subir o arrastra tu imagen aquí</div>
                    <div class="upload-subtext">PNG, JPG, GIF hasta 2MB</div>
                    <input type="file" id="profile_photo" name="profile_photo" class="file-input" accept="image/*" onchange="previewImage(this)">
                </div>

                <div class="progress-bar" id="progress-bar">
                    <div class="progress-fill" id="progress-fill"></div>
                </div>

                <div class="loading" id="loading">
                    <div class="spinner"></div>
                    <p>Subiendo imagen...</p>
                </div>

                <div class="actions">
                    <a href="{{ $routes['dashboard'] }}" class="btn btn-cancel">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-upload" id="upload-btn">
                        <i class="fas fa-upload"></i> Subir Foto
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Preview image functionality
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profile-preview').src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Drag and drop functionality
        const uploadArea = document.getElementById('upload-area');
        const fileInput = document.getElementById('profile_photo');

        uploadArea.addEventListener('click', () => {
            fileInput.click();
        });

        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadArea.classList.add('dragover');
        });

        uploadArea.addEventListener('dragleave', () => {
            uploadArea.classList.remove('dragover');
        });

        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.classList.remove('dragover');
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                fileInput.files = files;
                previewImage(fileInput);
            }
        });

        // Form submission with loading state
        document.getElementById('upload-form').addEventListener('submit', function(e) {
            const fileInput = document.getElementById('profile_photo');
            const uploadBtn = document.getElementById('upload-btn');
            const progressBar = document.getElementById('progress-bar');
            const progressFill = document.getElementById('progress-fill');
            const loading = document.getElementById('loading');

            if (!fileInput.files.length) {
                e.preventDefault();
                alert('Por favor selecciona una imagen');
                return;
            }

            // Show loading state
            uploadBtn.disabled = true;
            uploadBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Subiendo...';
            progressBar.style.display = 'block';
            loading.style.display = 'block';

            // Simulate progress
            let progress = 0;
            const progressInterval = setInterval(() => {
                progress += Math.random() * 30;
                if (progress > 90) progress = 90;
                progressFill.style.width = progress + '%';
            }, 200);

            // Reset after form submission
            setTimeout(() => {
                clearInterval(progressInterval);
                progressFill.style.width = '100%';
            }, 1000);
        });

        // Add some interactive effects
        document.addEventListener('DOMContentLoaded', function() {
            // Add hover effects to buttons
            const buttons = document.querySelectorAll('.btn');
            buttons.forEach(button => {
                button.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px)';
                });
                
                button.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });

            // Add click effect to upload area
            uploadArea.addEventListener('mousedown', function() {
                this.style.transform = 'scale(0.98)';
            });

            uploadArea.addEventListener('mouseup', function() {
                this.style.transform = 'scale(1)';
            });

            uploadArea.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1)';
            });
        });
    </script>
</body>
</html> 