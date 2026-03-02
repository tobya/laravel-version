<?php

declare(strict_types=1);

namespace Eznix86\Version;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;

class VersionLoader
{
    protected string $path;

    public function __construct(?string $path = null)
    {
        $this->path = $path ?? App::basePath('config/app.php');
    }

    /**
     * Load version from the version.json file.
     */
    public function load(): Version
    {
        if (config('app.version',null) != null )
        {
            return new Version(config('app.version') ?? '1.0.0');
        }

        if (File::exists($this->path)) {
            $data = json_decode(File::get($this->path), true);

            return new Version($data['version'] ?? '1.0.0');
        }

        $version = new Version('1.0.0');
        $this->save($version);

        return $version;
    }

    /**
     * Save the version to the version.json file.
     */
    public function save(Version $version): void
    {
        $content = File::get($this->path);

        $updated = $result = preg_replace(
            '/ \'version\'(\s*)=>(\s*)\'(.*)\'/', "'version' => '". $version->get()."'",
            $content);

        File::put($this->path, $updated);
        return;
        File::put($this->path, json_encode([
            'version' => $version->get(),
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)."\n");
    }

    /**
     * Get the path to the version file.
     */
    public function path(): string
    {
        return $this->path;
    }
}
