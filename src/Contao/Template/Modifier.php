<?php

/**
 * @package   contao-bootstrap
 * @author    David Molineus <david.molineus@netzmacht.de>
 * @license   LGPL 3+
 * @copyright 2013-2015 netzmacht creative David Molineus
 */

namespace Netzmacht\Bootstrap\Core\Contao\Template;

use Netzmacht\Bootstrap\Core\Bootstrap;

/**
 * Class TemplateModifier contains all template modifiers used by bootstrap config.
 *
 * @package Netzmacht\Bootstrap
 */
class Modifier
{

    /**
     * Execute all registered template modifiers.
     *
     * @param \Template $template Current template.
     *
     * @return void
     */
    public function modify(\Template $template)
    {
        if (!Bootstrap::isEnabled()) {
            return;
        }

        foreach ((array) Bootstrap::getConfigVar('templates.modifiers') as $config) {
            if ($config['disabled'] || !$this->isTemplateAffected($template->getName(), (array) $config['templates'])) {
                continue;
            }

            if ($config['type'] == 'replace') {
                if (is_callable($config['replace'])) {
                    $value = call_user_func($config['replace'], $template);
                } else {
                    $value = $config['replace'];
                }

                $template->$config['key'] = str_replace($config['search'], $value, $template->$config['key']);
            } elseif ($config['type'] == 'callback') {
                call_user_func($config['callback'], $template);
            }
        }
    }

    /**
     * Parse current template.
     *
     * @param string $buffer       Parsed template.
     * @param string $templateName Name of the template.
     *
     * @return string
     */
    public function parse($buffer, $templateName)
    {
        if (!Bootstrap::isEnabled()) {
            return $buffer;
        }

        foreach ((array) Bootstrap::getConfigVar('templates.parsers') as $config) {
            if ($config['disabled'] || !$this->isTemplateAffected($templateName, (array) $config['templates'])) {
                continue;
            }

            if ($config['type'] == 'replace') {
                if (is_callable($config['replace'])) {
                    $value = call_user_func($config['replace'], $buffer, $templateName);
                } else {
                    $value = $config['replace'];
                }

                $buffer = str_replace($config['search'], $value, $buffer);
            } elseif ($config['type'] == 'callback') {
                $buffer = call_user_func($config['callback'], $buffer, $templateName);
            }
        }

        return $buffer;
    }

    /**
     * Consider if template is affected.
     *
     * @param string $template  Name of current template.
     * @param array  $templates Defined templates of the modifier.
     *
     * @return bool
     */
    protected function isTemplateAffected($template, $templates)
    {
        foreach ($templates as $config) {
            if ($template == $config) {
                return true;
            } elseif (substr($config, -1) == '*'
                && 0 == strcasecmp(substr($config, 0, -1), substr($template, 0, (strlen($config) - 1)))
            ) {
                return true;
            }

        }

        return false;
    }
}
