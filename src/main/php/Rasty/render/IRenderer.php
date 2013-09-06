<?php

namespace Rasty\render;

use Rasty\components\AbstractComponent;

interface IRenderer{

	public function render(AbstractComponent $component);
}
?>