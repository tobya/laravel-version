<?php

declare(strict_types=1);

namespace Eznix86\Version;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use JsonException;

class VersionLoader
{
    protected string $path;

    protected string $storageType;

    public function __construct(?string $path = null)
    {
        $this->storageType = config('version.storage') ;

        if ($this->storageType == 'config-file')
        {
          $this->path = $path ?? App::basePath('config/version.php');
        }
        else
        {
          $this->path = $path ?? App::basePath('version.json');
        }


    }

    /**
     * Load version from the storage file.
     */
    public function load(): Version
    {

      if ($this->storageType == 'config-file')
      {
        return $this->loadFromConfig();
      }
      else
      {
        return $this->loadFromJson();
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
            $data = json_decode(File::get($this->path), true, 512, JSON_THROW_ON_ERROR);

            if (! is_array($data)) {
                throw new JsonException('The version file must decode to a JSON object.');
            }

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
       if ($this->storageType == 'config-file'){
         $this->saveToConfig($version);
       }
       else
       {
         $this->saveToJson($version);
       }
    }

  /**
   * Save the version to the Laravel config file
   * This involves finding via regex and replacing.
   * @param Version $version
   * @return void
   */
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

  /**
   * Save the version to the version.json file
   * @param Version $version
   * @return void
   */
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
