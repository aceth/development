<?php
include_once 'constant.php';

// Core_Application
require_once LIBRARY_PATH . '/Core/Application.php';

set_include_path(LIBRARY_PATH);

Core_Application::getInstance(APPLICATION_ENV, include_once APPLICATION_PATH . '/config/app.php')->run();