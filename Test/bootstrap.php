<?php
/**
 * This file is part of marcuspi/module-postnummer.
 *
 * Postnummer is a Magento 2 module that facilitates post number searches.
 * Copyright (C) 2018 Marcus Pettersen Irgens
 *
 * This program is free software: you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software
 * Foundation, either version 3 of the License, or (at your option) any later
 * version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
 * PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * this program. If not, see <https://www.gnu.org/licenses/>.
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
