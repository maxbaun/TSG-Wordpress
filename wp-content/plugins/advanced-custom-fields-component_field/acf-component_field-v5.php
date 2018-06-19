<?php

defined('ABSPATH') or die('No script kiddies please!');

/**
* ACF Component Field Class
*
* Using existing functionality of repeater field, extends it to another
* field type that allows embedding the entire group without recreating.
*
* @since       1.0.0
* @version     1.0.14
* @class       acf_field_component_field
* @extends     acf_field_repeater
*/
class acf_field_component_field extends acf_field_repeater
{
    /**
     * Constructor
     *
     * @since   1.0.0
     * @version 1.0.14
     * @return  void
     */
    public function __construct()
    {
        $this->name = 'component_field';
        $this->label = __('Component Field', 'acf-component_field');
        $this->category = 'relational';
        $this->defaults = array(
            'field_group_id' => null,
            'sub_fields'     => array(),
            'min'            => 1,
            'max'            => 1,
            'layout'         => 'block',
            'button_label'   => __("Add Row",'acf'),
            'collapsed'      => ''
        );

        // create a custom status for this field, looks prettier in the table list
        add_action('init', array($this, 'register_component_post_status'));

        // add side metabos for component checkbox
        add_action('add_meta_boxes', array($this, 'add_component_meta_boxes'));

        // update the group to acf-component status on save
        add_action('acf/update_field_group', array($this, 'update_component_post_status'));

        // include the component groups on export screen
        add_action('pre_get_posts', array($this, 'display_component_on_export'));

        // filter the select dropdown to populate proper options
        add_filter("acf/prepare_field/type=select", array($this, 'filter_avaliable_field_groups'));

        // include the acf-component into the list,
        // so the file syncing won't think it doesn't exists admin/field-groups.php:25
        // also need to make sure this trigger before the json local cache
        add_filter('acf/get_field_groups', array($this, 'include_component_field_groups'), 5);

        // apply appearance classes
        add_filter('acf/prepare_field/type=component_field', array($this, 'apply_appearance_classes'));

        // change the proper status when a field group is duplicated
        add_action('acf/duplicate_field_group', array($this, 'update_component_status_on_duplication'));

        // append 'acf-component' status to all wp_query that queries 'acf-field-group'
        add_action('pre_get_posts', array($this, 'include_component_post_status'));

        // called the base, no parent, cause we're hacking the repeater
        acf_field::__construct();
    }

    /**
     * Initialize
     *
     * @since   1.0.13
     * @version 1.0.13
     * @return  void
     */
    public function initialize()
    {
        // nothing
    }

    /**
     * Field Setting options.
     *
     * @since   1.0.0
     * @version 1.0.5
     * @param   object $field Current field object
     * @return  void
     */
	public function render_field_settings($field)
    {
        // The ACF Group that we want to use
        acf_render_field_setting($field, array(
            'label'         => __('Field Group', 'acf-component_field'),
            'instructions'  => __('Select a field group to be used', 'acf-component_field'),
            'type'          => 'select',
            'name'          => 'field_group_id',
            'required'      => 1,
            'allow_null'    => true,
            'choices'       => array(), // choices will be loaded from filter
            'acf-component_field::select_group' => 1
        ));

        // min
        acf_render_field_setting($field, array(
            'label'         => __('Minimum Rows', 'acf'),
            'instructions'  => '',
            'type'          => 'number',
            'name'          => 'min',
            'placeholder'   => '0',
        ));


        // max
        acf_render_field_setting($field, array(
            'label'         => __('Maximum Rows', 'acf'),
            'instructions'  => '',
            'type'          => 'number',
            'name'          => 'max',
            'placeholder'   => '0',
        ));

        // Same as repeater field
        acf_render_field_setting($field, array(
            'label'         => __('Layout', 'acf'),
            'instructions'  => '',
            'class'         => 'acf-repeater-layout',
            'type'          => 'radio',
            'name'          => 'layout',
            'layout'        => 'horizontal',
            'choices'       => array(
                'table'     => __('Table', 'acf'),
                'block'     => __('Block', 'acf'),
                'row'       => __('Row', 'acf')
            )
        ));

        // Same as repeater field
        acf_render_field_setting($field, array(
            'label'         => __('Button Label', 'acf'),
            'instructions'  => '',
            'type'          => 'text',
            'name'          => 'button_label',
        ));

        // Same as repeater field
        acf_render_field_setting($field, array(
            'label'         => __('Component Appearances', 'acf-component_field'),
            'instructions'  => sprintf(
                '%s <br/>%s',
                __('Alternative styles for component box.', 'acf-component_field'),
                __('(usually used for non-repeating nesting components)', 'acf-component_field')
            ),
            'type'          => 'checkbox',
            'name'          => 'appearances',
            // 'layout'        => 'horizontal',
            'choices'       => array(
                'hide-outer-boundary' => __('Hide outer boundary', 'acf-component_field'),
                'hide-outer-label'   => __('Hide outer field label', 'acf-component_field')
            )
        ));
	}

