<?php
/**
 * This file is part of marcuspi/module-postnummer.
 *
 * Postnummer is a Magento 2 module that facilitates post number searches.
 *
 * @author Marcus Pettersen Irgens <marcus.pettersen.irgens@gmail.com>
 * @copyright 2018 Marcus Pettersen Irgens
 * @license GPL-3.0-or-later GNU General Public License version 3
 */

/**
 * Finds the Magento test bootstrapping files to initialize the testing library.
 */

$directory = realpath(__DIR__);

$magentoDir = null;

if (mb_strpos($directory, "/app/code/Marcuspi") !== false) {
    $magentoDir = realpath($directory . "/../../../../../");
} elseif (mb_strpos($directory, "/vendor/marcuspi") !== false) {
    $magentoDir = realpath($directory . "/../../../../");
}

if (is_null($magentoDir)) {
    throw new \Exception("Could not find Magento install directory");
}

$bootstrapDir =  $magentoDir . "/dev/tests/unit/framework";
$bootstrapFile = $bootstrapDir . "/bootstrap.php";

if (!file_exists($bootstrapFile) || !is_readable($bootstrapFile)) {
    throw new \Exception("Could not find Magento Unit Test bootstrap file");
}

require_once $bootstrapFile;
