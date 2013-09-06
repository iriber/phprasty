<?php
namespace Rasty\render;

use Rasty\components\AbstractComponent;

class HTMLRenderer implements IRenderer{

	public function render(AbstractComponent $component){
			echo $component->render();
	}
}
?>