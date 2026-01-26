<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use ZipArchive;

class SystemBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sys:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crea un respaldo completo de la base de datos y archivos (Optimizado para XAMPP/Windows)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando proceso de respaldo...');

        $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
        $backupDir = storage_path('app/backups');
        $filename = "siafnin_backup_{$timestamp}.zip";
        $zipPath = $backupDir . '/' . $filename;

        if (!File::exists($backupDir)) {
            File::makeDirectory($backupDir, 0755, true);
        }

        // 1. Exportar Base de Datos
        $dbFile = $backupDir . "/db_temp_{$timestamp}.sql";
        $dbName = config('database.connections.mysql.database');
        $dbUser = config('database.connections.mysql.username');
        $dbPass = config('database.connections.mysql.password');

        // Ruta típica de XAMPP
        $mysqldumpPath = 'C:\xampp\mysql\bin\mysqldump.exe';
        if (!File::exists($mysqldumpPath)) {
            $mysqldumpPath = 'mysqldump'; // Intentar en el PATH global
        }

        $command = "\"{$mysqldumpPath}\" --user={$dbUser} " . ($dbPass ? "--password={$dbPass} " : "") . "{$dbName} > \"{$dbFile}\"";

        exec($command, $output, $resultCode);

        if ($resultCode !== 0) {
            $this->error('Fallo al exportar la base de datos.');
            Log::error('Backup: Fallo al exportar BD (mysqldump). Codigo: ' . $resultCode);
            return 1;
        }

        // 2. Comprimir todo en ZIP
        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            // Agregar SQL
            $zip->addFile($dbFile, 'database.sql');

            // Agregar archivos cargados (storage/app/public)
            $publicFiles = storage_path('app/public');
            if (File::exists($publicFiles)) {
                $files = new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator($publicFiles),
                    \RecursiveIteratorIterator::LEAVES_ONLY
                );

                foreach ($files as $name => $file) {
                    if (!$file->isDir()) {
                        $filePath = $file->getRealPath();
                        $relativePath = 'uploads/' . substr($filePath, strlen($publicFiles) + 1);
                        $zip->addFile($filePath, $relativePath);
                    }
                }
            }

            $zip->close();

            // Limpieza del temporal
            File::delete($dbFile);

            $this->info("Respaldo creado con éxito: {$filename}");
            Log::info("Backup: Respaldo mensual exitoso: {$filename}");

            // 3. Limpieza de respaldos viejos (mantener últimos 7 días)
            $this->cleanup($backupDir);

            return 0;
        } else {
            $this->error('Fallo al crear el archivo ZIP.');
            return 1;
        }
    }

    protected function cleanup($backupDir)
    {
        $files = File::files($backupDir);
        $threshold = Carbon::now()->subDays(7);

        foreach ($files as $file) {
            if ($file->getExtension() === 'zip' && Carbon::createFromTimestamp($file->getMTime())->lt($threshold)) {
                File::delete($file->getPathname());
                $this->comment("Eliminado respaldo antiguo: " . $file->getFilename());
            }
        }
    }
}
