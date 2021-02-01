<?php

namespace EAddonsQueryMedia\Modules\Query\Skins;

use Elementor\Controls_Manager;
use EAddonsForElementor\Modules\Query\Skins\Base;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Grid Skin
 *
 * Elementor widget query-posts for e-addons
 *
 */
class Justifiedgrid extends Base {

    public function _register_controls_actions() {
        
        parent::_register_controls_actions();
        add_action( 'elementor/element/'.$this->parent->get_name().'/section_e_query/after_section_end', [ $this, 'register_additional_justifiedgrid_controls' ], 20 );
        add_action( 'elementor/element/'.$this->parent->get_name().'/section_items/before_section_start', [ $this, 'register_reveal_controls' ], 20 );
    }
    
    public function get_script_depends() {
        return ['imagesloaded', 'justifiedgallery', 'e-addons-query-justifiedgrid'];
    }

    public function get_style_depends() {
        return ['e-addons-common-query', 'justifiedgallery'];
    }
    
    public function get_id() {
        return 'justifiedgrid';
    }

    public function get_title() {
        return __('Justified Grid', 'e-addons');
    }

    
    public function get_icon() {
        return 'eadd-gallery-grid-justified';
    }

    public function register_additional_justifiedgrid_controls() {
        //var_dump($this->get_id());
        //var_dump($this->parent->get_settings('_skin')); //->get_current_skin()->get_id();

        $this->start_controls_section(
            'section_justifiedgrid', [
                'label' => '<i class="eaddicon eadd-gallery-grid-justified"></i> ' . __('Justified Grid', 'e-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );
        // ***************** JUSTIFIED
        $this->add_control(
            'justified_rowHeight', [
                'label' => __('Row Height', 'e-addons'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => '',
                ],
                'range' => [
                    'px' => [
                        'min' => 150,
                        'max' => 800,
                        'step' => 1
                    ],
                ],
                'frontend_available' => true
            ]
        );
        $this->add_control(
            'justified_margin', [
                'label' => __('Images space', 'e-addons'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => '',
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1
                    ],
                ],
                'frontend_available' => true
            ]
        );
        $this->add_control(
            'justified_lastRow', [
                'label' => __('Last row', 'e-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'justify',
                'options' => [
                    'justify' => 'Justify',
                    'nojustify' => 'No-Justify',
                    'left' => 'Left',
                    'center' => 'Center',
                    'right' => 'Right',
                    'hide' => 'Hide',
                ],
                'frontend_available' => true
            ]
            
        );
        
        $this->end_controls_section();
    }
    
    public function get_scrollreveal_class() {
        if ($this->get_instance_value('scrollreveal_effect_type'))
            return 'reveal-effect reveal-effect-' . $this->get_instance_value('scrollreveal_effect_type');
    }
    // Classes ----------
	public function get_container_class() {
		return 'e-add-skin-' . $this->get_id();
	}
    public function get_wrapper_class() {
        return 'e-add-wrapper-' . $this->get_id();
    }
    public function get_item_class() {
        return 'e-add-item-' . $this->get_id();
    }
    

    /* public function render() {

      echo 'is:'.$this->get_id().' skin:'.$this->parent->get_settings('_skin');
      var_dump($this->parent->get_script_depends());
      } */
}
