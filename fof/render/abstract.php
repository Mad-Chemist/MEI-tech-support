<?php
/**
 * @package    FrameworkOnFramework
 * @copyright  Copyright (C) 2010 - 2012 Akeeba Ltd. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

/**
 * ABstract view renderer class
 */
abstract class BBDFOFRenderAbstract
{

	/** @var int Priority of this renderer. Higher means more important */
	protected $priority = 50;

	/** @var int Is this renderer enabled? */
	protected $enabled = false;

	/**
	 * Returns the information about this renderer
	 *
	 * @return object
	 */
	public function getInformation()
	{
		return (object) array(
				'priority'	 => $this->priority,
				'enabled'	 => $this->enabled,
		);
	}

	/**
	 * Echoes any HTML to show before the view template
	 *
	 * @param string $view The current view
	 * @param string $task The current task
	 * @param array $input The input array (request parameters)
	 * @param array $config The view configuration array
	 */
	abstract public function preRender($view, $task, $input, $config = array());

	/**
	 * Echoes any HTML to show after the view template
	 *
	 * @param string $view The current view
	 * @param string $task The current task
	 * @param array $config The view configuration array
	 */
	abstract public function postRender($view, $task, $input, $config = array());

	/**
	 * Renders a BBDFOFForm and returns the corresponding HTML
	 *
	 * @param   BBDFOFForm   $form      The form to render
	 * @param   BBDFOFModel  $model     The model providing our data
	 * @param   BBDFOFInput  $input     The input object
	 * @param   string    $formType  The form type: edit, browse or read
	 *
	 * @return  string    The HTML rendering of the form
	 */
	public function renderForm(BBDFOFForm &$form, BBDFOFModel $model, BBDFOFInput $input, $formType = null)
	{
		if (is_null($formType))
		{
			$formType = $form->getAttribute('type', 'edit');
		}
		else
		{
			$formType = strtolower($formType);
		}
		switch ($formType)
		{
			case 'browse':
				return $this->renderFormBrowse($form, $model, $input);
				break;

			case 'read':
				return $this->renderFormRead($form, $model, $input);
				break;

			default:
				return $this->renderFormEdit($form, $model, $input);
				break;
		}
	}

	/**
	 * Renders a BBDFOFForm for a Browse view and returns the corresponding HTML
	 *
	 * @param   BBDFOFForm   $form      The form to render
	 * @param   BBDFOFModel  $model     The model providing our data
	 * @param   BBDFOFInput  $input     The input object
	 *
	 * @return  string    The HTML rendering of the form
	 */
	abstract protected function renderFormBrowse(BBDFOFForm &$form, BBDFOFModel $model, BBDFOFInput $input);

	/**
	 * Renders a BBDFOFForm for a Browse view and returns the corresponding HTML
	 *
	 * @param   BBDFOFForm   $form      The form to render
	 * @param   BBDFOFModel  $model     The model providing our data
	 * @param   BBDFOFInput  $input     The input object
	 *
	 * @return  string    The HTML rendering of the form
	 */
	abstract protected function renderFormRead(BBDFOFForm &$form, BBDFOFModel $model, BBDFOFInput $input);

	/**
	 * Renders a BBDFOFForm for a Browse view and returns the corresponding HTML
	 *
	 * @param   BBDFOFForm   $form      The form to render
	 * @param   BBDFOFModel  $model     The model providing our data
	 * @param   BBDFOFInput  $input     The input object
	 *
	 * @return  string    The HTML rendering of the form
	 */
	abstract protected function renderFormEdit(BBDFOFForm &$form, BBDFOFModel $model, BBDFOFInput $input);
}