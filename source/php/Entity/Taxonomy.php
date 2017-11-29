<?php

namespace ModularityFormBuilder\Entity;

class Taxonomy
{
    public $nameSingular;
    public $namePlural;
    public $slug;
    public $args;
    public $postTypes;

    public function __construct($nameSingular, $namePlural, $slug, $postTypes, $args)
    {
        $this->nameSingular = $nameSingular;
        $this->namePlural = $namePlural;
        $this->slug = $slug;
        $this->args = $args;
        $this->postTypes = $postTypes;

        add_action('init', array($this, 'registerTaxonomy'), 9);
    }

    public function registerTaxonomy() : string
    {
        $labels = array(
            'name'              => $this->namePlural,
            'singular_name'     => $this->nameSingular,
            'search_items'      => sprintf(__('Search %s', 'modularity-form-builder'), $this->namePlural),
            'all_items'         => sprintf(__('All %s', 'modularity-form-builder'), $this->namePlural),
            'parent_item'       => sprintf(__('Parent %s:', 'modularity-form-builder'), $this->nameSingular),
            'parent_item_colon' => sprintf(__('Parent %s:', 'modularity-form-builder'), $this->nameSingular) . ':',
            'edit_item'         => sprintf(__('Edit %s', 'modularity-form-builder'), $this->nameSingular),
            'update_item'       => sprintf(__('Update %s', 'modularity-form-builder'), $this->nameSingular),
            'add_new_item'      => sprintf(__('Add New %s', 'modularity-form-builder'), $this->nameSingular),
            'new_item_name'     => sprintf(__('New %s Name', 'modularity-form-builder'), $this->nameSingular),
            'menu_name'         => $this->namePlural,
        );

        $this->args['labels'] = $labels;

        register_taxonomy($this->slug, $this->postTypes, $this->args);
        return $this->slug;
    }
}