    /**
     * Filter the value retured from db
     *
     * @since  1.0.0
     * @version  1.0.14
     * @param  object $field Current field object
     * @return object The filtered values
     */
    public function load_field($field)
    {
        // check if current operation is exporting
        if (! $this->isExporting()) {
            // Treat the selected fields as a 'sub-field' in repeater
            $field['sub_fields'] = $this->inject_sub_fields($field);
        }

        // In order to leverage repeater field's helper functions ( have_rows(),
        // has_rows(), the_sub_field(), get_sub_field() ), we need to fake it
        // as a repeater field. (currently no action hook in the helper function)
        if (! is_admin()) {
            $field['type'] = 'repeater';
        }

        return $field;
    }

    /**
     * Render the field
     *
     * @since  1.0.2
     * @deprecated 1.0.12
     * @param  array $field current field instance
     * @return void
     */
    // public function render_field($field)
    // {
    // }

    /**
     * Add scripts for input editing page
     *
     * @since  1.0.0
     * @version 1.0.10
     * @return void
     */
    public function input_admin_enqueue_scripts()
    {
        $dir = plugin_dir_url(__FILE__);

        wp_enqueue_script(
            'acf-input-component_field',
            "{$dir}js/input.js",
            array('acf-pro-input'),
            false,
            acf_get_setting('version')
        );

        wp_enqueue_style(
            'acf-input-component_field',
            "{$dir}css/input.css"
        );
    }

    /**
     * Add scripts for group editing page
     *
     * @since  1.0.0
     * @return void
     */
    public function field_group_admin_enqueue_scripts()
    {
        $dir = plugin_dir_url(__FILE__);

        wp_enqueue_script(
            'acf-group-component_field',
            "{$dir}js/group.js",
            array('acf-pro-input'),
            false,
            acf_get_setting('version')
        );

        wp_enqueue_style(
            'acf-group-component_field',
            "{$dir}css/group.css"
        );
    }

    /**
     * Register a seperated post status to indicate component
     *
     * @since  1.0.0
     * @return void
     */
    public function register_component_post_status()
    {
        register_post_status('acf-component', array(
            'label'                     => __('Component', 'acf-component_field'),
            'public'                    => ! is_admin(),
            'exclude_from_search'       => true,
            'show_in_admin_all_list'    => false,
            'show_in_admin_status_list' => true,
            'label_count'               => _n_noop('Component <span class="count">(%s)</span>', 'Components <span class="count">(%s)</span>', 'acf-component_field'),
        ));
    }

    /**
     * Add metabox in group editing page for assigning as component
     *
     * @since  1.0.0
     * @return void
     */
    public function add_component_meta_boxes()
    {
        add_meta_box(
            'acf-component-field-metabox',
            __('Used as ACF Component Field?', 'acf-component_field'),
            array($this, 'component_metabox_callback'),
            'acf-field-group',
            'side'
        );
    }

    /**
     * Callback for the metabox output
     *
     * @since  1.0.0
     * @return void
     */
    public function component_metabox_callback()
    {
        global $post;
        $checked = $post->post_status == 'acf-component'? 'checked' : '';
        printf('<input type="hidden" name="%s" value="0" />', 'acf_field_group[is_acf_component]');
        printf('<label><input type="checkbox" name="%s" value="1" %s id="is_acf_component_checkbox" /> %s.</label>',
            'acf_field_group[is_acf_component]',
            $checked, __('this is a component', 'acf-component_field')
        );
    }

