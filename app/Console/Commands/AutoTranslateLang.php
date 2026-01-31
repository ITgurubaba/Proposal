<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class AutoTranslateLang extends Command
{
    protected $signature = 'lang:auto-translate';
    protected $description = 'Translate English messages.php to Hindi and Nepali using LibreTranslate';

    public function handle()
    {
        $sourceLang = 'en';
        $targetLangs = ['hi', 'ne']; // Add more as needed

        $sourceFile = resource_path("lang/{$sourceLang}/messages.php");

        if (!file_exists($sourceFile)) {
            $this->error("Source file not found at: $sourceFile");
            return;
        }

        $strings = include $sourceFile;

        foreach ($targetLangs as $lang) {
            $this->info("Translating to [$lang]...");
            $translated = [];

            foreach ($strings as $key => $text) {
                $response = Http::timeout(10)->post('https://translate.argosopentech.com/translate', [
                    'q' => $text,
                    'source' => $sourceLang,
                    'target' => $lang,
                    'format' => 'text',
                ]);

                if ($response->ok()) {
                    $translated[$key] = $response['translatedText'];
                    $this->line("✓ $key => {$translated[$key]}");
                } else {
                    $translated[$key] = $text;
                    $this->error("✗ Failed to translate: $key");
                }

                sleep(1); // Rate limit friendly
            }

            $output = "<?php\n\nreturn " . var_export($translated, true) . ";\n";
            file_put_contents(resource_path("lang/{$lang}/messages.php"), $output);
            $this->info("✅ Saved: lang/{$lang}/messages.php");
        }

        $this->info('🎉 Translation complete!');
    }
}
