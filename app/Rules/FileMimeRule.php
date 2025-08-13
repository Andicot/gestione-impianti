<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class FileMimeRule implements ValidationRule
{
    /**
     * Lista dei MIME types non permessi
     *
     * @var array
     */
    protected $blacklistedMimes = [
        // Windows executables
        'application/x-msdownload',
        'application/x-executable',
        'application/x-dosexec',
        'application/x-msdos-program',
        'application/x-msdos-windows',
        'application/x-download',
        'application/octet-stream',     // Può contenere eseguibili di vario tipo
        'application/x-shellscript',

        // macOS executables
        'application/x-mach-binary',    // Binari Mach-O
        'application/x-apple-binary',   // Binari Apple
        'application/x-apple-diskimage', // File .dmg
        'application/x-newton-compatible-pkg', // Pacchetti macOS
        'application/x-pkg',            // Installer packages
        'application/x-osx-app',        // App bundle
        'application/vnd.apple.installer+xml', // Pacchetti installer XML
        'application/x-apple-systemprofiler+xml', // File di sistema

        // Script e interpreti che potrebbero essere pericolosi su macOS
        'application/x-python',         // Script Python
        'application/x-perl',           // Script Perl
        'application/x-ruby',           // Script Ruby
        'text/x-applescript',           // AppleScript
        'application/x-applescript',    // AppleScript binario
        'application/x-swift',          // Swift
    ];

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$value->isValid()) {
            $fail('Il file non è valido.');
            return;
        }

        $mimeType = $value->getMimeType();

        if (in_array($mimeType, $this->blacklistedMimes)) {
            $fail('Il tipo di file non è permesso per motivi di sicurezza.');
        }
    }

    /**
     * Aggiungi un MIME type alla blacklist
     */
    public function addBlacklistedMime(string $mimeType): self
    {
        if (!in_array($mimeType, $this->blacklistedMimes)) {
            $this->blacklistedMimes[] = $mimeType;
        }

        return $this;
    }

    /**
     * Imposta una nuova lista di MIME types non permessi
     */
    public function setBlacklistedMimes(array $mimes): self
    {
        $this->blacklistedMimes = $mimes;

        return $this;
    }
}
