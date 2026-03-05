<?php

declare(strict_types=1);

it('boots the package service provider', function (): void {
    expect(app()->providerIsLoaded(\Octowire\SafeMigrations\SafeMigrationsServiceProvider::class))->toBeTrue();
});