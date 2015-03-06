<?php
/*
Plugin Name: Limit Characters in Title
Plugin URI: https://wordpress.org/plugins/limit-characters-in-title
Description: Limit the number of characters in Title.
Author: Isaías Oliveira
Author URI: https://www.facebook.com/isaiaswebnet
Version: 0.1
Text Domain: Limit-Characters-in-Title
License: GPLv2 or later
*/
if (!class_exists('Characters_Title')):
    class Characters_Title {
        /* Plugin version */
        const VERSION = '0.1';
        /* Instance of this class */
        protected static $instance;
        /* Returns Instance Class */
        public static function get_instance() {
            if (null == self::$instance) {
                self::$instance = new Characters_Title();
            }
            return self::$instance;
        }
        /* Construct Class */
        public function __construct() {
            /* Hooks */
            add_filter('cmb_meta_boxes', array($this, 'lct_insert_meta_box'));
            add_filter('the_title', array($this, 'lct_insert_substr_title'));
            add_action('init', array($this, 'lct_initialize_meta_box_class'), 9999);
        }
        /* Insert Meta Box */
        public function lct_insert_meta_box($meta_boxes) {
            $types = get_post_types('', 'names');
            $prefix = 'lct_';
            $meta_boxes[] = array('id' => 'limit_characters_in_title', 'title' => 'Limite de Caracteres no T&iacute;tulo', 'pages' => $types, 'context' => 'side', 'priority' => 'high', 'show_names' => true, 'fields' => array(array('name' => 'N&uacute;mero de Caracteres', 'desc' => '', 'id' => $prefix . 'charlimit', 'type' => 'text_small', 'attributes' => array(
            //'placeholder' => 'N&uacute;mero',
            'size' => 3, 'type' => 'number', 'min' => '1', 'max' => '200', 'step' => '1'),), array('name' => 'Retic&ecirc;ncias (...)', 'id' => $prefix . 'suspension_points', 'type' => 'radio', 'options' => array('no' => 'N&atilde;o', 'yes' => 'Sim',), 'default' => 'no',)));
            return $meta_boxes;
        }
        /* Insert substr() in Title */
        public function lct_insert_substr_title($title_limit) {
			$prefix = 'lct_';
            global $post;
            if (in_the_loop() && $title_limit == $post->post_title) {
                $custom = get_post_custom($post->ID);
                $charlimit = isset($custom[$prefix."charlimit"][0]);
				$suspension = isset($custom[$prefix."suspension_points"][0])=='yes' ? '...' : '';
				$title_limit = $charlimit?substr($title_limit, 0, $charlimit).$suspension:$title_limit;
            }
            return $title_limit;
        }
        /* Initialize the metabox class */
        public function lct_initialize_meta_box_class() {
            if (!class_exists('cmb_Meta_Box')) require_once 'metabox/init.php';
        }
    } // End Limit_Characters_Title class
    /* Plugins Loaded */
    add_action('plugins_loaded', array('Characters_Title', 'get_instance'), 0);
endif; // End Limit_Characters_Title class_exists