    /**
     * Check and assign the group to acf-componet status on save and import
     *
     * @since  1.0.1
     * @version 1.0.9
     * @param  array $field_group the group object
     * @return void
     */
    public function update_component_post_status($field_group)
    {
        // update the post status when it's saving the group
        if ($group = acf_maybe_get($_POST, 'acf_field_group', false)) {
            if (acf_maybe_get($group, 'is_acf_component', null)) {
                $field_group['post_status'] = 'acf-component';
                wp_update_post($field_group);
            }
        }

        // if it's updated from a sync, update the status as well
        if (acf_maybe_get($_GET, 'acfsync') || acf_maybe_get($_GET, 'action2') === 'acfsync') {
            if (acf_maybe_get($field_group, 'is_acf_component', null)) {
                $field_group['post_status'] = 'acf-component';
                wp_update_post($field_group);
            }
        }

        // update the post status when it's importing from json
        if (isset($_FILES['acf_import_file'])) {
            if (acf_maybe_get($field_group, 'is_acf_component', null)) {
                $field_group['post_status'] = 'acf-component';
                wp_update_post($field_group);
            }
        }
    }

    /**
     * Add acf-component status on the export lists
     *
     * @since  1.0.0
     * @param  object $query thee query to retreive export list
     * @return void
     */
    public function display_component_on_export($query)
    {
        if (acf_maybe_get($_GET, 'page') == 'acf-settings-tools') {
            $post_status = $query->get('post_status');

            // some of the code is passing string...
            if (is_string($post_status)) {
                $post_status .= ', acf-component';
            } else
            if (is_array($post_status)) {
                $post_status[] = 'acf-component';
            }

            $query->set('post_status', $post_status);
        }
    }

    /**
     * Reset the repeater field's delete hook,
     * cause we don't want to delete the component
     *
     * @since  1.0.0
     * @param  array $field current field instance
     * @return void
     */
    public function delete_field( $field ) {}

    /**
     * Reset the repeater field's duplicate hook,
     * cause component should not be duplicated
     *
     * @since  1.0.0
     * @param  array $field current field instance
     * @return void
     */
    public function duplicate_field( $field )
    {
        return $field;
    }

    /**
     * Fetch the avaliable field groups for select
     *
     * @since  1.0.0
     * @version 1.0.5
     * @return array Avaliable field groups
     */
    protected function fetch_avaliable_field_groups()
    {
        $avaliable_groups = array();

        // load forom local php
        $local_groups = acf_local()->groups;
        foreach ($local_groups as $group) {
            if (isset($group['is_acf_component']) && $group['is_acf_component']) {
                $avaliable_groups[$group['key']] = $group['title'];
            }
        }

        // then we load the ones from database
        $groups_query = new WP_Query();
        $groups_query->query(array(
            'post_type'      => 'acf-field-group',
            'posts_per_page' => -1,
            'post_status'    => 'acf-component',
            'post__not_in'   => isset($_GET['post'])? array((int) $_GET['post']) : array()
        ));

        foreach ($groups_query->posts as $group) {
            $avaliable_groups[$group->post_name] = $group->post_title;
        }

        return $avaliable_groups;
    }

    /**
     * Get the component field group
     *
     * @since  1.0.2
     * @version 1.0.11
     * @param  array $field current field instance
     * @return array
     */
    protected function inject_sub_fields($field) {
        $group_key = $field['field_group_id'];

        // bail early
        if (! $group_key) {
            return array();
        }

        // if acf is able to load it from local json or php, then we return it
        if ($field_group = acf_get_field_group($group_key)) {
            return acf_get_fields($field_group);
        }

        if ($this->is_wpml_translatable()) {
            $translated = $this->getTranslatedFieldGroup($group_key);
            $group_key = $translated;
        }

        // vars
        $args = array(
            'posts_per_page'    => 1,
            'post_type'         => 'acf-field-group',
            'orderby'           => 'menu_order title',
            'order'             => 'ASC',
            'suppress_filters'  => false,
            'post_status'       => array('acf-component'),
            'pagename'          => $group_key // hacky, but there's no parameter for post_name
        );

        // load posts
        $posts = get_posts($args);

        // validate
        if (empty($posts)) {
            return array();
        }

        return acf_get_fields($posts[0]->ID);
    }

