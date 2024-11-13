<?php

namespace ModularityFormBuilder\Blade;

use ComponentLibrary\Init as ComponentLibraryInit;
use HelsingborgStad\BladeService\BladeServiceInterface;

class Blade
{
    private BladeServiceInterface $bladeEngine;

    public function __construct(private ComponentLibraryInit $componentLibrary)
    {
        $this->bladeEngine = $this->componentLibrary->getEngine();
    }

    public function render($view, $data = [], $compress = true, $viewPaths = [FORM_BUILDER_MODULE_VIEW_PATH])
    {
        $markup = '';
        $data = array_merge($data, array('errorMessage' => false));

        try {
            $markup = $this->bladeEngine->makeView($view, $data, [], $viewPaths)->render();
        } catch (\Throwable $e) {
            $this->bladeEngine->errorHandler($e)->print();
        }

        if ($compress == true) {
            $replacements = array(
                ["~<!--(.*?)-->~s", ""],
                ["/\r|\n/", ""],
                ["!\s+!", " "]
            );

            foreach ($replacements as $replacement) {
                $markup = preg_replace($replacement[0], $replacement[1], $markup);
            }

            return $markup;
        }

        return $markup;
    }
}