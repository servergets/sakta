{{-- resources/views/filament/pages/auth/login.blade.php --}}
<div>
    {{-- Split Screen Container --}}
    <div class="custom-login-container">
        {{-- Left Side - Background Image & Logo --}}
        <div class="custom-login-bg">
            <img src="{{ asset('images/bg-login.jpg') }}" 
                 alt="Background"
                 class="custom-bg-image">
            
            {{-- Gradient Overlay --}}
            <div class="custom-overlay"></div>
            
            {{-- Logo Bottom Left --}}
            <div class="custom-logo">
                <img src="{{ asset('images/logo.png') }}" 
                     alt="SAKTA Logo" 
                     class="custom-logo-img">
            </div>
        </div>

        {{-- Right Side - Login Form --}}
        <div class="custom-login-form">
            <div class="custom-form-container">
                
                {{-- Mobile Logo --}}
                <div class="custom-mobile-logo">
                    <img src="{{ asset('images/logo.png') }}" 
                         alt="SAKTA Logo" 
                         class="custom-mobile-logo-img">
                </div>
                
                {{-- Header --}}
                <div class="custom-header text-center">
            
                    <h1 class="custom-title flex items-center justify-center gap-2 text-2xl font-bold">
                    <span class="custom-brand">Selamat datang di</span> <span class="text-gray-900">SA</span><span class="custom-brand">K</span><span class="text-gray-900">TA</span>
                </div>


                {{-- Login Form --}}
                <div class="custom-form-wrapper">
                    <form wire:submit="authenticate" class="custom-form">
                        {{-- Use Filament Form Components --}}
                        <div class="custom-fields">
                            {{ $this->form }}
                        </div>

                        {{-- Submit Button --}}
                        <x-filament::button 
                            type="submit"
                            size="lg"
                            class="custom-submit">
                            <span wire:loading.remove wire:target="authenticate">
                                Masuk
                            </span>
                            <span wire:loading wire:target="authenticate" class="custom-loading">
                                <svg class="custom-spinner" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="custom-spinner-circle" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="custom-spinner-path" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Memproses...
                            </span>
                        </x-filament::button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* FORCE OVERRIDE FILAMENT LAYOUT */
        html, body {
            margin: 0 !important;
            padding: 0 !important;
            height: 100% !important;
            overflow-x: hidden !important;
        }
        
        .fi-layout,
        .fi-simple-layout,
        .fi-simple-main,
        .fi-simple-page,
        .fi-simple-header,
        .fi-simple-footer {
            padding: 0 !important;
            margin: 0 !important;
            background: transparent !important;
            min-height: 100vh !important;
        }

        /* CUSTOM LOGIN STYLES */
        .custom-login-container {
            display: flex;
            min-height: 100vh;
            width: 100%;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 9999;
        }
        
        .custom-login-bg {
            display: none;
            position: relative;
            background: linear-gradient(135deg, #1f2937 0%, #374151 100%);
        }
        
        .custom-bg-image {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.8;
        }
        
        .custom-overlay {
            position: absolute;
            /* inset: 0; */
            background: linear-gradient(135deg, rgba(0,0,0,0.5) 0%, transparent 100%);
        }
        
        .custom-logo {
            position: absolute;
            bottom: 2rem;
            left: 2rem;
            z-index: 10;
        }
        
        .custom-logo-img {
            height: 4rem;
            width: auto;
            filter: drop-shadow(0 10px 25px rgba(0,0,0,0.5));
        }
        
        .custom-login-form {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            background: white;
        }
        
        .custom-form-container {
            width: 100%;
            max-width: 24rem;
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }
        
        .custom-mobile-logo {
            text-align: center;
        }
        
        .custom-mobile-logo-img {
            height: 3rem;
            width: auto;
            margin: 0 auto 2rem;
        }
        
        .custom-header {
            text-align: left;
        }
        
        .custom-title {
            font-size: 1.875rem;
            font-weight: 700;
            color: #111827;
            line-height: 1.2;
            margin: 0;
        }
        
        .custom-brand {
            background: linear-gradient(135deg, #14b8a6 0%, #0891b2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 900;
        }
        
        .custom-subtitle {
            margin-top: 1rem;
            font-size: 1.25rem;
            font-weight: 600;
            color: #374151;
        }
        
        .custom-form-wrapper {
            margin-top: 2rem;
        }
        
        .custom-form {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        
        .custom-fields {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        
        .custom-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .custom-remember {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .custom-checkbox {
            height: 1rem;
            width: 1rem;
            color: #14b8a6;
            border-radius: 0.25rem;
            border: 1px solid #d1d5db;
        }
        
        .custom-checkbox:focus {
            box-shadow: 0 0 0 2px rgba(20, 184, 166, 0.2);
        }
        
        .custom-remember-text {
            font-size: 0.875rem;
            color: #111827;
        }
        
        .custom-forgot {
            font-size: 0.875rem;
        }
        
        .custom-forgot-link {
            font-weight: 500;
            color: #14b8a6;
            text-decoration: none;
            transition: color 0.2s;
        }
        
        .custom-forgot-link:hover {
            color: #0d9488;
        }
        
        .custom-submit {
            width: 100% !important;
            justify-content: center !important;
            padding: 0.75rem 1.5rem !important;
            font-size: 1rem !important;
            font-weight: 600 !important;
            color: white !important;
            background-color: #14b8a6 !important;
            border: none !important;
            border-radius: 0.5rem !important;
            cursor: pointer !important;
            transition: all 0.2s !important;
        }
        
        .custom-submit:hover {
            background-color: #0d9488 !important;
        }
        
        .custom-loading {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .custom-spinner {
            animation: spin 1s linear infinite;
            height: 1.25rem;
            width: 1.25rem;
            color: white;
        }
        
        .custom-spinner-circle {
            opacity: 0.25;
        }
        
        .custom-spinner-path {
            opacity: 0.75;
        }
        
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* FILAMENT FORM FIELD OVERRIDES */
        .fi-fo-field-wrp {
            margin-bottom: 1.5rem !important;
        }
        
        .fi-fo-field-wrp-label {
            display: block !important;
            font-size: 0.875rem !important;
            font-weight: 500 !important;
            color: #374151 !important;
            margin-bottom: 0.5rem !important;
        }
        
        .fi-input {
            display: block !important;
            width: 100% !important;
            padding: 0.75rem 1rem !important;
            border: 1px solid #d1d5db !important;
            border-radius: 0.5rem !important;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
            font-size: 1rem !important;
            line-height: 1.5 !important;
            color: #111827 !important;
            background-color: #ffffff !important;
            transition: all 0.2s ease !important;
        }
        
        .fi-input::placeholder {
            color: #9ca3af !important;
        }
        
        .fi-input:focus {
            outline: none !important;
            border-color: #14b8a6 !important;
            box-shadow: 0 0 0 3px rgba(20, 184, 166, 0.1) !important;
        }

        .fi-input-wrapper .fi-input-suffix {
            position: absolute !important;
            right: 0.75rem !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            color: #6b7280 !important;
        }

        .fi-fo-field-wrp-error-message {
            margin-top: 0.25rem !important;
            font-size: 0.875rem !important;
            color: #ef4444 !important;
        }

        /* RESPONSIVE */
        @media (min-width: 1024px) {
            .custom-login-bg {
                display: block;
                width: 50%;
            }
            
            .custom-login-form {
                width: 50%;
            }
            
            .custom-mobile-logo {
                display: none;
            }
        }
        
        @media (max-width: 1023px) {
            .custom-mobile-logo {
                display: block;
            }
        }
    </style>
</div>