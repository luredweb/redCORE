<?php
/**
 * @package     RedRad
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_REDRAD') or die;

/**
 * Controller Form class.
 * Works with a RModelAdmin or a Model using RForm.
 *
 * @package     RedRad
 * @subpackage  Controller
 * @since       1.0
 */
class RControllerForm extends JControllerForm
{
	/**
	 * Validates the form and displays the error per field.
	 *
	 * {
	 *  "error": "error", => global error
	 *  "field_name": "error"
	 * }
	 *
	 * @return  void
	 */
	public function validateFormAjax()
	{
		/** @var RModelAdmin $model */
		$model = $this->getModel();
		$data = $this->input->post->get('jform', array(), 'array');

		$form = $model->getForm($data, false);

		// Filter and validate the form data.
		$data = $form->filter($data);
		$return = $form->validate($data);

		// Prepare the json array.
		$jsonArray = array();

		// Check for an error.
		if ($return instanceof Exception)
		{
			$jsonArray['error'] = $return->getMessage();
		}

		// Check the validation results.
		elseif ($return === false)
		{
			// Get the validation messages from the form.
			foreach ($form->getErrors() as $key => $message)
			{
				if ($message instanceof Exception)
				{
					$jsonArray[$key] = $message->getMessage();
				}

				else
				{
					$jsonArray[$key] = $message;
				}
			}
		}

		echo json_encode($jsonArray);

		JFactory::getApplication()->close();
	}
}