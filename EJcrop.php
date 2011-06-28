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
	 * @var string URL of the picture to crop.
	 */
	public $url;
	/**
	 * @var type Alternate text for the full size image image.
	 */
	public $alt;
	/**
	 * @var array to set buttons options
	 */
	public $buttonOptions = array();
	/**
	 * @var string URL for the AJAX request
	 */
	public $ajaxUrl;
	/**
	 * @var array Extra parameters to send with the AJAX request.
	 */
	public $ajaxParams = array();
	

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

		$this->options['onChange'] = "js:function(c) {ejcrop_getCoords(c,'{$id}'); ejcrop_showThumb(c,'{$id}');}";
		$this->options['ajaxUrl'] = $this->ajaxUrl;
		$this->options['ajaxParams'] = $this->ajaxParams;
		
		$options = CJavaScript::encode($this->options);
		
		if (!empty($this->buttonOptions)) {
			$js = "ejcrop_initWithButtons('{$id}', {$options});";
		}
		else {
			$js = "jQuery('#{$id}').jcrop({$options});";
		}
		$cls->registerScript(__CLASS__ . '#' . $id, $js, CClientScript::POS_READY);
	}
}
