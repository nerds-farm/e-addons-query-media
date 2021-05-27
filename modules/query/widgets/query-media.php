<?php

namespace EAddonsQueryMedia\Modules\Query\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Repeater;
use EAddonsForElementor\Base\Base_Widget;
use EAddonsForElementor\Core\Utils;
use EAddonsForElementor\Core\Utils\Query as Query_Utils;
use EAddonsForElementor\Modules\Query\Base\Query as Base_Query;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * Query Posts (L'idea potrebbe essere che altri estendono query_base come: query_terms e query_users )
 *
 * Elementor widget for E-Addons
 *
 */
class Query_Media extends Base_Query {

    public function __construct($data = [], $args = null) {
        parent::__construct($data, $args);
        //$this->register_script('assets/js/e-addons-query-grid.js'); // from module folder
        //$this->register_style('assets/css/e-addons-query-grid.css'); // from module folder
    }

    public function get_pid() {
        return 8349;
    }

    public function get_name() {
        return 'e-query-media';
    }

    public function get_title() {
        return __('Query Media', 'e-addons');
    }

    public function get_description() {
        return __('The widget Query media', 'e-addons');
    }

    public function get_icon() {
        return 'eadd-query-media';
    }

    protected $querytype = 'attachment';

    protected function _register_skins() {
        $this->add_skin(new \EAddonsForElementor\Modules\Query\Skins\Grid($this));
        $this->add_skin(new \EAddonsForElementor\Modules\Query\Skins\Carousel($this));
        $this->add_skin(new \EAddonsForElementor\Modules\Query\Skins\Dualslider($this));
        $this->add_skin(new \EAddonsQueryMedia\Modules\Query\Skins\Justifiedgrid($this));
        //$this->add_skin( new \EAddonsQuery\Modules\Query\Skins\Gridfilters( $this ) );
        //$this->add_skin( new \EAddonsQuery\Modules\Query\Skins\Timeline( $this ) );
        $this->add_skin(new \EAddonsForElementor\Modules\Query\Skins\Table($this));
        $this->add_skin(new \EAddonsForElementor\Modules\Query\Skins\Simple_List( $this ));
        $this->add_skin(new \EAddonsQuery\Modules\Query\Skins\Horizontal_Scroll($this));       
    }

    protected function _register_controls() {
        parent::_register_controls();

        $types = Utils::get_post_types();
        $taxonomies = Utils::get_taxonomies();

        // ------------------------------------------------------------------ [SECTION ITEMS]
        $this->start_controls_section(
                'section_items', [
            'label' => '<i class="eaddicon eicon-radio" aria-hidden="true"></i> ' . __('Media Items', 'e-addons'),
            'condition' => [
                '_skin!' => ['nextpost'],
                'style_items!' => 'template',
            ],
                ]
        );

        ////////////////////////////////////////////////////////////////////////////////
        // -------- ORDERING & DISPLAY items
        $repeater = new Repeater();
        /*
          //item_image
          item_date
          item_title
          item_termstaxonomy
          item_alternativetext
          item_caption
          item_content
          item_author
          item_readmore
          item_custommeta
          item_imagemeta
          item_mimetype
          item_label
          //da valutare: uploaded_to ...

         */
        $repeater->add_control(
                'item_type', [
            'label' => __('Item type', 'e-addons'),
            'type' => Controls_Manager::SELECT,
            'options' => [
                //'item_image' => __('Image', 'e-addons'),
                'item_title' => __('Title', 'e-addons'),
                'item_alternativetext' => __('Alternative Text', 'e-addons'),
                'item_caption' => __('Caption', 'e-addons'),
                'item_content' => __('Description', 'e-addons'),
                'item_uploadedto' => __('Uploaded to', 'e-addons'),
                'item_date' => __('Date', 'e-addons'),
                'item_termstaxonomy' => __('Taxonomy Terms', 'e-addons'),
                'item_author' => __('Author', 'e-addons'),
                'item_imagemeta' => __('Image Meta', 'e-addons'),
                'item_mimetype' => __('Mime Type', 'e-addons'),
                'item_custommeta' => __('Custom Fields', 'e-addons'),
                'item_readmore' => __('Read More', 'e-addons'),
                'item_label' => __('Label', 'e-addons'),
                'item_template' => __('Template', 'e-addons'),
            ],
            'default' => '',
                ]
        );

        // TABS ----------
        $repeater->start_controls_tabs('items_repeater_tab');

        $repeater->start_controls_tab('tab_content', [
            'label' => __('Content', 'e-addons'),
        ]);

        // CONTENT - TAB
        
        // +********************* Common
        $this->controls_items_common_content($repeater);
        
        // +********************* Label
        $this->controls_items_label_content($repeater);

        // +********************* Template
        $this->controls_items_template_content($repeater);

        // +********************* Image
        //$this->controls_items_image_content($repeater,'attachment' );
        // +********************* Title
        $this->controls_items_title_content($repeater, 'attachment');

        // +********************* Date
        $this->controls_items_date_content($repeater, 'attachment');

        // +********************* Terms of Taxonomy [metadata] (Category, Tag, CustomTax)
        $this->controls_items_termstaxonomy_content($repeater);

        // +********************* Content/Excerpt
        $this->controls_items_contentdescription_content($repeater, 'attachment');

        // +********************* ReadMore
        $this->controls_items_readmore_content($repeater);

        // +********************* Author-box user
        $this->controls_items_authorbox_content($repeater);

        // +********************* CustoFields (ACF, Pods, Toolset, Metabox)
        $this->custommeta_items($repeater, 'attachment');

        // +********************* ImageMeta (Dimension, Size, File, [copyright, iso, focal, ecc])
        $this->controls_items_imagemeta_content($repeater);


        $repeater->end_controls_tab();

        $repeater->start_controls_tab('tab_style', [
            'label' => __('Style', 'e-addons'),
            'condition' => [
                'item_type!' => 'item_template'
            ]
        ]);

        // STYLE - TAB (9)
        // +********************* Image
        // +********************* Title
        // +********************* Date
        // +********************* Terms of Taxonomy (Category, Tag, CustomTax)
        // +********************* Content/Excerpt
        // +********************* ReadMore
        // +********************* Author user
        // +********************* Post Type
        // +********************* CustoFields (ACF, Pods, Toolset, Metabox)
        // --------------- BASE
        //@p le caratteristiche grafiche base:
        //  - text-align, flex-align, typography, space 
        $this->controls_items_base_style($repeater);

        // --------------- AUTHOR BOX
        //@p le carateristiche grafche dell'auhor-box per il widget post
        $this->controls_items_author_style($repeater);

        // -------- COLORS
        //@p le carateristiche grafche del colore testi e background
        $this->controls_items_colors_style($repeater);

        // --------------- LABEL BEFORE
        //@p le caratteristiche grafiiche ddella label
        $this->controls_items_label_style($repeater);

        // -------- COLORS-HOVER
        //@p le carateristiche grafche del colore testi e background nello statoo di hover
        $this->controls_items_colorshover_style($repeater);

        // ------------ SPACES
        //@p le carateristiche grafche le spaziature Padding e margin
        $this->controls_items_spaces_style($repeater);

        // ------------ BORDERS & SHADOW
        //@p le carateristiche grafche: bordo, raggio-del-bordo, ombra del box
        $this->controls_items_bordersandshadow_style($repeater);

        $repeater->end_controls_tab();

        $repeater->start_controls_tab('tab_advanced', [
            'label' => __('Advanced', 'e-addons'),
            'conditions' => [
                'relation' => 'or',
                'terms' => [
                    [
                        'name' => 'item_type',
                        'operator' => '!in',
                        'value' => ['item_author', /*'item_readmore',*/ 'item_custommeta', 'item_template',
                            'item_caption',
                            'item_alternativetext',
                            'item_imagemeta',
                            'item_mimetype'],
                    ],
                    [
                        'relation' => 'and',
                        'terms' => [
                            [
                                'name' => 'item_type',
                                'value' => 'item_custommeta',
                            ],
                            [
                                'name' => 'metafield_type',
                                'operator' => 'in',
                                'value' => ['text', 'number', 'image', 'file', 'array']
                            ]
                        ]
                    ]
                ]
            ]
        ]);

        // ------------ ADVANCED - TAB
        // @p considero i campi avanzati: se è linkato (use_link) e se l'item è Bloock o Inline
        $this->controls_items_advanced($repeater);

        $repeater->end_controls_tab();

        $repeater->end_controls_tabs();
        /*
          //item_image
          item_date
          item_title
          item_termstaxonomy
          item_content
          item_author
          item_readmore
          item_custommeta
          item_imagemeta
         */
        $this->add_control(
                'list_items',
                [
                    'label' => __('ITEMS', 'e-addons'),
                    'show_label' => false,
                    'separator' => 'before',
                    'type' => Controls_Manager::REPEATER,
                    'fields' => $repeater->get_controls(),
                    'prevent_empty' => false,
                    'default' => [
                    /* [
                      'item_type' => 'item_image',
                      ] */
                    ],
                    //item_type.replace("item_", "")
                    'title_field' => '<# var etichetta = item_type; etichetta = etichetta.replace("item_", ""); #><b class="e-add-item-name"><i class="fa {{{ item_type+"-ic" }}}" aria-hidden="true"></i> {{{item_text_label}}} | {{{ etichetta }}}</b>',
                ]
        );

        $this->controls_items_grid_debug($this);
        
        $this->end_controls_section();


        //@p il TAB Query
        // ------------------------------------------------------------------ [SECTION - QUERY MEDIA]
        $this->start_controls_section(
                'section_query_posts', [
            'label' => '<i class="eaddicon eicon-settings" aria-hidden="true"></i> ' . __('Query', 'e-addons'),
            'tab' => 'e_query',
                ]
        );
        /*
          'specific_posts'
          'get_attachments'
          'custommeta_source'
          'satic_list'
         */
        $this->add_control(
                'query_debug', [
            'label' => '<span style="color: #fff; background-color: #93003c; padding: 5px 10px; border-radius: 20px;">' . __('Show query for DEBUG', 'e-addons') . '</span>',
            'type' => Controls_Manager::SWITCHER,
                ]
        );
        $this->add_control(
                'query_type', [
            'label' => __('Query Type', 'e-addons'),
            'type' => 'ui_selector',
            'toggle' => false,
            'type_selector' => 'icon',
            'columns_grid' => 3,
            'separator' => 'before',
            'label_block' => true,
            'options' => [
                /*
                  'automatic_mode' => [
                  'title' => __('Automatic Mode','e-addons'),
                  'return_val' => 'val',
                  'icon' => 'fa fa-cogs',
                  ],
                 */
                'specific_posts' => [
                    'title' => __('Specific Attachment', 'e-addons'),
                    'return_val' => 'val',
                    'icon' => 'far fa-copy',
                ],
                'get_attachments' => [
                    'title' => __('Media Library', 'e-addons'),
                    'return_val' => 'val',
                    'icon' => 'fa fa-images',
                ],
                'custommeta_source' => [
                    'title' => __('Custom Field Gallery', 'e-addons'),
                    'return_val' => 'val',
                    'icon' => 'fas fa-check-double',
                ],
            ],
            'default' => 'get_attachments',
                ]
        );

        // --------------------------------- [ Specific Posts-Pages ] 
        $this->add_control(
                'specific_attachments',
                [
                    'label' => __('Add Medias', 'elementor'),
                    'type' => Controls_Manager::GALLERY,
                    'default' => [],
                    'show_label' => false,
                    'dynamic' => [
                        'active' => true,
                    ],
                    'condition' => [
                        'query_type' => 'specific_posts',
                    ],
                ]
        );

        // --------------------------------- [ CustomMeta source ]
        $this->custommeta_source_items($this, 'post');

        // --------------------------------- [ Automatic mode ]
        $this->add_control(
                'avviso_automatic_mode',
                [
                    'type' => Controls_Manager::RAW_HTML,
                    'show_label' => false,
                    'raw' => '<i class="fas fa-exclamation-circle"></i> ' . __('With this option will be used posts based on the current global Query. <div class="eadd-automatic-info">Ideal for native Archives pages: <ul><li>Templates in posts, pages or single cpt;</li> <li>Terms archives;</li> <li>Authors archives; </li></ul></div>', 'e-addons'),
                    'content_classes' => 'e-add-info-panel',
                    'condition' => [
                        'query_type' => 'automatic_mode',
                    ],
                ]
        );
        $this->add_control(
                'options_heading', [
            'label' => __('Options', 'e-addons'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
            'condition' => [
                'query_type' => ['automatic_mode'],
            ],
                ]
        );

        // --------------------------------- [ Custom Post Type ]

        /*
          'post_type'
          'post_status'
          'ignore_sticky_posts'
          'posts_per_page'
          'posts_offset'
          'orderby'
          'metakey' ...
          'order'
          'exclude_myself'
          'exclude_posts'
         */
        $this->add_control(
                'hr_query',
                [
                    'type' => Controls_Manager::DIVIDER,
                    'style' => 'thick',
                ]
        );
        $this->add_control(
                'posts_per_page', [
            'label' => __('Number of Medias', 'e-addons'),
            'type' => Controls_Manager::NUMBER,
            'description' => __('Number of Medias per Page. Set -1 to display all Medias or empty for default settings value.'),
            'condition' => [
                'query_type' => ['get_attachments', 'automatic_mode'],
            ],
                ]
        );
        $this->add_control(
                'posts_offset', [
            'label' => __('Offset', 'e-addons'),
            'type' => Controls_Manager::NUMBER,
            'default' => '0',
            'condition' => [
                'query_type' => ['get_attachments', 'automatic_mode'],
                'posts_per_page!' => '-1'
            ],
                ]
        );
        $this->add_control(
                'orderby', [
            'label' => __('Order By', 'e-addons'),
            'type' => Controls_Manager::SELECT,
            'options' => Query_Utils::get_post_orderby_options(),
            'default' => 'date',
            'condition' => [
                'query_type' => ['get_attachments', 'automatic_mode'],
            ],
                ]
        );
        $this->add_control(
                'metakey', [
            'label' => __('Custom Field', 'e-addons'),
            'type' => 'e-query',
            'placeholder' => __('Search Custom Field', 'e-addons'),
            'label_block' => true,
            'query_type' => 'metas',
            'object_type' => 'attachment',
            //'description' => __('Selected Post Meta value must be stored if format "Ymd", like ACF Date', 'e-addons'),
            'separator' => 'after',
            'condition' => [
                'query_type' => ['get_attachments', 'automatic_mode'],
                'orderby' => ['meta_value', 'meta_value_date', 'meta_value_num'],
            ]
                ]
        );
        $this->add_control(
                'order', [
            'label' => __('Order', 'e-addons'),
            'type' => Controls_Manager::SELECT,
            'options' => [
                'ASC' => 'ASC',
                'DESC' => 'DESC'
            ],
            'default' => 'DESC',
            'condition' => [
                'query_type' => ['get_attachments', 'automatic_mode'],
                'orderby!' => ['random'],
            ],
                ]
        );
        // --------------------------------- [ Posts Exclusion ]
        $this->add_control(
                'heading_query_exclude',
                [
                    'type' => Controls_Manager::RAW_HTML,
                    'show_label' => false,
                    'raw' => '<i class="fas fa-ban" aria-hidden="true"></i> &nbsp;<b>' . __('Exclude', 'e-addons') . '</b>',
                    'content_classes' => 'e-add-icon-heading',
                    'condition' => [
                        'query_type' => ['get_attachments', 'automatic_mode']
                    ]
                ]
        );

        $this->add_control(
                'exclude_posts', [
            'label' => __('Exclude Specific Media', 'e-addons'),
            'type' => 'file',
            'placeholder' => __('Excuded Media', 'e-addons'),
            'label_block' => true,
            'multiple' => true,
            'condition' => [
                'query_type' => ['get_attachments', 'automatic_mode'],
            ],
                ]
        );
        $this->end_controls_section();

        // --------------------------------- [SECTION QUERY-FILTER]
        /*
          'query_filter'
          'date'
          'term'
          'author'
          'metakey'
          'mimetype'

          -------- DATE -------
          ''
          'past'
          'today'
          'yesterday'
          'days'
          'weeks'
          'months'
          'years'
          'period'

          -------- TERMS TAX -------
          'term_from'
          'post_term'
          'include_term'
          'include_term_combination'
          'exclude_term'
          'exclude_term_combination'
          'custom_meta'
          'term_field_meta'
          'current_term'

          -------- AUTHORS -------
          'author_from'
          'post_author'
          'include_author'
          'exclude_author'
          'custom_meta'
          'author_field_meta'
          'current_autor'

          -------- META KEY -------
          'metakey_list' [REPEATER]
          'metakey_field_meta'
          'metakey_field_meta_compare'
          'metakey_field_meta_type'
          'metakey_field_meta_value'
          //'metakey_field_meta_value_num'

          'metakey_combination'

          -------- COMMENTS -------


         */
        $this->start_controls_section(
                'section_query_filter', [
            'label' => '<i class="eaddicon eicon-parallax" aria-hidden="true"></i> ' . __('Query Filter', 'e-addons'),
            'tab' => 'e_query',
            'condition' => [
                'query_type' => ['get_attachments', 'automatic_mode']
            ]
                ]
        );
        $this->add_control(
                'query_filter', [
            'label' => __('Filter by', 'e-addons'),
            'type' => Controls_Manager::SELECT2,
            'options' => [
                'date' => 'Date',
                'term' => 'Term',
                'author' => 'Author',
                'metakey' => 'Meta key',
                'search' => 'Search',
                'mimetype' => 'Mime Type'
            ],
            'multiple' => true,
            'label_block' => true,
            'default' => [],
                ]
        );
        // ******************** MimeType
        // get_available_post_mime_types()
        // get_allowed_mime_types()
        // 
        $this->add_control(
                'filter_mimetype', [
            'label' => __('Mime Types', 'e-addons'),
            'type' => Controls_Manager::SELECT2,
            'multiple' => true,
            'label_block' => true,
            'options' => Query_Utils::get_available_mime_types_options(),
            'default' => 'all',
            'place_holder' => 'Select specific Mime Types',
            'toggle' => false,
            'condition' => [
                'query_filter' => 'mimetype'
            ]
                ]
        );
        // ******************** Search
        $this->add_control(
                'heading_query_filter_search',
                [
                    'type' => Controls_Manager::RAW_HTML,
                    'show_label' => false,
                    'raw' => '<i class="fa fa-search" aria-hidden="true"></i> ' . __('Search Filters', 'e-addons'),
                    'content_classes' => 'e-add-icon-heading',
                    'condition' => [
                        'query_filter' => 'search'
                    ],
                ]
        );
        $this->add_control(
                'search_field_value', [
            'label' => __('Search Value', 'elementor'),
            'type' => Controls_Manager::TEXT,
            'label_block' => true,
            'default' => '',
            'condition' => [
                'query_filter' => 'search'
            ]
                ]
        );
        $this->add_control(
                'info_filter_search',
                [
                    'type' => Controls_Manager::RAW_HTML,
                    'show_label' => false,
                    'raw' => '<i class="fa fa-info" aria-hidden="true"></i> ' . __('Prepending a term with a hyphen will exclude posts matching that term. Eg, "pillow -sofa" will return posts containing "pillow" but not "sofa".', 'e-addons'),
                    'content_classes' => 'e-add-info-panel',
                    'condition' => [
                        'query_filter' => 'search'
                    ],
                ]
        );
        // +********************* Date
        $this->add_control(
                'heading_query_filter_date',
                [
                    'type' => Controls_Manager::RAW_HTML,
                    'show_label' => false,
                    'raw' => '<i class="fa fa-calendar" aria-hidden="true"></i> ' . __('Date Filters', 'e-addons'),
                    'label_block' => false,
                    'content_classes' => 'e-add-icon-heading',
                    'condition' => [
                        'query_filter' => 'date',
                    ],
                ]
        );
        $this->add_control(
                'querydate_mode', [
            'label' => __('Date Filter', 'e-addons'),
            'type' => Controls_Manager::SELECT,
            'default' => '',
            'label_block' => true,
            'options' => [
                '' => __('Default (Past)', 'e-addons'),
                //'future' => __('Future', 'e-addons'),
                'today' => __('Today', 'e-addons'),
                'yesterday' => __('Yesterday', 'e-addons'),
                'days' => __('Past Days', 'e-addons'),
                'weeks' => __('Past Weeks', 'e-addons'),
                'months' => __('Past Months', 'e-addons'),
                'years' => __('Past Years', 'e-addons'),
                'period' => __('Period', 'e-addons'),
            ],
            'condition' => [
                'query_filter' => 'date',
            ],
                ]
        );

        // number of days / months / years elapsed
        $this->add_control(
                'querydate_range', [
            'label' => __('Number of (days/months/years) elapsed', 'e-addons'),
            'label_block' => false,
            'type' => Controls_Manager::NUMBER,
            'default' => 1,
            'condition' => [
                'query_filter' => 'date',
                'querydate_mode' => ['days', 'weeks', 'months', 'years']
            ]
                ]
        );
        $this->add_control(
                'querydate_date_from', [
            'label' => __('Date FROM', 'e-addons'),
            'type' => Controls_Manager::DATE_TIME,
            'label_block' => false,
            'condition' => [
                'query_filter' => 'date',
                'querydate_mode' => 'period',
            ],
                ]
        );
        $this->add_control(
                'querydate_date_to', [
            'label' => __('Date TO', 'e-addons'),
            'type' => Controls_Manager::DATE_TIME,
            'label_block' => false,
            'condition' => [
                'query_filter' => 'date',
                'querydate_mode' => 'period',
            ],
                ]
        );

        // +********************* Term Taxonomy
        $this->add_control(
                'heading_query_filter_term',
                [
                    'type' => Controls_Manager::RAW_HTML,
                    'show_label' => false,
                    'raw' => '<i class="fa fa-folder-o" aria-hidden="true"></i> ' . __('Term Filters', 'e-addons'),
                    'content_classes' => 'e-add-icon-heading',
                    'condition' => [
                        'query_filter' => 'term'
                    ],
                ]
        );
        // From Post or Meta
        $this->add_control(
                'term_from', [
            'label' => __('Type', 'e-addons'),
            'type' => Controls_Manager::CHOOSE,
            'label_block' => false,
            'options' => [
                'post_term' => [
                    'title' => __('Select Term', 'e-addons'),
                    'icon' => 'fa fa-tag',
                ],
                'custom_meta' => [
                    'title' => __('Media Meta Term', 'e-addons'),
                    'icon' => 'fa fa-square',
                ],
                'current_term' => [
                    'title' => __('Current Term', 'e-addons'),
                    'icon' => 'fa fa-cog',
                ],
            ],
            'default' => 'post_term',
            'toggle' => false,
            'condition' => [
                'query_filter' => 'term'
            ]
                ]
        );
        // [Post Meta]
        $this->add_control(
                'term_field_meta',
                [
                    'label' => __('Media Term <b>custom meta field</b>', 'e-addons'),
                    'type' => 'e-query',
                    'placeholder' => __('Search Meta Field', 'e-addons'),
                    'label_block' => true,
                    'query_type' => 'metas',
                    'object_type' => 'attachment',
                    'description' => __('Selected Media Custom field. The meta must return an element of type array or comma separated string that contains the term type IDs. (ex: array [5,27,88] or 5,27,88).', 'e-addons'),
                    'condition' => [
                        'term_from' => 'custom_meta',
                        'query_filter' => 'term'
                    ]
                ]
        );

        // [Post Term]
        $this->add_control(
                'include_term',
                [
                    'label' => __('<b>Include</b> Term', 'e-addons'),
                    'type' => 'e-query',
                    'placeholder' => __('Select Media Terms', 'e-addons'),
                    'label_block' => true,
                    'query_type' => 'terms',
                    'render_type' => 'template',
                    'multiple' => true,
                    'condition' => [
                        'query_filter' => 'term',
                        'term_from' => 'post_term'
                    ],
                ]
        );
        $this->add_control(
                'include_term_combination',
                [
                    'label' => __('<b>Include</b> Combination', 'e-addons'),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'OR' => [
                            'title' => __('OR', 'e-addons'),
                            'icon' => 'fa fa-circle-o',
                        ],
                        'AND' => [
                            'title' => __('AND', 'e-addons'),
                            'icon' => 'fa fa-circle',
                        ]
                    ],
                    'toggle' => false,
                    'default' => 'OR',
                    'conditions' => [
                        'terms' => [
                            [
                                'name' => 'query_filter',
                                'operator' => 'contains',
                                'value' => 'term',
                            ],
                            [
                                'name' => 'query_filter',
                                'operator' => '!=',
                                'value' => [],
                            ],
                            [
                                'name' => 'include_term',
                                'operator' => '!=',
                                'value' => '',
                            ],
                            [
                                'name' => 'include_term',
                                'operator' => '!=',
                                'value' => [],
                            ]/* ,
                          [
                          'name' => 'term_from',
                          'value' => 'post_term',
                          ], */
                        ]
                    ]
                ]
        );
        $this->add_control(
                'exclude_term',
                [
                    'label' => __('<b>Exclude</b> Term', 'e-addons'),
                    'type' => 'e-query',
                    'placeholder' => __('Select Media Terms', 'e-addons'),
                    'label_block' => true,
                    'query_type' => 'terms',
                    'render_type' => 'template',
                    'multiple' => true,
                    'condition' => [
                        'query_filter' => 'term',
                    //'term_from' => 'post_term'
                    ],
                ]
        );
        $this->add_control(
                'exclude_term_combination',
                [
                    'label' => __('<b>Exclude</b> Combination', 'e-addons'),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'OR' => [
                            'title' => __('OR', 'e-addons'),
                            'icon' => 'fa fa-circle-o',
                        ],
                        'AND' => [
                            'title' => __('AND', 'e-addons'),
                            'icon' => 'fa fa-circle',
                        ]
                    ],
                    'toggle' => false,
                    'default' => 'OR',
                    'conditions' => [
                        'terms' => [
                            [
                                'name' => 'query_filter',
                                'operator' => 'contains',
                                'value' => 'term',
                            ],
                            [
                                'name' => 'query_filter',
                                'operator' => '!=',
                                'value' => [],
                            ],
                            [
                                'name' => 'exclude_term',
                                'operator' => '!=',
                                'value' => '',
                            ],
                            [
                                'name' => 'exclude_term',
                                'operator' => '!=',
                                'value' => [],
                            ]/* ,
                          [
                          'name' => 'term_from',
                          'value' => 'post_term',
                          ], */
                        ]
                    ]
                ]
        );

        // +********************* Author
        $this->add_control(
                'heading_query_filter_author',
                [
                    'type' => Controls_Manager::RAW_HTML,
                    'show_label' => false,
                    'raw' => '<i class="fa fa-user-circle-o" aria-hidden="true"></i> ' . __('Author Filters', 'e-addons'),
                    'content_classes' => 'e-add-icon-heading',
                    'condition' => [
                        'query_filter' => 'author'
                    ],
                ]
        );
        // From: Post, Meta or Current
        $this->add_control(
                'author_from', [
            'label' => __('Type', 'e-addons'),
            'type' => Controls_Manager::CHOOSE,
            'label_block' => false,
            'options' => [
                'post_author' => [
                    'title' => __('Select Author', 'e-addons'),
                    'icon' => 'fa fa-users',
                ],
                'custom_meta' => [
                    'title' => __('Post Meta Author', 'e-addons'),
                    'icon' => 'fa fa-square',
                ],
                'current_author' => [
                    'title' => __('Current Author', 'e-addons'),
                    'icon' => 'fa fa-user-cog',
                ],
            ],
            'default' => 'post_author',
            'toggle' => false,
            'condition' => [
                'query_filter' => 'author'
            ],
                ]
        );
        // [Post Meta]
        $this->add_control(
                'author_field_meta',
                [
                    'label' => __('Media Author <b>custom meta field</b>', 'e-addons'),
                    'type' => 'e-query',
                    'placeholder' => __('Search Custom Author meta', 'e-addons'),
                    'label_block' => true,
                    'query_type' => 'metas',
                    'object_type' => 'attachment',
                    'default' => 'nickname',
                    'description' => __('Selected Media Meta value. Selected Media Custom field. The meta must return an element of type array or comma separated string that contains the term type IDs. (ex: array [5,27,88] or 5,27,88).', 'e-addons'),
                    'condition' => [
                        'author_from' => 'custom_meta',
                        'query_filter' => 'author'
                    ]
                ]
        );

        // [Select Authors]
        $this->add_control(
                'include_author',
                [
                    'label' => __('<b>Include</b> Author', 'e-addons'),
                    'type' => 'e-query',
                    'placeholder' => __('Select Authors', 'e-addons'),
                    'label_block' => true,
                    'multiple' => true,
                    'query_type' => 'users',
                    //'object_type'   => 'editor',
                    'description' => __('Filter Medias by selected Authors', 'e-addons'),
                    'condition' => [
                        'query_filter' => 'author',
                        'author_from' => 'post_author'
                    ]
                ]
        );

        $this->add_control(
                'exclude_author',
                [
                    'label' => __('<b>Exclude</b> Author', 'e-addons'),
                    'type' => 'e-query',
                    'placeholder' => __('Select Authors', 'e-addons'),
                    'label_block' => true,
                    'multiple' => true,
                    'query_type' => 'users',
                    //'object_type'   => 'editor',
                    'description' => __('Filter Medias by selected Authors', 'e-addons'),
                    'separator' => 'after',
                    'condition' => [
                        'query_filter' => 'author',
                        'author_from' => 'post_author'
                    ]
                ]
        );

        // ****************** Meta key
        $this->add_control(
                'heading_query_filter_metakey',
                [
                    'type' => Controls_Manager::RAW_HTML,
                    'show_label' => false,
                    'raw' => '<i class="fa fa-key" aria-hidden="true"></i> ' . __('Custom Meta Field Filters', 'e-addons'),
                    'content_classes' => 'e-add-icon-heading',
                    'condition' => [
                        'query_filter' => 'metakey'
                    ],
                ]
        );

        // [Post Meta]
        $repeater_metakeys = new Repeater();

        $repeater_metakeys->add_control(
                'metakey_field_meta',
                [
                    'label' => __('Media Custom Field <b>custom meta key</b>', 'e-addons'),
                    'type' => 'e-query',
                    'placeholder' => __('Meta key or Name', 'e-addons'),
                    'label_block' => true,
                    'query_type' => 'metas',
                    'object_type' => 'attachment',
                    'description' => __('Selected Post Meta value. The meta must return an element of type array or comma separated string that contains IDs of type metakey . (es: array[5,27,88] o 5,27,88)', 'e-addons'),
                ]
        );
        $repeater_metakeys->add_control(
                'metakey_field_meta_type', [
            'label' => __('Value Type', 'elementor'),
            'description' => __('Custom field type. Default value is (CHAR)', 'e-addons'),
            'type' => Controls_Manager::SELECT,
            'options' => Query_Utils::get_meta_comparetype(),
            'default' => 'CHAR',
            'label_block' => true
                ]
        );
        $repeater_metakeys->add_control(
                'metakey_field_meta_compare', [
            'label' => __('Compare Operator', 'elementor'),
            'description' => __('Comparison operator. Default value is (=)', 'e-addons'),
            'type' => Controls_Manager::SELECT,
            'options' => Query_Utils::get_meta_compare(),
            'default' => '=',
            'label_block' => true
                ]
        );

        $repeater_metakeys->add_control(
                'metakey_field_meta_value', [
            'label' => __('Media Field Value', 'elementor'),
            'type' => Controls_Manager::TEXT,
            'description' => __('The specific value of the Media Field', 'elementor'),
            'label_block' => true,
            'condition' => [
                'metakey_field_meta_compare!' => ['EXISTS', 'NOT EXISTS']
            ]
                ]
        );
        // il metakey REPEATER
        $this->add_control(
                'metakey_list',
                [
                    'label' => __('Custom Meta Fields', 'e-addons'),
                    'type' => Controls_Manager::REPEATER,
                    'fields' => $repeater_metakeys->get_controls(),
                    'title_field' => '{{{ metakey_field_meta }}}',
                    'prevent_empty' => false,
                    'condition' => [
                        'query_filter' => 'metakey',
                    ]
                ]
        );
        $this->add_control(
                'metakey_combination',
                [
                    'label' => __('<b>Custom Field</b> Combination', 'e-addons'),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'OR' => [
                            'title' => __('OR', 'e-addons'),
                            'icon' => 'fa fa-circle-o',
                        ],
                        'AND' => [
                            'title' => __('AND', 'e-addons'),
                            'icon' => 'fa fa-circle',
                        ]
                    ],
                    'toggle' => false,
                    'default' => 'OR',
                    'conditions' => [
                        'terms' => [
                            [
                                'name' => 'query_filter',
                                'operator' => 'contains',
                                'value' => 'metakey',
                            ],
                            [
                                'name' => 'query_filter',
                                'operator' => '!=',
                                'value' => [],
                            ],
                            [
                                'name' => 'metakey_list',
                                'operator' => '!=',
                                'value' => '',
                            ],
                            [
                                'name' => 'metakey_list',
                                'operator' => '!=',
                                'value' => [],
                            ]
                        ]
                    ]
                ]
        );
        $this->end_controls_section();

        $this->add_no_result_section();
    }

    // --------------------------------- [ Media Options ]
    //@p questo metodo ignetta nella prima section le opzioni per media: 
    /*
      - Lightbox
     */

    public function items_query_controls() {
        $this->add_control(
                'heading_imagelink',
                [
                    'type' => Controls_Manager::RAW_HTML,
                    'show_label' => false,
                    'raw' => '<i class="fas fa-external-link-square-alt"></i> &nbsp;' . __('LINK & LIGHTBOX:', 'e-addons'),
                    'content_classes' => 'e-add-icon-heading',
                ]
        );
        $this->add_control(
                'gallery_link',
                [
                    'label' => __('Link', 'elementor'),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'file',
                    'options' => [
                        'file' => __('Media File', 'elementor'),
                        'attachment' => __('Attachment Page', 'elementor'),
                        'none' => __('None', 'elementor'),
                    ],
                ]
        );
        $this->add_control(
                'open_lightbox',
                [
                    'label' => __('Lightbox', 'elementor'),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'default',
                    'options' => [
                        'default' => __('Default', 'elementor'),
                        'yes' => __('Yes', 'elementor'),
                        'no' => __('No', 'elementor'),
                    ],
                    'condition' => [
                        'gallery_link' => 'file',
                    ],
                ]
        );
        $this->add_control(
                'heading_imageoptions',
                [
                    'type' => Controls_Manager::RAW_HTML,
                    'show_label' => false,
                    'raw' => '<i class="fas fa-image"></i> &nbsp;' . __('IMAGE OPTIONS:', 'e-addons'),
                    'content_classes' => 'e-add-icon-heading',
                ]
        );
        $this->add_group_control(
                Group_Control_Image_Size::get_type(), [
            'name' => 'thumbnail_size',
            'label' => __('Image Format', 'e-addons'),
            'default' => 'large',
                ]
        );
        $this->add_responsive_control(
                'ratio_image', [
            'label' => __('Image Ratio', 'e-addons'),
            'type' => Controls_Manager::SLIDER,
            'separator' => 'before',
            'range' => [
                'px' => [
                    'min' => 0.1,
                    'max' => 2,
                    'step' => 0.1
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .elementor-repeater-item-e-add-media-image .e-add-img' => 'padding-bottom: calc( {{SIZE}} * 100% );', '{{WRAPPER}}:after' => 'content: "{{SIZE}}";',
            ],
            'conditions' => [
                'terms' => [
                    [
                        'name' => '_skin',
                        'operator' => '!in',
                        'value' => ['justifiedgrid'],
                    ],
                    [
                        'name' => 'use_bgimage',
                        'value' => '',
                    ]
                ]
            ]
                ]
        );
        $this->add_responsive_control(
                'width_image', [
            'label' => __('Image Width', 'e-addons'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['%', 'px', 'vw'],
            'range' => [
                '%' => [
                    'min' => 1,
                    'max' => 100,
                    'step' => 1
                ],
                'vw' => [
                    'min' => 1,
                    'max' => 100,
                    'step' => 1
                ],
                'px' => [
                    'min' => 1,
                    'max' => 800,
                    'step' => 1
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .elementor-repeater-item-e-add-media-image .e-add-post-image' => 'width: {{SIZE}}{{UNIT}};',
            ],
            'conditions' => [
                'terms' => [
                    [
                        'name' => '_skin',
                        'operator' => '!in',
                        'value' => ['justifiedgrid'],
                    ],
                    [
                        'name' => 'use_bgimage',
                        'value' => '',
                    ]
                ]
            ]
                ]
        );
        $this->add_control(
                'use_bgimage', [
            'label' => __('Background mode', 'e-addons'),
            'type' => Controls_Manager::SWITCHER,
            'separator' => 'before',
            'render_type' => 'template',
            'selectors' => [
                '{{WRAPPER}} .e-add-image-area, {{WRAPPER}}.e-add-posts-layout-default .e-add-post-bgimage' => 'position: relative;',
            ],
            'conditions' => [
                'terms' => [
                    [
                        'name' => '_skin',
                        'operator' => '!in',
                        'value' => ['justifiedgrid'],
                    ]
                ]
            ]
                ]
        );
        $this->add_responsive_control(
                'height_bgimage', [
            'label' => __('Height', 'e-addons'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px', 'vh'],
            'range' => [
                'px' => [
                    'min' => 1,
                    'max' => 800,
                    'step' => 1
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .elementor-repeater-item-e-add-media-image .e-add-post-image.e-add-post-bgimage' => 'height: {{SIZE}}{{UNIT}};'
            ],
            'conditions' => [
                'terms' => [
                    [
                        'name' => 'use_bgimage',
                        'operator' => '!=',
                        'value' => '',
                    ],
                    [
                        'name' => '_skin',
                        'operator' => '!in',
                        'value' => ['justifiedgrid'],
                    ]
                ]
            ]
                ]
        );
        $this->add_control(
                'use_overlay', [
            'label' => __('Overlay', 'e-addons'),
            'type' => Controls_Manager::SWITCHER,
            'separator' => 'before',
            'prefix_class' => 'overlayimage-',
            'render_type' => 'template',
                ]
        );
        $this->add_group_control(
                Group_Control_Background::get_type(), [
            'name' => 'overlay_color',
            'label' => __('Background', 'e-addons'),
            'types' => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .elementor-repeater-item-e-add-media-image .e-add-post-image.e-add-post-overlayimage:after',
            'conditions' => [
                'terms' => [
                    [
                        'name' => 'use_overlay',
                        'operator' => '!==',
                        'value' => '',
                    ]
                ]
            ]
                ]
        );
        $this->add_responsive_control(
                'overlay_opacity', [
            'label' => __('Opacity (%)', 'e-addons'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => 0.7,
            ],
            'range' => [
                'px' => [
                    'max' => 1,
                    'min' => 0.10,
                    'step' => 0.01,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .elementor-repeater-item-e-add-media-image .e-add-post-image.e-add-post-overlayimage:after' => 'opacity: {{SIZE}};',
            ],
            'conditions' => [
                'terms' => [
                    [
                        'name' => 'use_overlay',
                        'operator' => '!==',
                        'value' => '',
                    ]
                ]
            ]
                ]
        );
    }

    // 
    public function query_the_elements() {
        $settings = $this->get_settings_for_display();
        if (empty($settings))
            return;

        $args = array();

        /*
          '1 - automatic_mode'
          '2 - all attachments'
          '3 - custommeta_source'
          '4 - specific_posts'

         */
        $query_type = $settings['query_type'];

        switch ($query_type) {
            case 'automatic_mode':
                global $wp_query;
            //echo '<pre>'; var_dump($wp_query); echo '</pre>';

            /** @var Module_Query $elementor_query */
            //$elementor_query = Module_Query::instance();
            //$this->query = $elementor_query->get_query( $this, 'posts', $query_args, [] );

            case 'custommeta_source':
                /*
                  $custommeta_source_key = $settings['custommeta_source_key'];
                  //$custommeta_source_type = $settings['custommeta_source_type'];
                  if (empty($custommeta_source_key))
                  return;
                  $type_of_location = Query_Utils::is_type_of();
                  $id_of_location = Query_Utils::is_id_of();
                  $custommeta_source_value = get_metadata($type_of_location, $id_of_location, $custommeta_source_key, true);
                 */
                $custommeta_source_value = $this->get_custom_meta_source_value($settings);
                //var_dump($custommeta_source_value);
                if (!empty($custommeta_source_value)) {
                    // default args
                    $args['posts_per_page'] = -1;
                    $args['orderby'] = 'post__in';
                    //
                    $args['post__in'] = Utils::explode($custommeta_source_value);
                }
                break;
            case 'specific_posts':
                if (!empty($settings['specific_attachments'])) {
                    $items_specific_posts = array();
                    foreach ($settings['specific_attachments'] as $item_sp) {
                        if (!empty($item_sp['id'])) {
                            array_push($items_specific_posts, $item_sp['id']);
                        }
                    }
                    if (count($items_specific_posts)) {
                        $args['posts_per_page'] = -1;
                        $args['orderby'] = 'post__in';
                        $args['post__in'] = $items_specific_posts;
                    }
                }
                break;
        }

        /*
          'post_type'
          --'posts_per_page'
          --'posts_offset'
          --'orderby'
          --'metakey' ...
          --'order'
          --'exclude_posts'
         */

        //@p è scontato che il type è "attachment"
        $args['post_type'] = 'attachment';
        $args['post_status'] = ['inherit', 'publish'];

        // limit posts per page
        if (!empty($settings['posts_per_page']))
            $args['posts_per_page'] = $settings['posts_per_page'];

        // offset
        if (!empty($settings['posts_offset']))
            $args['offset'] = $settings['posts_offset'];

        // paginazione
        if (!empty($settings['pagination_enable']) || !empty($settings['infiniteScroll_enable']))
            $args['paged'] = $this->get_current_page();


        // order by
        if (!empty($settings['orderby']))
            $args['orderby'] = $settings['orderby'];
        //meta key order
        if (!empty($settings['metakey']))
            $args['meta_key'] = $settings['metakey'];
        // order asc-desc
        if (!empty($settings['order']))
            $args['order'] = $settings['order'];

        // exclusion posts
        if (!empty($settings['exclude_posts'])) {
            $args['post__not_in'] = Utils::explode($settings['exclude_posts']);
        }

        /*
          'query_filter'
          'date'
          'term'
          'author'
          'metakey'
          'mimetype'
         */
        $query_filters = $settings['query_filter'];
        if (!empty($query_filters))
            foreach ($query_filters as $filter) {

                switch ($filter) {
                    case 'date':
                        $args = array_merge($args, $this->get_date_filter($settings));
                        break;
                    case 'term':
                        $args = array_merge($args, $this->get_terms_filter($settings));
                        break;
                    case 'author':
                        $args = array_merge($args, $this->get_author_filter($settings));
                        break;
                    case 'metakey':
                        $args = array_merge($args, $this->get_metakey_filter($settings));
                        break;
                    case 'search':
                        $args = array_merge($args, $this->get_search_filter($settings));
                        break;
                    case 'mimetype':
                        $args = array_merge($args, $this->get_mimetype_filter($settings));
                        break;
                }
            }

        if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
            if (!empty($this->get_settings_for_display('query_debug'))) {
                echo '<pre>';
                var_dump($args);
                echo '</pre>';
            }
        }

        $query_m = new \WP_Query($args);
        $this->query = $query_m;
    }

    protected function get_mimetype_filter($settings) {
        /*
          'mimetype_field_value'
         */
        $mimetype_args = array();
        $mimetypes_field_value = $settings['filter_mimetype'];
        $mimetype_args['post_mime_type'] = $mimetypes_field_value;
        return $mimetype_args;
    }

    protected function get_search_filter($settings) {
        /*
          'search_field_value'
         */
        $search_args = array();

        $search_field_value = $settings['search_field_value'];
        if (!empty($search_field_value))
            $search_args['s'] = $search_field_value;

        /*
          $args = array(
          'search'         => 'Rami',
          'search_columns' => array( 'user_login', 'user_email' )
          );
         */
        return $search_args;
    }

    protected function get_author_filter($settings) {
        /*
          -------- AUTHORS -------
          'author_from'
          'post_author'
          'include_author'
          'exclude_author'
          'custom_meta'
          'author_field_meta'
          'current_author'

         */
        $author_args = array();
        switch ($settings['author_from']) {
            case 'post_author':
                if (!empty($settings['include_author'])) {
                    $author_args['author__in'] = $settings['include_author'];
                }
                break;
            case 'custom_meta':
                if (!empty($settings['author_field_meta'])) {
                    $author_args['author__in'] = $settings['author_field_meta'];
                }
                break;
            case 'current_author':
                $author_id = get_the_author_meta('ID');
                $author_args['author'] = $author_id;
                break;
        }

        // l'esclusione vale in ogni caso, permette di modellare la query sempre
        if (!empty($settings['exclude_author'])) {
            $author_args['author__not_in'] = $settings['exclude_author'];
        }


        return $author_args;
    }

    protected function get_metakey_filter($settings) {
        /*
          -------- META KEY -------
          'metakey_list' [REPEATER]
          'metakey_field_meta'
          'metakey_field_meta_compare'
          'metakey_field_meta_type'
          'metakey_field_meta_value'
          //'metakey_field_meta_value_num'

          'metakey_combination'
         */
        $metakey_args = array();
        $keysquery = array();

        $metakey_list = $settings['metakey_list'];
        foreach ($metakey_list as $item) {
            $_id = $item['_id'];

            $metakey_field_meta = $item['metakey_field_meta'];
            $metakey_field_meta_type = $item['metakey_field_meta_type'];
            $metakey_field_meta_compare = $item['metakey_field_meta_compare'];

            $metakey_field_meta_value = $item['metakey_field_meta_value'];
            //$metakey_field_meta_value_num = $item['metakey_field_meta_value_num'];

            array_push($keysquery, array(
                'key' => $metakey_field_meta,
                'value' => $metakey_field_meta_value,
                'type' => $metakey_field_meta_type,
                'compare' => $metakey_field_meta_compare
            ));
        }

        $keysquery['relation'] = $settings['metakey_combination'];

        $metakey_args['meta_query'] = $keysquery;
        //var_dump($taxquery);
        //
        return $metakey_args;
    }

    protected function get_terms_filter($settings) {
        /*
          -------- TERMS TAX -------
          'term_from'
          'post_term'
          'include_term'
          'include_term_combination'
          'exclude_term'
          'exclude_term_combination'
          'custom_meta'
          'term_field_meta'
          'current_term'
         */
        $terms_args = array();

        $taxonomies_from_type = get_object_taxonomies('attachment');

        $terms_included = array();
        $terms_excluded = array();

        switch ($settings['term_from']) {
            case 'post_term':
                if (!empty($settings['include_term']))
                    $terms_included = $settings['include_term'];

                break;
            case 'custom_meta':
                if (!empty($settings['term_field_meta']))
                    $terms_included = get_post_meta(get_the_ID(), $settings['term_field_meta'], true);

                break;
            case 'current_term':
                foreach ($taxonomies_from_type as $tax) {
                    $currentpost_terms = get_the_terms(get_the_ID(), $tax);

                    foreach ($currentpost_terms as $term) {
                        array_push($terms_included, $term->term_id);
                    }
                }
                break;
        }

        // l'esclusione vale in ogni caso, permette di modellare la queri in caso di termini multipli
        if (!empty($settings['exclude_term'])) {
            $terms_excluded = $settings['exclude_term'];
            $terms_excluded = Utils::explode($terms_excluded);
        }
        //risolvo bug: quando il dato è una stringa o numero e non Array, quindi converto.
        $terms_included = Utils::explode($terms_included);
        //var_dump($terms_included);
        //
        $taxquery = array();
        $taxquery_inc = array();
        $taxquery_exc = array();

        foreach ($taxonomies_from_type as $tax) {

            // 0 - questi sono i termini solo di questa $tax (array di IDs)
            $filtered_terms_included = array();
            $filtered_terms_excluded = array();
            // 1 - leggo tutti i termini di questa taxonomy
            $taxterms = get_terms(array(
                'taxonomy' => $tax,
                'hide_empty' => false,
            ));
            // 2 - li confronto con quelli selezionati e ne ricavo solo quelli di qusta taxonomy
            foreach ($taxterms as $term) {

                if (!empty($terms_included))
                    if (in_array($term->term_id, $terms_included)) {
                        array_push($filtered_terms_included, $term->term_id);
                    }

                if (!empty($terms_excluded))
                    if (in_array($term->term_id, $terms_excluded)) {
                        array_push($filtered_terms_excluded, $term->term_id);
                    }
            }
            // +++++++++++++++++++++
            if (count($filtered_terms_included)) {
                foreach ($filtered_terms_included as $fti) {
                    array_push($taxquery_inc, array(
                        'taxonomy' => $tax,
                        'field' => 'term_id',
                        'terms' => $fti,
                    ));
                }
            }
            if (count($filtered_terms_excluded)) {
                foreach ($filtered_terms_excluded as $fte) {
                    array_push($taxquery_exc, array(
                        'taxonomy' => $tax,
                        'field' => 'term_id',
                        'terms' => $fte,
                        'operator' => 'NOT IN'
                    ));
                }
            }
            //var_dump($filtred_terms);
        }

        $taxquery_inc['relation'] = $settings['include_term_combination'];
        $taxquery_exc['relation'] = $settings['exclude_term_combination'];
        $taxquery = [$taxquery_inc, $taxquery_exc];
        $taxquery['relation'] = 'AND';
        //
        $terms_args['tax_query'] = $taxquery;

        //var_dump($taxquery);
        //
        return $terms_args;
    }

    protected function get_date_filter($settings) {
        /* -------- DATE ------- */

        $date_args = array();
        if ($settings['querydate_mode']) {

            $date_after = $date_before = false;
            switch ($settings['querydate_mode']) {
                case 'past':
                    $date_before = date('Y-m-d H:i:s');
                    break;
                case 'future':
                    $date_after = date('Y-m-d H:i:s');
                    break;
                case 'today':
                    $date_after = date('Y-m-d 00:00:00');
                    $date_before = date('Y-m-d 23:23:59');
                    break;
                case 'yesterday':
                    $date_after = date('Y-m-d 00:00:00', strtotime('-1 day'));
                    $date_before = date('Y-m-d 23:23:59', strtotime('-1 day'));
                    break;
                case 'days':
                case 'weeks':
                case 'months':
                case 'years':
                    $date_after = '-' . $settings['querydate_range'] . ' ' . $settings['querydate_mode'];
                    $date_before = 'now';
                    break;
                case 'period':
                    $date_after = $settings['querydate_date_from'];
                    $date_before = $settings['querydate_date_to'];
                    break;

                    // compare by post publish date
                    $date_args['date_query'] = array(
                        array(
                            'after' => $date_after,
                            'before' => $date_before,
                            'inclusive' => true,
                        )
                    );
            }
        }
        return $date_args;
    }

    /* public function render() {
      echo 'E-ADDONS query WIDGET';
      $settings = $this->get_settings_for_display();
      if (empty($settings))
      return;


      // ------------------------------------------
      $id_page = Utils::get_the_id();
      $type_page = get_post_type();
      // ------------------------------------------

      echo 'e-addons-query'.$id_page;
      var_dump($settings['include_term']);
      //var_dump($settings['hover_opacity']);
      //var_dump($settings['layout_items']);
      } */
}

/*
          'post_type'
          --'posts_per_page'
          --'posts_offset'
          --'orderby'
          --'metakey' ...
          --'order'
          --'exclude_posts'
         */
