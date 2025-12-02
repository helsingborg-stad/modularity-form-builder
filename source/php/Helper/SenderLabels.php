<?php

declare(strict_types=1);

namespace ModularityFormBuilder\Helper;

class SenderLabels
{
    /**
     * Translated sender labels
     * @return array
     */
    public static function getLabels()
    {
        $labels = [
            'firstname' => __('Firstname', 'modularity-form-builder'),
            'lastname' => __('Lastname', 'modularity-form-builder'),
            'email' => __('Email', 'modularity-form-builder'),
            'phone' => __('Phone', 'modularity-form-builder'),
            'address' => __('Address', 'modularity-form-builder'),
            'street_address' => __('Street address', 'modularity-form-builder'),
            'postal_code' => __('Postal code', 'modularity-form-builder'),
            'city' => __('City', 'modularity-form-builder'),
        ];

        return $labels;
    }
}
