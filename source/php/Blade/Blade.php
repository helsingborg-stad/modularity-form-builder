<?php

declare(strict_types=1);

namespace ModularityFormBuilder\Blade;

use ComponentLibrary\Init as ComponentLibraryInit;
use Municipio\Helper\ComponentBladeService;

class Blade
{
    public function render($view, $data = [], $compress = true, $viewPaths = [FORM_BUILDER_MODULE_VIEW_PATH])
    {
        $markup = '';
        $data = array_merge($data, ['errorMessage' => false]);

        $bladeEngine = class_exists(ComponentBladeService::class)
            ? ComponentBladeService::create($viewPaths)
            : (new ComponentLibraryInit($viewPaths))->getEngine();

        try {
            $markup = $bladeEngine->makeView($view, $data, [], $viewPaths)->render();
        } catch (\Throwable $e) {
            $bladeEngine->errorHandler($e)->print();
        }

        if ($compress == true) {
            $replacements = [
                ['~<!--(.*?)-->~s', ''],
                ["/\r|\n/",         ''],
                ["!\s+!",           ' '],
            ];

            foreach ($replacements as $replacement) {
                $markup = preg_replace($replacement[0], $replacement[1], $markup);
            }

            return $markup;
        }

        return $markup;
    }
}
