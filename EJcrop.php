<?php

/**
 * Jcrop Yii extension
 * 
 * Select a cropping area fro an image using the Jcrop jQuery tool and crop
 * it using PHP's GD functions.
 *
 * @copyright © Digitick <www.digitick.net> 2011
 * @license GNU Lesser General Public License v3.0
 * @author Jacques Basseck
 * @author Ianaré Sévi
 *
 */
Yii::import('zii.widgets.jui.CJuiWidget');

/**
 * Base class.
 */
class EJcrop extends CJuiWidget
{
	/**
	 * @var array An array conteniing an url of the picture to crop
	 * required
	 */
	public $url;
	/**
	 * @var string A jQuery selector used to apply the widget to the element(s).
	 * Use this to have the elements keep their binding when the DOM is manipulated
	 * by Javascript, ie ajax calls or cloning.
	 * Can also be useful when there are several elements that share the same settings,
	 * to cut down on the amount of JS injected into the HTML.
	 */
	public $scriptSelector;
	/**
	 * @var type Alternate text for the full size image image
	 * required
	 */
	public $alt;
	/**
	 * @var array to set buttons options
	 */
	public $buttonOptions = array();
	
	/**
	 * 
	 */
	public function run()
	{
		$assets = Yii::app()->getAssetManager()->publish(dirname(__FILE__) . '/assets');
		
		if(!isset($this->htmlOptions['id'])) {
			$this->htmlOptions['id'] = $this->getId();
		}
		$id = $this->htmlOptions['id'];
		
		echo CHtml::image($this->url, $this->alt, $this->htmlOptions);
		
		if (!empty($this->buttonOptions)) {
			echo '<div class="jcrop-buttons">'.
			CHtml::button($this->buttonOptions['startLabel'], array('id' => 'button_' . $id, 'class' => 'jcrop-start'));
			echo CHtml::button($this->buttonOptions['cropLabel'], array('id' => 'submit_' . $id, 'style' => 'display:none;', 'class' => 'jcrop-crop'));
			echo CHtml::button($this->buttonOptions['cancelLabel'], array('id' => 'cancel_' . $id, 'style' => 'display:none;', 'class' => 'jcrop-cancel')).
			'</div>';
		}
		echo CHtml::hiddenField($id . '_x', 0, array('class' => 'coords'));
		echo CHtml::hiddenField($id . '_y', 0, array('class' => 'coords'));
		echo CHtml::hiddenField($id . '_w', 0, array('class' => 'coords'));
		echo CHtml::hiddenField($id . '_h', 0, array('class' => 'coords'));
		echo CHtml::hiddenField($id . '_x2', 0, array('class' => 'coords'));
		echo CHtml::hiddenField($id . '_y2', 0, array('class' => 'coords'));
		
		$cls = Yii::app()->getClientScript();
		$cls->registerScriptFile($assets . '/jquery.Jcrop.min.js');
		$cls->registerScriptFile($assets . '/ejcrop.js', CClientScript::POS_HEAD);

		$this->options['onChange'] = "js:function(c) { getCoords(c,'{$id}'); showThumb(c,'{$id}');}";
		
		$this->options = array_merge($this->defaultOptions, $this->options);
		$options = CJavaScript::encode($this->options);
		
		if (!empty($this->buttonOptions)) {
			$js = "jcrop_initWithButtons('{$id}', {$options});";
		}
		else {
			$js = "jQuery('#{$id}').jcrop({$options});";
		}
		$cls->registerScript(__CLASS__ . '#' . $id, $js, CClientScript::POS_READY);
	}
}
