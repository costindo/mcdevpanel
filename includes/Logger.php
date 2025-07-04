<?php
// includes/Logger.php

class Logger
{
    /** @var resource|null */
    private $file;

    /**
     * Logger constructor.
     * @param string $path Calea completă către fișierul de log
     * @throws RuntimeException dacă nu reușește să creeze directorul sau să deschidă fișierul
     */
    public function __construct(string $path)
    {
        $dir = dirname($path);
        if (!is_dir($dir)) {
            if (false === mkdir($dir, 0755, true)) {
                throw new \RuntimeException("Nu am putut crea directorul de log: {$dir}");
            }
        }

        $this->file = @fopen($path, 'a');
        if (!is_resource($this->file)) {
            throw new \RuntimeException("Nu am putut deschide fișierul de log: {$path}");
        }
    }

    /**
     * Scrie un mesaj în log
     * @param string $level  Nivelul log-ului (INFO, ERROR, DEBUG)
     * @param string $message Mesajul propriu-zis
     */
    public function log(string $level, string $message): void
    {
        if (!is_resource($this->file)) {
            return;
        }
        $time = (new \DateTime('now', new \DateTimeZone('Europe/Bucharest')))
                    ->format('Y-m-d H:i:s');
        $line = "[{$time}] [{$level}] {$message}" . PHP_EOL;
        fwrite($this->file, $line);
    }

    public function info(string $msg): void  { $this->log('INFO',  $msg); }
    public function error(string $msg): void { $this->log('ERROR', $msg); }
    public function debug(string $msg): void { $this->log('DEBUG', $msg); }

    public function __destruct()
    {
        if (is_resource($this->file)) {
            fclose($this->file);
        }
    }
}