    /**
     * Filter the component field group
     *
     * @since   1.0.5
     * @version 1.0.11
     * @param   array $field current field instance
     * @return  array
     */
    public function filter_avaliable_field_groups($field) {
        if ($field['type'] == 'select' && isset($field['acf-component_field::select_group'])) {
            $field['choices'] = $this->fetch_avaliable_field_groups();

            if (! array_key_exists($field['value'], $field['choices']) && $this->is_wpml_translatable()) {
                $translated = $this->getTranslatedFieldGroup($field['value']);
                $field['value'] = $translated;
            }

        }
        return $field;
    }

    /**
     * Include the ACF component field groups
     *
     * @since   1.0.6
     * @version 1.0.8
     * @param   array $groups field groups
     * @return  array
     */
    public function include_component_field_groups($groups)
    {
        $args = array(
            'posts_per_page'    => -1,
            'post_type'         => 'acf-field-group',
            'orderby'           => 'menu_order title',
            'order'             => 'ASC',
            'suppress_filters'  => false,
            'post_status'       => array('acf-component')
        );

        // load posts from db
        $posts = get_posts($args);

        foreach ($posts as $post) {
            $groups[] = acf_get_field_group($post);
        }

        return $groups;
    }

    /**
     * Add additional classes to the outer wrapper
     *
     * @since   1.0.10
     * @param   array $field field
     * @return  array
     */
    public function apply_appearance_classes($field)
    {
        $appearances = (isset($field['appearances']) && is_array($field['appearances']))?
            $field['appearances'] :
            array();

        foreach ($appearances as $a) {
            $field['wrapper']['class'] .= " acf-$a";
        }

        return $field;
    }

    /**
     * Update the post status on field group duplication
     *
     * @since   1.0.11
     * @param   array $field_group field_group
     * @return  void
     */
    public function update_component_status_on_duplication($field_group)
    {
        if (! acf_maybe_get($field_group, 'is_acf_component', 0)) {
            return;
        }

        wp_update_post(array(
            'ID' => $field_group['ID'],
            'post_status' => 'acf-component'
        ));
    }

    /**
     * Append component status to the query
     *
     * @since  1.0.14
     * @param  object $query WP_Query
     * @return boid
     */
    public function include_component_post_status($query)
    {
        if ($query->get('post_type') != 'acf-field-group') {
            return;
        }

        if (! is_array($query->get('post_status')) ) {
            return;
        }

        $postStatus = $query->get('post_status');
        $postStatus[] = 'acf-component';

        $query->set('post_status', $postStatus);
    }

    /**
     * Get the translation field group key without the wpml adjust id filter on
     *
     * @since   1.0.11
     * @param   string $field_group_key field_group_key
     * @return  string
     */
    protected function getTranslatedFieldGroup($field_group_key)
    {
        $args = array(
            'posts_per_page'    => 1,
            'post_type'         => 'acf-field-group',
            'orderby'           => 'menu_order title',
            'order'             => 'ASC',
            'suppress_filters'  => true,
            'post_status'       => array('acf-component'),
            'pagename'          => $field_group_key
        );

        $posts = get_posts( $args );

        return $posts? get_post(icl_object_id($posts[0]->ID, 'acf-field-group'))->post_name : $field_group_key;
    }

    protected function is_wpml_translatable()
    {
        if (! defined('ICL_SITEPRESS_VERSION')) return false;

        $sync_option = icl_get_setting('custom_posts_sync_option');

        return acf_maybe_get($sync_option, 'acf-field-group', false);
    }

    /**
     * Check if currently is exporting field group,
     * acf pro v5.6.5 added new "admin tool" interface
     *
     * @since  1.0.14
     * @return boolean
     */
    protected function isExporting()
    {
        if (isset($_POST['acf_export_keys'])) {
            return true;
        }

        if (acf_maybe_get_GET('tool') == 'export' && acf_maybe_get_GET('keys')) {
            return true;
        }

        if (acf_maybe_get_POST('action') == 'download' && acf_maybe_get_POST('keys')) {
            return true;
        }

        return false;
    }
}
