<?php

// autoload_psr4.php @generated by Composer

$vendorDir = dirname(__DIR__);
$baseDir = dirname($vendorDir);

return array(
    'WPEverest\\URMembership\\Payment\\' => array($baseDir . '/modules/payment-history'),
    'WPEverest\\URMembership\\' => array($baseDir . '/modules/membership/includes'),
    'WPEverest\\URM\\DiviBuilder\\' => array($baseDir . '/includes/3rd-party/DiviBuilder'),
    'Stripe\\' => array($vendorDir . '/stripe/stripe-php/lib'),
    'Composer\\Installers\\' => array($vendorDir . '/composer/installers/src/Composer/Installers'),
);
