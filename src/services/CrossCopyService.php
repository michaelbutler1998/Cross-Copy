<?php
/**
 * Cross Copy plugin for Craft CMS 3.x
 *
 * Copy a matrix from one project to another
 *
 * @link      michael@ipopdigital.com
 * @copyright Copyright (c) 2019 Michael Butler
 */

namespace crosscopy\crosscopy\services;

use crosscopy\crosscopy\CrossCopy;

use Craft;
use craft\base\Component;

/**
 * CrossCopyService Service
 *
 * All of your plugin’s business logic should go in services, including saving data,
 * retrieving data, etc. They provide APIs that your controllers, template variables,
 * and other plugins can interact with.
 *
 * https://craftcms.com/docs/plugins/services
 *
 * @author    Michael Butler
 * @package   CrossCopy
 * @since     1.0.0
 */
class CrossCopyService extends Component
{
    // Public Methods
    // =========================================================================

    /**
     * This function can literally be anything you want, and you can have as many service
     * functions as you want
     *
     * From any other plugin file, call it like this:
     *
     *     CrossCopy::$plugin->crossCopyService->exampleService()
     *
     * @return mixed
     */
    public function exampleService()
    {
        $result = 'something';
        // Check our Plugin's settings for `someAttribute`
        if (CrossCopy::$plugin->getSettings()->someAttribute) {
        }

        return $result;
    }
}
