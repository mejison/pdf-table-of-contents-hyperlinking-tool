<?php

declare(strict_types=1);


use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use \Tests\CreatesApplication, StoresAssetForTests;
}
