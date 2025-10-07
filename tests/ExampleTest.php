<?php

it('can test', function () {
    expect(true)->toBeTrue();
    //    expect(\Kuzry\Przelewy24\Przelewy24ServiceProvider::class)->toBe('Kuzry\Przelewy24\Przelewy24ServiceProvider');
    $p = new \Kuzry\Przelewy24\Przelewy24ServiceProvider(app());

});
