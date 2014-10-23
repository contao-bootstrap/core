<?php

/**
 * @package    contao-bootstrap
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\Bootstrap\Core\Config\Type;

use Netzmacht\Bootstrap\Core\Config\Type;
use Netzmacht\Bootstrap\Core\Contao\Model\BootstrapConfigModel;
use Netzmacht\Bootstrap\Core\Config;

class IconSetType implements Type
{
    /**
     * @param Config $config
     * @param BootstrapConfigModel $model
     * @return mixed
     */
    public function buildConfig(Config $config, BootstrapConfigModel $model)
    {
        $key = 'icons.sets.' . $model->name;

        if ($model->delete) {
            $config->remove($key);
            return;
        }

        $value = array(
            'stylesheet' => $this->getStylesheets($model),
            'template'   => $model->icons_template,
            'path'       => $model->icons_path,
        );

        $config->set($key, $value);
    }

    /**
     * @param $key
     * @param Config $config
     * @param BootstrapConfigModel $model
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
     * @return bool
     */
    public function hasGlobalScope()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isMultiple()
    {
        return true;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return 'icons.sets';
    }

    /**
     * @param BootstrapConfigModel $model
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