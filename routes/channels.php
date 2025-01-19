<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('CommonMessage', function () {
    return true;
});
