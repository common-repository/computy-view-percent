<?php
/*class admin page*/

class Vp_Computy_Admin {

    public static function init() {
        add_action( 'admin_menu', array( 'Vp_Computy_Admin', 'add_admin_menu' ) );/* инициализируем меню в админке*/
        add_action( 'admin_enqueue_scripts', array( 'Vp_Computy_Admin', 'load_scripts' ) );/*Загружаем скрипты и стили*/
        add_filter( 'plugin_action_links_' . plugin_basename( plugin_dir_path( __FILE__ ) . 'computy-view-percent.php' ), array( 'Vp_Computy_Admin', 'admin_plugin_settings_link' ) );/*добавляем ссылку на настройки на странице плагинов */

        add_action( 'admin_init', array( 'Vp_Computy_Admin', 'plugin_settings' ) );/*Вывод настроек в меню*/
    }

    public static function add_admin_menu() {
        $hello1 = __( 'View percent', 'computy-view-percent' );
        add_options_page( ' ', $hello1, 'manage_options', 'vp-plugin-options', array( 'Vp_Computy_Admin', 'vp_plugin_options' ) );
    }

    public static function admin_plugin_settings_link( $links ) {
        $settings_link = '<a href="options-general.php?page=vp-plugin-options">'.__("Manual and settings").'</a>';
        array_unshift( $links, $settings_link );
        return $links;
    }

    public static function load_scripts() {
        wp_register_style( 'vp-computy-style-admin.css', plugin_dir_url( __FILE__ ) . 'css/vp-computy-style-admin.css', array(), VP_COMPUTY_VERSION );
        wp_enqueue_style( 'vp-computy-style-admin.css' );
    }

    public static function plugin_settings() {
        // параметры: $option_group_vp - обязательно должна быть уникальной переменной
        register_setting( 'option_group_vp', 'vp_option_name', 'sanitize_callback' );
        $trans2  = __( 'Styles line', 'computy-view-percent' );
        add_settings_section( 'section_id', $trans2, '', 'primer_page_vp' );
        add_settings_field( 'primer_field1', 'Color line', array( 'Vp_Computy_Admin', 'fill_primer_field1' ), 'primer_page_vp', 'section_id' );
        add_settings_field( 'primer_field2', 'Height line', array( 'Vp_Computy_Admin', 'fill_primer_field2' ), 'primer_page_vp', 'section_id' );
        add_settings_field( 'primer_field3', 'Shadow line', array( 'Vp_Computy_Admin', 'fill_primer_field3' ), 'primer_page_vp', 'section_id' );
        add_settings_field( 'primer_field4', 'Show on static pages', array( 'Vp_Computy_Admin', 'fill_static_page' ), 'primer_page_vp', 'section_id' );


    }

    ## Заполняем опцию 1
    public static function fill_primer_field1() {
        $trans = '#fc6';
        $val = get_option( 'vp_option_name' );
        $val['vp-color'] = $val['vp-color'] ?? '';

        if ( $val['vp-color'] == '' ) {$val['vp-color'] = $trans;}
        $val = $val ? $val['vp-color'] : $trans;
        ?>
        <input style="width: 150px" placeholder="#000" type="text" name="vp_option_name[vp-color]" value="<?php echo esc_attr( $val ) ?>" />
        <div style="display: inline-block;width: 20px;height: 20px;margin: 0 0 -4px 0;background: <?php echo esc_attr( $val ) ?>;"></div>
        <?php
    }

    ## Заполняем опцию 2
    public static function fill_primer_field2() {
        $trans = '5';
        $val = get_option( 'vp_option_name' );
        $val['vp-height'] = $val['vp-height'] ?? '';
        if ( $val['vp-height'] == '' ) {$val['vp-height'] = $trans;}
        $val = $val ? $val['vp-height'] : $trans;
        ?>
        <input style="width: 150px" placeholder="5" type="text" name="vp_option_name[vp-height]" value="<?php echo esc_attr( $val ) ?>" /> px
        <?php
    }

## Заполняем опцию 3
    public static function fill_primer_field3() {
        $val = get_option( 'vp_option_name' );
        if(isset( $val['shadow'])){ $vp = $val['shadow'];}else{ $vp= '';}
        $val = $val ? $vp : null;
        ?>
        <label><input type="checkbox" name="vp_option_name[shadow]" value="1" <?php checked( 1, $val ) ?> /> <?php _e( ' shadow line', 'nps-computy' ); ?>
        </label>
        <?php
    }

    public static function fill_static_page() {
        $val = get_option( 'vp_option_name' );
        $checked = isset($val['static-page']) ? "checked" : "";
        ?>
        <input name="bonus_option_name[static-page]" type="checkbox" value="1" <?php echo $checked; ?>>
    <?php }

## Очистка данных
    public static function sanitize_callback( $options ) {
            foreach ( $options as $name => & $val ) {
            if ( $name === 'vp-color' ) {
                $val = strip_tags($val);}
            if ( $name === 'vp-height' ) {
                $val = strip_tags($val);
            }
            if ( $name === 'shadow' ) {
                $val = (int)$val;
            }
        }
        return $options;
    }


    public static function vp_plugin_options()
    {
        ?>
        <div class="wrap vp-computy-admin">
            <h2 class="computy"><?php echo _e('Computy view percent', 'computy-view-percent'), ' V', VP_COMPUTY_VERSION; ?></h2>
            <p><?php echo _e('With the support of <a href="https://computy.ru" target="_blank" title="Development and support of sites on WordPress"> Computy </a>', 'vp-computy') ?> </p>
            <hr/>
            <h2><?php echo _e('Description of the plugin', 'computy-view-percent') ?></h2>
            <p><?php echo _e('Welcome to the percentage viewer plugin configuration guide page. ', 'computy-view-percent') ?></p>
            <p><?php echo _e('The plugin does not require any settings and works automatically for standard WordPress posts. When scrolling the page, the red bar at the top increases.', 'vp-computy') ?></p>
            <img alt="view percent" class="vp-img" src="<?php echo plugins_url() ?>/computy-view-percent/img/1.png"/>
            <p><?php echo _e('The plugin supports translation into your language. You can help in the translation by going to the page of <a href="https://wordpress.org/plugins/computy-view-percent/" target="_blank">the plugin</a> in the <a href="https://translate.wordpress.org/projects/wp-plugins/computy-view-percent" target="_blank">section</a>.', 'computy-view-percent') ?></p>
            <p><?php echo _e('For suggestions for improving the plugin or questions, please write by in the <a href="https://wordpress.org/support/plugin/computy-view-percent" target="_blank">forum</a>. We welcome feedback.', 'computy-view-percent') ?></p>
            <p><?php echo _e('The source code is open to your creativity and you can customize everything you need. But we warn that after updating the plugin your changes will be lost. We advise to update the plugin, because we constantly add something new and improve the plugin. Thank you for using our development. <a href="https://computy.ru" target="_blank">Computy</a> wishes you success.', 'computy-view-percent') ?></p>
            <hr/>
        </div>
        <div class="wrap vp-computy-admin">
            <h2><?php echo _e('Settings plugin', 'computy-view-percent') ?></h2>
            <form action="options.php" method="POST">
                <?php
                settings_fields( 'option_group_vp' );     // Скрытые защитные поля
                do_settings_sections( 'primer_page_vp' ); // Секции с настройками (опциями). У нас она всего одна 'section_id'
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }
}