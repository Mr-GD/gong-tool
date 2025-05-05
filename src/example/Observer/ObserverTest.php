<?php

use gong\example\Observer\AObserver;
use gong\tool\Observer\Action;

$action = new Action();
$action->register(new AObserver(['username' => 1]));