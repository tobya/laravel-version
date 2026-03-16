<?php

declare(strict_types=1);

return [


    /*
    |--------------------------------------------------------------------------
    | Version 
    |--------------------------------------------------------------------------
    |
    | This value is the version string
    |
    */

    'version' => '1.0.0',

    /*
    |--------------------------------------------------------------------------
    | Version Prefix
    |--------------------------------------------------------------------------
    |
    | This value is prepended to the version string when using the @version
    | Blade directive. Set to an empty string to disable the prefix.
    |
    */

    'prefix' => 'v',




    /*
    |--------------------------------------------------------------------------
    | File Storage
    |--------------------------------------------------------------------------
    |
    | Indicate where to store version string,
    | json file is default, but config file is an option
    |
    */

    'storage' => 'json-file', // json-file or config-file



    /*
    |--------------------------------------------------------------------------
    | Git Integration
    |--------------------------------------------------------------------------
    |
    | When enabled, version bumps will automatically create a git commit and
    | tag. You can customize the commit message and tag format using the
    | {version} placeholder which will be replaced with the new version.
    |
    */

    'git' => [
        'enabled' => true,
        'commit_message' => 'Bump version to {version}',
        'tag_format' => 'v{version}',
    ],


];
