<?php

$app = require __DIR__.'/../bootstrap/app.php';

$request = Illuminate\Http\Request::capture();

$app->run($request);
