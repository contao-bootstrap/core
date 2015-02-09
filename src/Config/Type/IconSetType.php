<?php

/**
 * @package   contao-bootstrap
 * @author    David Molineus <david.molineus@netzmacht.de>
 * @license   LGPL 3+
 * @copyright 2013-2015 netzmacht creative David Molineus
 */

namespace Netzmacht\Bootstrap\Core\Config\Type;

use Netzmacht\Bootstrap\Core\Config\Type;
use Netzmacht\Bootstrap\Core\Contao\Model\BootstrapConfigModel;
use Netzmacht\Bootstrap\Core\Config;

/**
 * Class IconSetType handles icon set configuration.
 *
 * @package Netzmacht\Bootstrap\Core\Config\Type
 */
class IconSetType implements Type
{
    /**
     * {@inheritdoc}
     */
    public function buildConfig(Config $config, BootstrapConfigModel $model)
    {
        $key = 'icons.sets.' . $model->name;

        if ($model->delete) {
            $config->remove($key);

            return;
        }

        $theme = $model->getRelated('pid');
        $value = array(
            'label'      => $model->name . ($theme ? (' (' . $theme->name . ')') : ''),
            'stylesheet' => $this->getStylesheets($model),
            'template'   => $model->icons_template,
            'path'       => $model->icons_path,
        );

        $config->set($key, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function extractConfig($key, Config $config, BootstrapConfigModel $model)
    {
        if ($config->has($key)) {
            $config = $config->get($key);

            $keys = explode('.', $key);

            $model->name           = end($keys);
            $model->icons_template = $config['template'];
            $model->icons_paths    = implode("\n", (array) $config['paths']);
            $model->icons_path     = $config['path'];
            $model->icons_source   = 'paths';
        }
    }

    /**
     * {@inheritdoc}
     */
    public function hasGlobalScope()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isMultiple()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getPath()
    {
        return 'icons.sets';
    }

    /**
     * {@inheritdoc}
     */
    public function isNameEditable()
    {
        return true;
    }

    /**
     * Get stylesheets from the model.
     *
     * @param BootstrapConfigModel $model Config model.
     *
     * @return array
     */
    private function getStylesheets(BootstrapConfigModel $model)
    {
        if ($model->icons_source == 'files') {
            $fileIds = deserialize($model->icons_files, true);
            $files   = \FilesModel::findMultipleByUuids($fileIds);

            if (!$files) {
                return array();
            }

            return $files->fetchEach('path');
        }

        return explode("\n", $model->icons_paths);
    }
}
