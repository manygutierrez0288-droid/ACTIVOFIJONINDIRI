<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BackupController extends Controller
{
    /**
     * Display a listing of backups.
     */
    public function index()
    {
        $backupDir = storage_path('app/backups');
        $backups = [];

        if (File::exists($backupDir)) {
            $files = File::files($backupDir);
            foreach ($files as $file) {
                if ($file->getExtension() === 'zip') {
                    $backups[] = [
                        'name' => $file->getFilename(),
                        'size' => round($file->getSize() / 1024 / 1024, 2) . ' MB',
                        'date' => date('d/m/Y H:i:s', $file->getMTime()),
                        'raw_date' => $file->getMTime(),
                    ];
                }
            }
        }

        // Sort by date descending
        usort($backups, function ($a, $b) {
            return $b['raw_date'] <=> $a['raw_date'];
        });

        return Inertia::render('Settings/Backups', [
            'backups' => $backups
        ]);
    }

    /**
     * Manually trigger a backup.
     */
    public function create()
    {
        Artisan::call('sys:backup');
        return redirect()->back()->with('success', 'Respaldo iniciado correctamente.');
    }

    /**
     * Download a specific backup.
     */
    public function download($filename)
    {
        $path = storage_path('app/backups/' . $filename);

        if (!File::exists($path)) {
            abort(404);
        }

        return response()->download($path);
    }

    /**
     * Delete a specific backup.
     */
    public function destroy($filename)
    {
        $path = storage_path('app/backups/' . $filename);

        if (File::exists($path)) {
            File::delete($path);
            return redirect()->back()->with('success', 'Respaldo eliminado.');
        }

        return redirect()->back()->with('error', 'No se pudo encontrar el archivo.');
    }
}
