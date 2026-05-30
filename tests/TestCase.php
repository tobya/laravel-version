<?php

declare(strict_types=1);

namespace Tests;

use Tobya\Version\Facades\Version;
use Tobya\Version\VersionServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            VersionServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app): array
    {
        return [
            'Version' => Version::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('version.git.enabled', false);
    }
}
