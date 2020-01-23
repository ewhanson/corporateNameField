<?php

/**
 * @file plugins/generic/corporateNameField/CorporateNameFieldPlugin.inc.php
 *
 * Copyright (c) 2014-2020 Simon Fraser University
 * Copyright (c) 2003-2020 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class CorporateNameFieldPlugin
 *
 * @brief Plugin that adds a corporate name field for contributors.
 */
import('lib.pkp.classes.plugins.GenericPlugin');

class CorporateNameFieldPlugin extends GenericPlugin {
    /**
     * @copydoc Plugin::register()
     */
	public function register($category, $path, $mainContextId = NULL) {

        // Register the plugin even when it is not enabled
        $success = parent::register($category, $path);

		if ($success && $this->getEnabled()) {
            HookRegistry::register('Schema::get::author', array($this, 'addToSchema'));
            HookRegistry::register('authorform::initdata', array($this, 'initFormData'));
            HookRegistry::register('authorform::readuservars', array($this, 'addUserVars'));
            HookRegistry::register('authorform::execute', array($this, 'executeForm'));
        }

		return $success;
    }

    /**
	 * Add a property to the author schema.
	 *
	 * @param $hookName string `Schema::get::author`
	 * @param $schema object Author schema
	 */
    public function addToSchema($hookName, $schema) {
        $prop = '{
            "type": "string",
            "multilingual": "true",
			"apiSummary": true,
			"validation": [
				"nullable"
			]
		}';
        $schema->properties->corporateName = json_decode($prop);
    }

    /**
     * Prepares initial input into the form.
     * 
     * @param $hookName string `authorform::initdata`
     * @param $args array Arguments passed by hook
     */
    public function initFormData($hookName, $args) {
        $form = $args[0];
        $author = $form->getAuthor();

        // If there is an author, get corporate name field from author
        if ($author) { 
            $form->_data['corporateName'] = $author->getData('corporateName', null);
        }
    }

    /**
     * Adds corporate name variable to input data.
     * 
     * @param $hookName string `authorform::readuservars`
     * @param $args array Arguments passed by hook
     */
    public function addUserVars($hookname, $args) {
        $vars = &$args[1];
        array_push($vars,'corporateName');
    }

    /**
     * Executes form's action with corporate name data.
     * 
     * @param $hookName string `authorform::execute`
     * @param $args array Arguments passed by hook
     */
    public function executeForm($hookName, $args) {
        $form = &$args[0];
        $author = $form->getAuthor();
        // Do this but wihtout setter
        // $author->setPreferredPublicName($this->getData('preferredPublicName'), null);
        $author->setData('corporateName',$form->getData('corporateName'), null);
    }
    
    /**
	 * @copydoc PKPPlugin::getDisplayName
	 */
    public function getDisplayName() {
        return __('plugins.generic.corporateNameField.displayName');
    }

    /**
	 * @copydoc PKPPlugin::getDescription
	 */
    public function getDescription() {
        return __('plugins.generic.corporateNameField.description');
    }
}