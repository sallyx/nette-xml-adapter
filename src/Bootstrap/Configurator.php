<?php

/**
 * Copyright (c) 2015 Petr Bilek (http://ww.sallyx.org)
 */

namespace Sallyx\Nette;

use Nette;

/**
 * This class extends Nette\Configurator with addAdapter method.
 * Reading and generating XML files.
 */
class Configurator extends Nette\Configurator
{
	use AdapterManager;
}
