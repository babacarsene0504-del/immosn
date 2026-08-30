<?php

namespace Cron;

use Model\CronLog;

/**
 * Classe abstraite parente de tous les scripts Cron.
 * Gère le verrouillage (lock) pour éviter qu'un même script tourne deux fois
 * en même temps, mesure la durée, et journalise systématiquement dans cron_logs.
 */
abstract class CronBase
{
    private string $scriptName;
    private string $lockFile;

    public function __construct(string $scriptName)
    {
        $this->scriptName = $scriptName;
        $this->lockFile = sys_get_temp_dir() . '/immosn_cron_' . $scriptName . '.lock';
    }

    /** Point d'entrée appelé par le script CLI — ne pas redéfinir dans les classes filles */
    final public function execute(): void
    {
        if (!$this->lock()) {
            CronLog::create($this->scriptName, 'skipped', 0, null, 'Script déjà en cours d\'exécution (lock actif)');
            echo "[{$this->scriptName}] Ignoré — un autre exécution est déjà en cours.\n";
            return;
        }

        $start = microtime(true);
        $status = 'success';
        $itemsProcessed = 0;
        $errorMessage = null;

        try {
            $itemsProcessed = $this->run();
        } catch (\Throwable $e) {
            $status = 'error';
            $errorMessage = $e->getMessage();
        } finally {
            $this->unlock();
        }

        $durationMs = (int) round((microtime(true) - $start) * 1000);
        CronLog::create($this->scriptName, $status, $itemsProcessed, $durationMs, $errorMessage);

        echo "[{$this->scriptName}] {$status} — {$itemsProcessed} élément(s) traité(s) en {$durationMs}ms\n";
        if ($errorMessage) {
            echo "  Erreur : {$errorMessage}\n";
        }
    }

    /** Chaque script Cron implémente sa logique ici, et retourne le nombre d'éléments traités */
    abstract protected function run(): int;

    private function lock(): bool
    {
        if (file_exists($this->lockFile)) {
            // Sécurité : si le lock a plus d'1h, on considère qu'un script précédent
            // a planté sans nettoyer, et on le débloque pour ne pas rester coincé indéfiniment.
            if (filemtime($this->lockFile) < time() - 3600) {
                unlink($this->lockFile);
            } else {
                return false;
            }
        }

        file_put_contents($this->lockFile, (string) getmypid());
        return true;
    }

    private function unlock(): void
    {
        if (file_exists($this->lockFile)) {
            unlink($this->lockFile);
        }
    }
}
