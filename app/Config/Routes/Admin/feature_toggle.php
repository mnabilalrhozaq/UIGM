<?php

/**
 * Feature Toggle Routes
 * URL: /admin-pusat/feature-toggle
 */

$routes->get('feature-toggle', 'Admin\\FeatureToggle::index');
$routes->post('feature-toggle/toggle', 'Admin\\FeatureToggle::toggle');
$routes->post('feature-toggle/bulk-toggle', 'Admin\\FeatureToggle::bulkToggle');
$routes->post('feature-toggle/update-config', 'Admin\\FeatureToggle::updateConfig');
$routes->get('feature-toggle/logs', 'Admin\\FeatureToggle::logs');
