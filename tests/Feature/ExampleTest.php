<?php

use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

uses(LazilyRefreshDatabase::class);

test('the application returns a successful response', function () {
    $this->get('/')->assertRedirect('/scan');

    $this->get('/scan')->assertOk();
});
