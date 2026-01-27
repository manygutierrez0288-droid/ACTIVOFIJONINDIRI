<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Activo - {{ $activo['codigo_inventario'] }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .glass-header {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        }
    </style>
</head>

<body class="bg-slate-50 min-h-screen">
    <!-- Header -->
    <header class="glass-header sticky top-0 z-50 px-4 py-4 flex justify-between items-center">
        <div class="flex items-center gap-3">
            <div class="bg-indigo-600 p-2 rounded-xl shadow-lg shadow-indigo-200">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                    </path>
                </svg>
            </div>
            <div>
                <h1 class="font-bold text-slate-800 leading-none">SIAFNIN</h1>
                <span class="text-[10px] text-slate-400 font-medium uppercase tracking-wider">Consulta Pública</span>
            </div>
        </div>
        <div
            class="bg-emerald-50 text-emerald-700 px-3 py-1.5 rounded-full text-xs font-bold flex items-center gap-1.5 border border-emerald-100 uppercase">
            <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
            Verificado
        </div>
    </header>

    <main class="max-w-md mx-auto p-6 space-y-6">
        <!-- Asset Identification -->
        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/60 overflow-hidden border border-white">
            <div
                class="bg-gradient-to-br from-indigo-600 via-indigo-700 to-blue-800 p-8 text-center text-white relative">
                <div class="absolute top-0 right-0 p-4 opacity-10">
                    <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
                    </svg>
                </div>
                <p class="text-indigo-100 text-sm font-semibold mb-1 uppercase tracking-widest">Código de Inventario</p>
                <h2 class="text-4xl font-extrabold tracking-tight mb-2">{{ $activo['codigo_inventario'] }}</h2>
                <div
                    class="inline-block bg-white/20 backdrop-blur-md px-4 py-1.5 rounded-full text-sm font-medium border border-white/30">
                    {{ $activo['nombre'] }}
                </div>
            </div>

            <div class="p-8 space-y-8">
                <!-- Info Grid -->
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Categoría</p>
                        <p class="text-slate-700 font-semibold">{{ $activo['categoria'] ?? '---' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Estado</p>
                        <p class="text-slate-700 font-semibold italic">{{ $activo['estado_nombre'] ?? '---' }}</p>
                    </div>
                </div>

                <div class="h-px bg-slate-100"></div>

                <!-- Assignment Info -->
                <div class="space-y-4">
                    <div class="flex items-start gap-4">
                        <div class="bg-blue-50 p-2.5 rounded-xl">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Ubicación
                                Actual</p>
                            <p class="text-slate-700 font-semibold leading-snug">
                                {{ $activo['ubicacion'] ?? 'No especificada' }}</p>
                            <p class="text-xs text-slate-400">{{ $activo['departamento'] ?? '' }}</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="bg-indigo-50 p-2.5 rounded-xl">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Responsable
                                del Bien</p>
                            <p class="text-slate-700 font-semibold leading-snug">
                                {{ $activo['responsable'] ?? 'No asignado' }}</p>
                        </div>
                    </div>
                </div>

                <div class="h-px bg-slate-100"></div>

                <!-- Footer Text -->
                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                    <p class="text-[10px] text-slate-500 leading-relaxed text-center italic">
                        "Documento digital generado automáticamente por el Sistema de Activo Fijo de la Alcaldía de
                        Nindirí."
                    </p>
                </div>
            </div>
        </div>

        <p class="text-center text-[10px] text-slate-400 font-medium uppercase tracking-[0.2em] pt-4">
            &copy; {{ date('Y') }} Alcaldía Municipal de Nindirí
        </p>
    </main>
</body>

</html>