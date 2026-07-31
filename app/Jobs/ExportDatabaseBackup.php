<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ExportDatabaseBackup implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $repo = config('services.backup.repo');
        $token = config('services.backup.token');

        if (! $repo || ! $token) {
            Log::warning('Database backup skipped: BACKUP_REPO / BACKUP_GITHUB_TOKEN not configured.');

            return;
        }

        try {
            $dump = $this->export();

            $filename = now()->format('Y-m-d\TH-i-s\Z') . '.sql.gz';
            $path = 'backups/' . $filename;

            $response = Http::withToken($token)
                ->withHeaders(['Accept' => 'application/vnd.github+json'])
                ->put("https://api.github.com/repos/{$repo}/contents/{$path}", [
                    'message' => 'Database backup ' . now()->format('Y-m-d H:i:s'),
                    'content' => base64_encode(gzencode(json_encode($dump, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), 6)),
                ]);

            if ($response->successful()) {
                Log::info('Database backup uploaded: ' . $path);
            } else {
                Log::error('Database backup upload failed (' . $response->status() . '): ' . $response->body());
            }

            $this->prune($token, $repo);
        } catch (\Throwable $e) {
            Log::error('Database backup export failed: ' . $e->getMessage());
            report($e);
        }
    }

    protected function export(): array
    {
        $connection = DB::connection();
        $schema = $connection->getDriverName() === 'pgsql' ? 'public' : $connection->getDatabaseName();

        $tables = collect(DB::select(
            'SELECT table_name FROM information_schema.tables WHERE table_schema = ? AND table_type = \'BASE TABLE\' ORDER BY table_name',
            [$schema]
        ))->pluck('table_name');

        $dump = [
            'exported_at' => now()->toIso8601String(),
            'app' => config('app.name'),
            'tables' => [],
        ];

        foreach ($tables as $table) {
            $rows = DB::table($table)
                ->get()
                ->map(function ($row) {
                    $row = (array) $row;

                    foreach ($row as $key => $value) {
                        if (is_resource($value)) {
                            $row[$key] = stream_get_contents($value);
                        }
                    }

                    return $row;
                })
                ->all();

            $dump['tables'][$table] = $rows;
        }

        return $dump;
    }

    protected function prune(string $token, string $repo): void
    {
        $keep = max(1, (int) config('services.backup.keep', 30));

        $response = Http::withToken($token)
            ->withHeaders(['Accept' => 'application/vnd.github+json'])
            ->get("https://api.github.com/repos/{$repo}/contents/backups");

        if (! $response->successful() || ! is_array($response->json())) {
            return;
        }

        $files = collect($response->json())
            ->filter(fn ($file) => ($file['type'] ?? null) === 'file')
            ->sortByDesc('name')
            ->values();

        foreach ($files->slice($keep) as $file) {
            Http::withToken($token)
                ->withHeaders(['Accept' => 'application/vnd.github+json'])
                ->delete("https://api.github.com/repos/{$repo}/contents/{$file['path']}", [
                    'message' => 'Remove old database backup ' . $file['name'],
                    'sha' => $file['sha'],
                ]);
        }
    }
}
