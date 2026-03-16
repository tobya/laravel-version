<?php

declare(strict_types=1);

namespace Eznix86\Version;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;

class VersionLoader
{
    protected string $path;

    protected string $storageType;

    public function __construct(?string $path = null)
    {
        $this->storageType = config('version.storage') ;

        if ($this->storageType == 'json-file')
        {
          $this->path = $path ?? App::basePath('version.json');
        }
        else  if ($this->storageType == 'config-file')
        {
          $this->path = $path ?? App::basePath('config/version.php');
        }


    }

    /**
     * Load version from the storage file.
     */
    public function load(): Version
    {

      if ($this->storageType == 'json-file')
      {
        return $this->loadFromJson();
      }
      else  // if ($this->storageType == 'config-file')
      {
        return $this->loadFromConfig();
      }

    }

  /**
   * Load version from Config file
   * @return Version
   */
    public function loadFromConfig() : Version
    {
         return new Version(config('version.version') ?? '1.0.0');
    }

  /**
   * Load version from version.json file.
   * @return Version
   */
    public function loadFromJson() : Version
    {
        if (File::exists($this->path)) {
            $data = json_decode(File::get($this->path), true);

            return new Version($data['version'] ?? '1.0.0');
        }

        $version = new Version('1.0.0');
        $this->save($version);

        return $version;
    }

    /**
     * Save the version to the selected file.
     */
    public function save(Version $version): void
    {
        $content = File::get($this->path);

        // Find Version => '1.0.0' string in app.config
        // PREG  'version'(\s*)=>(\s*)'(.*)'
        $updated = $result = preg_replace(
            '/\'version\'(\s*)=>(\s*)\'(.*)\'/', "'version' => '". $version->get()."'",
            $content);

        File::put($this->path, $updated);
        return;
        File::put($this->path, json_encode([
            'version' => $version->get(),
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)."\n");
    }

    public function saveToConfig(Version $version)
    {
       $content = File::get($this->path);

        // Find
        // Version => '1.0.0'
        // string in version config and replace
        // PREG  'version'(\s*)=>(\s*)'(.*)'
        $updated = $result = preg_replace(
            '/\'version\'(\s*)=>(\s*)\'(.*)\'/',
            "'version' => '". $version->get()."'",
            $content);

        File::put($this->path, $updated);

    }

    public function saveToJson(Version $version) : void
    {

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
