<?php
import('lib.pkp.classes.plugins.GenericPlugin');
class CorporateNameFieldPlugin extends GenericPlugin {
    /**
     * Get the display name of this plugin
     * 
     * The name will appear in the plugins list where editors can
     * enable and disable plugins.
     * 
     * @return string
     */
    public function getDisplayName() {
        return __('plugins.generic.corporateNameField.displayName');
    }

    /**
     * Get the description of this plugin
     * 
     * The description will appear in the plugins list where editors can
     * enable and disable plugins.
     * @return string
     */
    public function getDescription() {
        return __('plugins.generic.corporateNameField.description');
    }

    /**
     * @copydoc Plugin::register()
     */
	public function register($category, $path, $mainContextId = NULL) {

        // Register the plugin even when it is not enabled
        $success = parent::register($category, $path);

		if ($success && $this->getEnabled()) {
            // Do something when the plugin is enabled
        }

		return $success;
	}
}