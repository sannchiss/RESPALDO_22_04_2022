<?php

/**
 * Suppress "error - 0 - No summary was found for this file" on phpdoc generation
 *
 * @package WPDataAccess\Data_Tables
 */
namespace WPDataAccess\Data_Tables;

use  stdClass ;
use  WPDataAccess\Connection\WPDADB ;
use  WPDataAccess\Data_Dictionary\WPDA_Dictionary_Exist ;
use  WPDataAccess\Data_Dictionary\WPDA_Dictionary_Lists ;
use  WPDataAccess\Data_Dictionary\WPDA_List_Columns_Cache ;
use  WPDataAccess\Macro\WPDA_Macro ;
use  WPDataAccess\Plugin_Table_Models\WPDA_Publisher_Model ;
use  WPDataAccess\Plugin_Table_Models\WPDA_Media_Model ;
use  WPDataAccess\Plugin_Table_Models\WPDA_Table_Settings_Model ;
use  WPDataAccess\List_Table\WPDA_List_Table ;
use  WPDataAccess\Premium\WPDAPRO_Geo_Location\WPDAPRO_Geo_Location_WS ;
use  WPDataAccess\Templates\WPDAPRO_Template_Data_Publisher_Color ;
use  WPDataAccess\Templates\WPDAPRO_Template_Data_Publisher_Space ;
use  WPDataAccess\Templates\WPDA_Template_Data_Publisher_Corner ;
use  WPDataAccess\WPDA ;
use function  GuzzleHttp\json_encode ;
/**
 * Class WPDA_Data_Tables
 *
 * @author  Peter Schulz
 * @since   1.0.0
 */
class WPDA_Data_Tables
{
    protected  $wpda_list_columns ;
    public static function enqueue_styles_and_script( $styling = 'default' )
    {
        // Plugin css
        wp_enqueue_style( 'wpda_datatables_default' );
        wp_enqueue_style( 'dashicons' );
        // Needed to display icons for media attachments
        // Plugin js
        wp_enqueue_script( 'purl' );
        wp_enqueue_script( 'wpda_datatables' );
        if ( is_admin() && WPDA::get_option( WPDA::OPTION_BE_LOAD_DATATABLES ) === 'on' || !is_admin() && WPDA::get_option( WPDA::OPTION_FE_LOAD_DATATABLES ) === 'on' ) {
            wp_enqueue_script( 'jquery_datatables' );
        }
        if ( is_admin() && WPDA::get_option( WPDA::OPTION_BE_LOAD_DATATABLES_RESPONSE ) === 'on' || !is_admin() && WPDA::get_option( WPDA::OPTION_FE_LOAD_DATATABLES_RESPONSE ) === 'on' ) {
            wp_enqueue_script( 'jquery_datatables_responsive' );
        }
        switch ( $styling ) {
            case 'jqueryui':
                wp_enqueue_style( 'wpda_jqueryui_theme_structure' );
                wp_enqueue_style( 'wpda_jqueryui_theme' );
                wp_enqueue_style( 'jquery_datatables_jqueryui' );
                wp_enqueue_style( 'jquery_datatables_responsive_jqueryui' );
                wp_enqueue_script( 'jquery_datatables_jqueryui' );
                // Responsive icon not working with jqueryui file:
                // wp_enqueue_script( 'jquery_datatables_responsive_jqueryui' );
                break;
            case 'semantic':
                wp_enqueue_style( 'wpda_semantic_ui' );
                wp_enqueue_style( 'jquery_datatables_semanticui' );
                wp_enqueue_style( 'jquery_datatables_responsive_semanticui' );
                wp_enqueue_script( 'jquery_datatables_semanticui' );
                break;
            case 'foundation':
                wp_enqueue_style( 'wpda_foundation_ui' );
                wp_enqueue_style( 'jquery_datatables_foundation' );
                wp_enqueue_style( 'jquery_datatables_responsive_foundation' );
                wp_enqueue_script( 'jquery_datatables_foundation' );
                break;
            case 'bootstrap':
                wp_enqueue_style( 'wpda_bootstrap_ui' );
                wp_enqueue_style( 'jquery_datatables_bootstrap' );
                wp_enqueue_style( 'jquery_datatables_responsive_bootstrap' );
                wp_enqueue_script( 'jquery_datatables_bootstrap' );
                break;
            case 'bootstrap4':
                wp_enqueue_style( 'wpda_bootstrap4_ui' );
                wp_enqueue_style( 'jquery_datatables_bootstrap4' );
                wp_enqueue_style( 'jquery_datatables_responsive_bootstrap4' );
                wp_enqueue_script( 'jquery_datatables_bootstrap4' );
                break;
            default:
                if ( is_admin() && WPDA::get_option( WPDA::OPTION_BE_LOAD_DATATABLES ) === 'on' || !is_admin() && WPDA::get_option( WPDA::OPTION_FE_LOAD_DATATABLES ) === 'on' ) {
                    wp_enqueue_style( 'jquery_datatables' );
                }
                if ( is_admin() && WPDA::get_option( WPDA::OPTION_BE_LOAD_DATATABLES_RESPONSE ) === 'on' || !is_admin() && WPDA::get_option( WPDA::OPTION_FE_LOAD_DATATABLES_RESPONSE ) === 'on' ) {
                    wp_enqueue_style( 'jquery_datatables_responsive' );
                }
        }
    }
    
    /**
     * Generate jQuery DataTable code
     *
     * Table and column names provided are checked for existency and access to prevent hacking the DataTable code
     * and SQL injection.
     *
     * @param int    $pub_id Publication ID.
     * @param string $pub_name Publication name.
     * @param string $database Database name.
     * @param string $table_name Database table name.
     * @param string $column_names Comma seperated list of column names.
     * @param string $responsive Yes = responsive mode, No = No responsive mode.
     * @param int    $responsive_cols Number of columns to be displayd in responsive mode.
     * @param string $responsive_type Modal, Collaped or Expanded (only if $responsive = Yes).
     * @param string $responsive_icon Yes = show icon, No = do not show icon (only if $responsive = Yes).
     * @param string $sql_orderby SQL default order by
     * @param string $filter_field_name Filter field name (CSV)
     * @param string $filter_field_value Filter field value (CSV)
     * @param string $nl2br Convert New Line characters to BR tags
     *
     * @return string responsewpda_datatables_ajax_call
     *
     * @since   1.0.0
     *
     */
    public function show(
        $pub_id,
        $pub_name,
        $database,
        $table_name,
        $column_names,
        $responsive,
        $responsive_cols,
        $responsive_type,
        $responsive_icon,
        $sql_orderby,
        $filter_field_name = '',
        $filter_field_value = '',
        $nl2br = ''
    )
    {
        if ( '' === $pub_id && '' === $pub_name && '' === $table_name ) {
            return '<p>' . __( 'ERROR: Missing argument [need at least pub_id, pub_name or table argument]', 'wp-data-access' ) . '</p>';
        }
        
        if ( '' !== $pub_id || '' !== $pub_name ) {
            // Get publication information
            
            if ( '' !== $pub_id ) {
                $publication = WPDA_Publisher_Model::get_publication( $pub_id );
            } else {
                $publication = WPDA_Publisher_Model::get_publication_by_name( $pub_name );
            }
            
            if ( false === $publication ) {
                // Querying tables in other schema's is not allowed!
                return '<p>' . __( 'ERROR: Publication not found', 'wp-data-access' ) . '</p>';
            }
            $pub_id = $publication[0]['pub_id'];
            $pub_name = $publication[0]['pub_name'];
            $database = $publication[0]['pub_schema_name'];
            $table_name = $publication[0]['pub_table_name'];
            $column_names = $publication[0]['pub_column_names'];
            $responsive = strtolower( $publication[0]['pub_responsive'] );
            $responsive_popup_title = $publication[0]['pub_responsive_popup_title'];
            $responsive_cols = $publication[0]['pub_responsive_cols'];
            $responsive_type = strtolower( $publication[0]['pub_responsive_type'] );
            $responsive_icon = strtolower( $publication[0]['pub_responsive_icon'] );
            $pub_format = $publication[0]['pub_format'];
            $sql_orderby = $publication[0]['pub_default_orderby'];
            $pub_table_options_searching = $publication[0]['pub_table_options_searching'];
            $pub_table_options_ordering = $publication[0]['pub_table_options_ordering'];
            $pub_table_options_paging = $publication[0]['pub_table_options_paging'];
            $pub_table_options_advanced = $publication[0]['pub_table_options_advanced'];
            $pub_table_options_advanced = str_replace( [
                "\r\n",
                "\r",
                "\n",
                "\t"
            ], '', $pub_table_options_advanced );
            $pub_responsive_modal_hyperlinks = $publication[0]['pub_responsive_modal_hyperlinks'];
            $pub_sort_icons = $publication[0]['pub_sort_icons'];
        } else {
            $pub_id = '0';
            $responsive_popup_title = '';
            $pub_format = '';
            $pub_table_options_searching = 'on';
            $pub_table_options_ordering = 'on';
            $pub_table_options_paging = 'on';
            $pub_table_options_advanced = '';
            $pub_responsive_modal_hyperlinks = '';
            $pub_sort_icons = 'default';
        }
        
        $styling = WPDA::get_option( WPDA::OPTION_DP_STYLE );
        // Activate scripts and styles
        wp_enqueue_script( 'jquery-ui-draggable' );
        wp_enqueue_script( 'jquery-ui-resizable' );
        self::enqueue_styles_and_script( $styling );
        $header2 = '';
        try {
            $json = json_decode( $pub_table_options_advanced );
            if ( isset( $json->wpda_searchbox ) ) {
                $header2 = $json->wpda_searchbox;
            }
        } catch ( \Exception $e ) {
            $json = null;
        }
        if ( 'jqueryui' === $styling ) {
            $pub_sort_icons = 'none';
        }
        switch ( $pub_sort_icons ) {
            case 'plugin':
                // Use material ui icons
                wp_enqueue_style( 'wpda_material_icons' );
                wp_enqueue_style( 'wpda_datatables' );
            case 'none':
                // Hide jQuery Datatables sort icons
                wp_enqueue_style( 'wpda_datatables_hide_sort_icons' );
                break;
            default:
                // Show default jQuery Datatables sort icons
        }
        
        if ( 'on' !== $pub_table_options_searching || null === $pub_table_options_searching ) {
            $pub_table_options_searching = 'false';
        } else {
            $pub_table_options_searching = 'true';
        }
        
        
        if ( 'on' !== $pub_table_options_ordering || null === $pub_table_options_ordering ) {
            $pub_table_options_ordering = 'false';
        } else {
            $pub_table_options_ordering = 'true';
        }
        
        
        if ( 'on' !== $pub_table_options_paging || null === $pub_table_options_paging ) {
            $pub_table_options_paging = 'false';
        } else {
            $pub_table_options_paging = 'true';
        }
        
        
        if ( '' === $responsive_popup_title || null === $responsive_popup_title || 'Row details' === $responsive_popup_title ) {
            $responsive_popup_title = __( 'Row details', 'wp-data-access' );
            // Set title of modal window here to support i18n.
        }
        
        // WP database is the default
        
        if ( '' === $database ) {
            global  $wpdb ;
            $database = $wpdb->dbname;
        }
        
        // Check if table exists (prevent sql injection).
        $wpda_dictionary_checks = new WPDA_Dictionary_Exist( $database, $table_name );
        if ( !$wpda_dictionary_checks->table_exists( '0' === $pub_id, false ) ) {
            // Table not found.
            return '<p>' . __( 'ERROR: Invalid table name or not authorized', 'wp-data-access' ) . '</p>';
        }
        // Get table settings > hyperlinks
        $table_settings_db = WPDA_Table_Settings_Model::query( $table_name, $database );
        $hyperlinks = [];
        $table_settings = null;
        
        if ( isset( $table_settings_db[0]['wpda_table_settings'] ) ) {
            $table_settings = json_decode( $table_settings_db[0]['wpda_table_settings'] );
            if ( isset( $table_settings->hyperlinks ) ) {
                foreach ( $table_settings->hyperlinks as $hyperlink ) {
                    $hyperlink_label = ( isset( $hyperlink->hyperlink_label ) ? $hyperlink->hyperlink_label : '' );
                    $hyperlink_html = ( isset( $hyperlink->hyperlink_html ) ? $hyperlink->hyperlink_html : '' );
                    if ( $hyperlink_label !== '' && $hyperlink_html !== '' ) {
                        array_push( $hyperlinks, $hyperlink_label );
                    }
                }
            }
        }
        
        $geolocation = null;
        $row_count_estimate = WPDA::get_row_count_estimate( $database, $table_name, $table_settings );
        $calc_estimate = $row_count_estimate['is_estimate'];
        $hyperlink_positions = [];
        // Set columns to be queried.
        $this->wpda_list_columns = WPDA_List_Columns_Cache::get_list_columns( $database, $table_name );
        
        if ( '*' === $column_names ) {
            // Get all column names from table.
            $columns = [];
            // Create column ARRAY ***.
            foreach ( $this->wpda_list_columns->get_table_columns() as $column ) {
                $columns[] = $column['column_name'];
            }
        } else {
            $columns = explode( ',', $column_names );
            // Create column ARRAY.
            // Check if columns exist (prevent sql injection).
            $i = 0;
            foreach ( $columns as $column ) {
                
                if ( 'wpda_hyperlink_' !== substr( $column, 0, 15 ) ) {
                    if ( !$wpda_dictionary_checks->column_exists( $column ) ) {
                        // Column not found.
                        return __( 'ERROR: Column', 'wp-data-access' ) . ' ' . esc_attr( $column ) . ' ' . __( 'not found', 'wp-data-access' );
                    }
                } else {
                    $hyperlink_positions[] = $i;
                }
                
                $i++;
            }
        }
        
        // Get column labels
        $pub_format_json = json_decode( $pub_format, true );
        
        if ( isset( $pub_format_json['pub_format']['column_labels'] ) ) {
            $column_labels = $pub_format_json['pub_format']['column_labels'];
        } else {
            $column_labels = $this->wpda_list_columns->get_table_column_headers();
        }
        
        if ( !is_numeric( $responsive_cols ) ) {
            $responsive_cols = 0;
        }
        $wpda_database_columns = '';
        for ( $i = 0 ;  $i < count( $columns ) ;  $i++ ) {
            
            if ( 'wpda_hyperlink_' !== substr( $columns[$i], 0, 15 ) ) {
                $column_label = ( isset( $column_labels[$columns[$i]] ) ? $column_labels[$columns[$i]] : $columns[$i] );
            } else {
                $column_label = $hyperlinks[substr( $columns[$i], strrpos( $columns[$i], '_' ) + 1 )];
            }
            
            $wpda_database_columns_obj = (object) null;
            $wpda_database_columns_obj->className = $columns[$i];
            $wpda_database_columns_obj->name = $columns[$i];
            $wpda_database_columns_obj->targets = $i;
            $wpda_database_columns_obj->label = $column_label;
            $wpda_database_columns .= json_encode( $wpda_database_columns_obj );
            if ( $i < count( $columns ) - 1 ) {
                $wpda_database_columns .= ',';
            }
        }
        // Get language for jQuery DataTables
        $language = WPDA::get_option( WPDA::OPTION_DP_LANGUAGE );
        $columnsvar = 'wpdaDbColumns' . preg_replace( '/[^a-zA-Z0-9]/', '', $table_name ) . $pub_id;
        
        if ( has_filter( 'wpda_wpdataaccess_prepare' ) ) {
            $wpda_wpdataaccess_prepare_filter = apply_filters(
                'wpda_wpdataaccess_prepare',
                '',
                $database,
                $table_name,
                $pub_id,
                $columns,
                $table_settings
            );
        } else {
            $wpda_wpdataaccess_prepare_filter = '';
        }
        
        $buttons = '[]';
        $geomap = '';
        $geo_search = '';
        $geo_search_type = (object) null;
        $read_more_html = '';
        if ( 'false' === $pub_table_options_paging && null !== $json && isset( $json->serverSide ) && ("true" === $json->serverSide || true === $json->serverSide) ) {
            $read_more_html = "<div id=\"" . esc_attr( $table_name ) . "{$pub_id}_more_container\" class='wpda_more_container' >" . "<button id=\"" . esc_attr( $table_name ) . "{$pub_id}_more_button\" type='button' class='wpda_more_button dt-button'>SHOW MORE</button>" . "</div>";
        }
        $read_more = ( '' === $read_more_html ? 'false' : 'true' );
        
        if ( 'false' === $pub_table_options_searching || isset( $json->dom ) && (strpos( $json->dom, 'Q' ) !== false || strpos( $json->dom, 'P' ) !== false) ) {
            $header2 = '';
        } else {
            if ( isset( $json->dom ) && strpos( $json->dom, 'B' ) !== false && isset( $json->buttons ) && is_array( $json->buttons ) ) {
                foreach ( $json->buttons as $button ) {
                    
                    if ( isset( $button->extend ) && 'searchPanes' === $button->extend ) {
                        $header2 = '';
                        // Search panes
                    }
                
                }
            }
        }
        
        // Using s default styling library
        
        if ( 'jqueryui' === $styling ) {
            $dataTablesClass = 'ui-widget-content';
        } else {
            $dataTablesClass = '';
        }
        
        if ( 'on' === WPDA::get_option( WPDA::OPTION_DP_STYLE_COMPACT ) ) {
            $dataTablesClass .= ' compact';
        }
        switch ( $styling ) {
            case 'jqueryui':
                
                if ( isset( $json->dom ) ) {
                    $table = strpos( $json->dom, 't' );
                    
                    if ( false !== $table ) {
                        $before = substr( $json->dom, 0, $table );
                        $after = substr( $json->dom, $table + 1 );
                        $json->dom = '<"fg-toolbar ui-toolbar ui-widget-header ui-helper-clearfix ui-corner-tl ui-corner-tr"' . $before . '>' . 't' . '<"fg-toolbar ui-toolbar ui-widget-header ui-helper-clearfix ui-corner-bl ui-corner-br"' . $after . '>';
                    }
                
                }
                
                break;
            case 'semantic':
                
                if ( isset( $json->dom ) ) {
                    $tablePos = strpos( $json->dom, 't' );
                    $processPos = strpos( $json->dom, 'r' );
                    
                    if ( false !== $tablePos ) {
                        $before = str_replace( 'r', '', substr( $json->dom, 0, $tablePos ) );
                        $table = 't';
                        if ( false !== $processPos ) {
                            $table .= 'r';
                        }
                        $after = str_replace( 'r', '', substr( $json->dom, $tablePos + 1 ) );
                        $semantic = function ( $tags ) {
                            $cols = '';
                            
                            if ( strlen( $tags ) % 2 !== 0 ) {
                                $cols = "<'sixteen wide column'{$tags[0]}>";
                                $tags = substr( $tags, 1 );
                            }
                            
                            for ( $i = 0 ;  $i < strlen( $tags ) ;  $i++ ) {
                                $align = ( $i % 2 !== 0 ? 'right aligned' : '' );
                                $cols .= "<'{$align} eight wide column'{$tags[$i]}>";
                            }
                            return "<'row'{$cols}>";
                        };
                        $dom_before = $semantic( $before );
                        $dom_after = $semantic( $after );
                        $json->dom = "<'ui stackable grid'\n\t\t\t\t\t\t\t\t{$dom_before}\n\t\t\t\t\t\t\t\t<'row dt-table'\n\t\t\t\t\t\t\t\t<'sixteen wide column'{$table}>\n\t\t\t\t\t\t\t\t>\n\t\t\t\t\t\t\t\t{$dom_after}\n\t\t\t\t\t\t\t\t>";
                    }
                
                }
                
                break;
            case 'foundation':
                // Use jQuery DataTables default
                break;
            case 'bootstrap':
                
                if ( isset( $json->dom ) ) {
                    $tablePos = strpos( $json->dom, 't' );
                    $processPos = strpos( $json->dom, 'r' );
                    
                    if ( false !== $tablePos ) {
                        $before = str_replace( 'r', '', substr( $json->dom, 0, $tablePos ) );
                        $table = 't';
                        if ( false !== $processPos ) {
                            $table .= 'r';
                        }
                        $after = str_replace( 'r', '', substr( $json->dom, $tablePos + 1 ) );
                        $bootstrap3 = function ( $tags ) {
                            $cols = '';
                            
                            if ( strlen( $tags ) % 2 !== 0 ) {
                                $cols = "<'col-sm-12'{$tags[0]}>";
                                $tags = substr( $tags, 1 );
                            }
                            
                            for ( $i = 0 ;  $i < strlen( $tags ) ;  $i++ ) {
                                $cols .= "<'col-sm-6'{$tags[$i]}>";
                            }
                            return "<'row'{$cols}>";
                        };
                        $dom_before = $bootstrap3( $before );
                        $dom_after = $bootstrap3( $after );
                        $json->dom = $dom_before . "<'row'<'col-sm-12'tr>>" . $dom_after;
                    }
                
                }
                
                break;
            case 'bootstrap4':
                
                if ( isset( $json->dom ) ) {
                    $tablePos = strpos( $json->dom, 't' );
                    $processPos = strpos( $json->dom, 'r' );
                    
                    if ( false !== $tablePos ) {
                        $before = str_replace( 'r', '', substr( $json->dom, 0, $tablePos ) );
                        $table = 't';
                        if ( false !== $processPos ) {
                            $table .= 'r';
                        }
                        $after = str_replace( 'r', '', substr( $json->dom, $tablePos + 1 ) );
                        $bootstrap4 = function ( $tags ) {
                            $cols = '';
                            
                            if ( strlen( $tags ) % 2 !== 0 ) {
                                $cols = "<'col-sm-12'{$tags[0]}>";
                                $tags = substr( $tags, 1 );
                            }
                            
                            for ( $i = 0 ;  $i < strlen( $tags ) ;  $i++ ) {
                                $cols .= "<'col-sm-12 col-sm-6'{$tags[$i]}>";
                            }
                            return "<'row'{$cols}>";
                        };
                        $dom_before = $bootstrap4( $before );
                        $dom_after = $bootstrap4( $after );
                        $json->dom = $dom_before . "<'row'<'col-sm-12'tr>>" . $dom_after;
                    }
                
                }
                
                break;
            default:
                // Use default or provided custom dom
        }
        // Init styling
        $styling = '';
        $css = '';
        $add_modal_head = false;
        if ( null === $json ) {
            // Create JSON object if not already available
            $json = new stdClass();
        }
        $json->wpda_styling = $styling;
        // Convert JSON to string
        $json_value = json_encode( $json );
        // Prepare values needed for ajax request
        $database_value = $database;
        $column_names_value = $column_names;
        
        if ( '0' !== $pub_id ) {
            $database_value = '';
            $column_names_value = '';
        }
        
        return $wpda_wpdataaccess_prepare_filter . $styling . "<div class=''><table id=\"" . esc_attr( $table_name ) . "{$pub_id}\" class=\"display {$dataTablesClass}\" cellspacing=\"0\">" . "<thead>" . $this->show_header(
            $columns,
            $responsive,
            $responsive_cols,
            $pub_format,
            $hyperlinks,
            $header2,
            $json,
            $geolocation
        ) . "</thead>" . "<tfoot>" . $this->show_header(
            $columns,
            $responsive,
            $responsive_cols,
            $pub_format,
            $hyperlinks,
            '',
            $json,
            $geolocation
        ) . "</tfoot>" . "</table></div>" . $read_more_html . "<script type='text/javascript'>" . "var {$columnsvar}_advanced_options = " . $json_value . "; " . "var datatables_i18n_url = '" . plugins_url( '../assets/i18n/', __DIR__ ) . "';" . "var {$columnsvar} = [" . $wpda_database_columns . "];" . "var {$columnsvar}_geosearch_options = " . json_encode( $geo_search_type ) . "; " . "jQuery(function () {" . "\twpda_datatables_ajax_call(" . "\t\t{$columnsvar}," . "\t\t\"" . esc_attr( $database_value ) . "\"," . "\t\t\"" . esc_attr( $table_name ) . "\"," . "\t\t\"" . esc_attr( $column_names_value ) . "\"," . "\t\t\"" . esc_attr( $responsive ) . "\"," . "\t\t\"" . esc_attr( $responsive_popup_title ) . "\"," . "\t\t\"" . esc_attr( $responsive_type ) . "\"," . "\t\t\"" . esc_attr( $responsive_icon ) . "\"," . "\t\t\"" . esc_attr( $language ) . "\"," . "\t\t\"" . htmlentities( $sql_orderby ) . "\"," . "\t\t{$pub_table_options_searching}," . "\t    {$pub_table_options_ordering}," . "\t\t{$pub_table_options_paging}," . "\t\t{$columnsvar}_advanced_options," . "\t\t{$pub_id}," . "\t\t\"" . esc_attr( $pub_responsive_modal_hyperlinks ) . "\"," . "\t\t[" . implode( ',', $hyperlink_positions ) . "]," . "\t\t\"" . esc_attr( $filter_field_name ) . "\"," . "\t\t\"" . esc_attr( $filter_field_value ) . "\"," . "\t\t\"" . esc_attr( $nl2br ) . "\"," . "\t\t{$buttons}," . "\t\t\"{$read_more}\"," . "\t\t\"" . (( $calc_estimate ? 'true' : 'false' )) . "\"," . "\t\t\"" . trim( preg_replace( '/\\s+/', ' ', $geo_search ) ) . "\"," . "\t\t{$columnsvar}_geosearch_options," . "\t\t\"" . wp_create_nonce( 'wpda-publication-' . $table_name . '-' . $column_names_value ) . "\"" . "\t);" . "});" . "</script>" . $geomap;
    }
    
    /**
     * Show table header (footer as well)
     *
     * @param array  $columns List of column names.
     * @param string $responsive Yes = responsive mode, No = No responsive mode.
     * @param int    $responsive_cols Number of columns to be displayd in responsive mode.
     * @param string $pub_format Formatting options.
     * @param array $hyperlinks Hyperlinks defined in column settings.
     * @param string $header2 Adds an extra header row if TRUE.
     * @param mixed $json
     * @param mixed $geolocation
     *
     * @return HTML output
     */
    protected function show_header(
        $columns,
        $responsive,
        $responsive_cols,
        $pub_format,
        $hyperlinks,
        $header2,
        $json,
        $geolocation
    )
    {
        $count = 0;
        $html_output = '';
        $html_search = '';
        $column_labels = null;
        $pub_format = json_decode( $pub_format, true );
        
        if ( isset( $pub_format['pub_format']['column_labels'] ) ) {
            $column_labels = $pub_format['pub_format']['column_labels'];
        } else {
            $column_labels = $this->wpda_list_columns->get_table_column_headers();
        }
        
        foreach ( $columns as $column ) {
            $class = '';
            if ( 'yes' === $responsive ) {
                if ( is_numeric( $responsive_cols ) ) {
                    if ( (int) $responsive_cols > 0 ) {
                        
                        if ( $count >= 0 && $count < $responsive_cols ) {
                            $class = 'all';
                        } else {
                            $class = 'none';
                        }
                    
                    }
                }
            }
            
            if ( 'wpda_hyperlink_' !== substr( $column, 0, 15 ) ) {
                $column_label = ( isset( $column_labels[$column] ) ? $column_labels[$column] : $column );
            } else {
                $column_label = $hyperlinks[substr( $column, strrpos( $column, '_' ) + 1 )];
            }
            
            
            if ( 'header' === $header2 || 'both' === $header2 ) {
                $html_search .= "<td class=\"{$class}\" data-column_name_search=\"{$column}\"></td>";
                $html_output .= "<th class=\"{$class}\" data-column_name=\"{$column}\">{$column_label}</th>";
            } else {
                $html_output .= "<th class=\"{$class}\" data-column_name_search=\"{$column}\">{$column_label}</th>";
            }
            
            $count++;
        }
        if ( '' !== $html_search ) {
            $html_search = "<tr>{$html_search}</tr>";
        }
        return "{$html_search}<tr>{$html_output}</tr>";
    }
    
    /**
     * Performs jQuery DataTable query
     *
     * Once a jQuery DataTable is build using {@see WPDA_Data_Tables::show()}, the DataTable is filled according
     * to the search criteria and pagination settings on the Datable. The query is performed through this function.
     * The query result is returned (echo) in JSON format. Table and column names are checked for existence and
     * access to prevent hacking the DataTable code and SQL injection.
     *
     * @since   1.0.0
     *
     * @see WPDA_Data_Tables::show()
     */
    public function get_data()
    {
        $where = '';
        $_filter = [];
        $pub_id = ( isset( $_REQUEST['pubid'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['pubid'] ) ) : '' );
        // input var okay.
        $database = ( isset( $_REQUEST['wpdasrc'] ) ? str_replace( '`', '', sanitize_text_field( wp_unslash( $_REQUEST['wpdasrc'] ) ) ) : '' );
        // input var okay.
        $table_name = ( isset( $_REQUEST['wpdatabs'] ) ? str_replace( '`', '', sanitize_text_field( wp_unslash( $_REQUEST['wpdatabs'] ) ) ) : '' );
        // input var okay.
        $columns = ( isset( $_REQUEST['wpdacols'] ) ? str_replace( '`', '', sanitize_text_field( wp_unslash( $_REQUEST['wpdacols'] ) ) ) : '*' );
        // input var okay.
        $wpnonce = ( isset( $_REQUEST['wpnonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['wpnonce'] ) ) : '' );
        // input var okay.
        
        if ( '' === $pub_id && '' === $table_name ) {
            // input var okay.
            // Database and table name must be set!
            $this->create_empty_response( 'Missing arguments' );
            wp_die();
        } else {
            
            if ( '' !== $pub_id && '0' != $pub_id ) {
                // Get publication data
                $publication = WPDA_Publisher_Model::get_publication( $pub_id );
                
                if ( false === $publication ) {
                    // Publication not found
                    $this->create_empty_response( 'Invalid arguments' );
                    wp_die();
                }
                
                $database = $publication[0]['pub_schema_name'];
                $table_name = $publication[0]['pub_table_name'];
                $columns = $publication[0]['pub_column_names'];
                // Check token
                
                if ( !wp_verify_nonce( $wpnonce, 'wpda-publication-' . $table_name . '-' ) ) {
                    $this->create_empty_response( 'Token expired' );
                    wp_die();
                }
                
                // Get default where
                if ( isset( $publication[0]['pub_default_where'] ) ) {
                    if ( null !== $publication[0]['pub_default_where'] && '' !== trim( $publication[0]['pub_default_where'] ) ) {
                        $where = $publication[0]['pub_default_where'];
                    }
                }
            } else {
                // Check token
                
                if ( !wp_verify_nonce( $wpnonce, 'wpda-publication-' . $table_name . '-' . $columns ) ) {
                    $this->create_empty_response( 'Token expired' );
                    wp_die();
                }
                
                // Do not allow to access other schemas
                
                if ( strpos( $table_name, '.' ) ) {
                    $this->create_empty_response( 'Wrong argument' );
                    wp_die();
                }
                
                // Check access
                $wpda_dictionary_checks = new WPDA_Dictionary_Exist( $database, $table_name );
                
                if ( !$wpda_dictionary_checks->table_exists( true, false ) ) {
                    $this->create_empty_response( 'Not authorized' );
                    wp_die();
                }
            
            }
            
            if ( '' !== $where && 'where' !== strtolower( trim( substr( $where, 0, 5 ) ) ) ) {
                $where = "where {$where}";
            }
            if ( '' !== $where ) {
                $_filter = [
                    'filter_default' => $where,
                ];
            }
            $wpdadb = WPDADB::get_db_connection( $database );
            
            if ( null === $wpdadb ) {
                $this->create_empty_response( 'Invalid connection' );
                wp_die();
                // Remote database not available
            }
            
            // Add field filters from shortcode
            $filter_field_name = str_replace( '`', '', sanitize_text_field( wp_unslash( $_REQUEST['filter_field_name'] ) ) );
            // input var okay.
            $filter_field_value = wp_unslash( $_REQUEST['filter_field_value'] );
            // input var okay.
            
            if ( '' !== $filter_field_name && '' !== $filter_field_value ) {
                $filter_field_name_array = array_map( 'trim', explode( ',', $filter_field_name ) );
                $filter_field_value_array = array_map( 'trim', explode( ',', $filter_field_value ) );
                if ( sizeof( $filter_field_name_array ) === sizeof( $filter_field_value_array ) ) {
                    // Add filter to where clause
                    for ( $i = 0 ;  $i < sizeof( $filter_field_name_array ) ;  $i++ ) {
                        
                        if ( '' === $where ) {
                            $where = $wpdadb->prepare( " where `{$filter_field_name_array[$i]}` like %s ", [ $filter_field_value_array[$i] ] );
                        } else {
                            $where .= $wpdadb->prepare( " and `{$filter_field_name_array[$i]}` like %s ", [ $filter_field_value_array[$i] ] );
                        }
                        
                        $_filter['filter_field_name'] = $filter_field_name;
                        $_filter['filter_field_value'] = $filter_field_value;
                    }
                }
            }
            
            // Get all column names from table (must be comma seperated string)
            $this->wpda_list_columns = WPDA_List_Columns_Cache::get_list_columns( $database, $table_name );
            $table_columns = $this->wpda_list_columns->get_table_columns();
            // Save column:data_type pairs for fast access
            $column_array_ordered = [];
            foreach ( $table_columns as $column ) {
                $column_array_ordered[$column['column_name']] = $column['data_type'];
            }
            // Load table settings
            $table_settings_db = WPDA_Table_Settings_Model::query( $table_name, $database );
            if ( isset( $table_settings_db[0]['wpda_table_settings'] ) ) {
                $table_settings = json_decode( $table_settings_db[0]['wpda_table_settings'] );
            }
            
            if ( '*' === $columns ) {
                // Get all column names from table (must be comma seperated string).
                $column_array = [];
                foreach ( $table_columns as $column ) {
                    $column_array[] = $column['column_name'];
                }
                $columns = implode( ',', $column_array );
            } else {
                // Check if columns exist (prevent sql injection).
                $wpda_dictionary_checks = new WPDA_Dictionary_Exist( $database, $table_name );
                $column_array = explode( ',', $columns );
                $has_dynamic_hyperlinks = false;
                foreach ( $column_array as $column ) {
                    
                    if ( 'wpda_hyperlink_' !== substr( $column, 0, 15 ) ) {
                        
                        if ( !$wpda_dictionary_checks->column_exists( $column ) ) {
                            // Column not found.
                            $this->create_empty_response( 'Invalid column name' );
                            wp_die();
                        }
                    
                    } else {
                        $has_dynamic_hyperlinks = true;
                    }
                
                }
                
                if ( $has_dynamic_hyperlinks ) {
                    // Check for columns needed for substitution and missing in the query
                    $hyperlink_substitution_columns = [];
                    if ( isset( $table_settings->hyperlinks ) ) {
                        foreach ( $table_settings->hyperlinks as $hyperlink ) {
                            if ( isset( $hyperlink->hyperlink_html ) ) {
                                foreach ( $table_columns as $column ) {
                                    if ( stripos( $hyperlink->hyperlink_html, "\$\${$column['column_name']}\$\$" ) !== false ) {
                                        $hyperlink_substitution_columns[$column['column_name']] = true;
                                    }
                                }
                            }
                        }
                    }
                    if ( sizeof( $hyperlink_substitution_columns ) > 0 ) {
                        foreach ( $hyperlink_substitution_columns as $hyperlink_substitution_column => $val ) {
                            if ( stripos( $columns, $hyperlink_substitution_column ) === false ) {
                                $columns .= ",{$hyperlink_substitution_column}";
                            }
                        }
                    }
                }
            
            }
            
            // Save column name without backticks for later use
            $column_array_clean = $column_array;
            // Set pagination values.
            $offset = 0;
            
            if ( isset( $_REQUEST['start'] ) ) {
                $offset = sanitize_text_field( wp_unslash( $_REQUEST['start'] ) );
                // input var okay.
            }
            
            $limit = -1;
            // jQuery DataTables default.
            
            if ( isset( $_REQUEST['length'] ) ) {
                $limit = sanitize_text_field( wp_unslash( $_REQUEST['length'] ) );
                // input var okay.
            }
            
            $publication_mode = 'normal';
            
            if ( -1 == $limit && isset( $_REQUEST['more_start'] ) && isset( $_REQUEST['more_limit'] ) ) {
                $publication_mode = 'more';
                $offset = sanitize_text_field( wp_unslash( $_REQUEST['more_start'] ) );
                // input var okay.
                $limit = sanitize_text_field( wp_unslash( $_REQUEST['more_limit'] ) );
                // input var okay.
            }
            
            // Set order by.
            $orderby = '';
            
            if ( isset( $_REQUEST['order'] ) && is_array( $_REQUEST['order'] ) ) {
                // input var okay.
                $orderby_columns = [];
                $orderby_args = [];
                // Sanitize argument array and write result to temporary sanitizes array for processing:
                foreach ( $_REQUEST['order'] as $order_column ) {
                    // input var okay.
                    $orderby_args[] = [
                        'column' => sanitize_text_field( wp_unslash( $order_column['column'] ) ),
                        'dir'    => sanitize_text_field( wp_unslash( $order_column['dir'] ) ),
                    ];
                }
                foreach ( $orderby_args as $order_column ) {
                    // input var okay.
                    $column_index = $order_column['column'];
                    $column_name = str_replace( '`', '', $column_array[$column_index] );
                    $column_dir = $order_column['dir'];
                    $orderby_columns[] = "`{$column_name}` {$column_dir}";
                }
                $orderby = implode( ',', $orderby_columns );
            } else {
                // If ordering is disabled we still need to use the default order by (user cannot reorder)
                
                if ( isset( $publication[0]['pub_default_orderby'] ) && null !== $publication[0]['pub_default_orderby'] && '' !== trim( $publication[0]['pub_default_orderby'] ) ) {
                    $default_orderby = $publication[0]['pub_default_orderby'];
                    $default_orderby_arr = explode( '|', $default_orderby );
                    $orderby_columns = [];
                    foreach ( $default_orderby_arr as $order ) {
                        $orderby_column_arr = explode( ',', $order );
                        $orderby_column = '`' . str_replace( '`', '', $column_array[$orderby_column_arr[0]] ) . '`';
                        if ( isset( $orderby_column_arr[1] ) ) {
                            $orderby_column .= ' ' . $orderby_column_arr[1];
                        }
                        $orderby_columns[] = $orderby_column;
                    }
                    $orderby = implode( ',', $orderby_columns );
                }
            
            }
            
            // Add search criteria.
            
            if ( isset( $_REQUEST['search']['value'] ) ) {
                $search_value = sanitize_text_field( wp_unslash( $_REQUEST['search']['value'] ) );
                // input var okay.
            } else {
                $search_value = '';
            }
            
            $where_columns = WPDA::construct_where_clause(
                $database,
                $table_name,
                $this->wpda_list_columns->get_table_columns(),
                $search_value
            );
            if ( '' !== $where_columns ) {
                
                if ( '' === $where ) {
                    $where = " where {$where_columns} ";
                } else {
                    $where .= " and {$where_columns} ";
                }
            
            }
            if ( '' !== $where ) {
                $where = WPDA::substitute_environment_vars( $where );
            }
            if ( '' !== $search_value ) {
                $_filter['filter_dyn'] = $search_value;
            }
            foreach ( $_REQUEST as $key => $value ) {
                if ( 'wpda_search_' === substr( $key, 0, 12 ) ) {
                    $_filter['filter_args'][$key] = $value;
                }
            }
            $geo_radius_col = '';
            // Execute query.
            $column_array = explode( ',', $columns );
            $column_array_orig = $column_array;
            $images_array = [];
            $imagesurl_array = [];
            $attachments_array = [];
            $hyperlinks_array = [];
            $hyperlinks_array_col = [];
            $audio_array = [];
            $video_array = [];
            
            if ( isset( $publication[0]['pub_format'] ) && '' !== $publication[0]['pub_format'] && null !== $publication[0]['pub_format'] ) {
                $pub_format = json_decode( $publication[0]['pub_format'], true );
                $column_images = [];
                $column_attachments = [];
                if ( isset( $pub_format['pub_format']['column_images'] ) ) {
                    $column_images = $pub_format['pub_format']['column_images'];
                }
                if ( isset( $pub_format['pub_format']['column_attachments'] ) ) {
                    $column_attachments = $pub_format['pub_format']['column_attachments'];
                }
                $i = 0;
                foreach ( $column_array as $col ) {
                    if ( isset( $column_images[$col] ) ) {
                        array_push( $images_array, $i );
                    }
                    $i++;
                }
                $i = 0;
                foreach ( $column_array as $col ) {
                    if ( isset( $column_attachments[$col] ) ) {
                        array_push( $attachments_array, $i );
                    }
                    $i++;
                }
            }
            
            // Check media columns defined on plugin level and add to arrays
            $i = 0;
            foreach ( $column_array as $col ) {
                
                if ( 'Image' === WPDA_Media_Model::get_column_media( $table_name, $col, $database ) ) {
                    if ( !isset( $images_array[$i] ) ) {
                        array_push( $images_array, $i );
                    }
                } elseif ( 'ImageURL' === WPDA_Media_Model::get_column_media( $table_name, $col, $database ) ) {
                    array_push( $imagesurl_array, $i );
                } elseif ( 'Attachment' === WPDA_Media_Model::get_column_media( $table_name, $col, $database ) ) {
                    if ( !isset( $attachments_array[$i] ) ) {
                        array_push( $attachments_array, $i );
                    }
                } elseif ( 'Hyperlink' === WPDA_Media_Model::get_column_media( $table_name, $col, $database ) ) {
                    
                    if ( !isset( $hyperlinks_array[$i] ) ) {
                        array_push( $hyperlinks_array, $i );
                        array_push( $hyperlinks_array_col, $col );
                    }
                
                } elseif ( 'Audio' === WPDA_Media_Model::get_column_media( $table_name, $col, $database ) ) {
                    array_push( $audio_array, $i );
                } elseif ( 'Video' === WPDA_Media_Model::get_column_media( $table_name, $col, $database ) ) {
                    array_push( $video_array, $i );
                }
                
                $i++;
            }
            // Change dynamic hyperlinks
            $update = [];
            $i = 0;
            $hyperlinks_column_index = [];
            foreach ( $column_array as $col ) {
                
                if ( 'wpda_hyperlink_' === substr( $col, 0, 15 ) ) {
                    $update[$col] = "'x' as {$col}";
                    $hyperlinks_column_index[$i] = substr( $col, 15 );
                } else {
                    $update[$col] = '`' . str_replace( '`', '', $col ) . '`';
                }
                
                $i++;
            }
            $column_array = $update;
            $columns_backticks = implode( ',', $column_array ) . $geo_radius_col;
            $query = "select {$columns_backticks} from `{$wpdadb->dbname}`.`{$table_name}` {$where}";
            if ( '' !== $orderby ) {
                $query .= " order by {$orderby} ";
            }
            if ( -1 != $limit ) {
                $query .= " limit {$limit} offset {$offset}";
            }
            $hyperlinks = [];
            if ( sizeof( $hyperlinks_column_index ) ) {
                if ( isset( $table_settings->hyperlinks ) ) {
                    foreach ( $table_settings->hyperlinks as $hyperlink ) {
                        $hyperlink_label = ( isset( $hyperlink->hyperlink_label ) ? $hyperlink->hyperlink_label : '' );
                        $hyperlink_target = ( isset( $hyperlink->hyperlink_target ) ? $hyperlink->hyperlink_target : false );
                        $hyperlink_html = ( isset( $hyperlink->hyperlink_html ) ? $hyperlink->hyperlink_html : '' );
                        if ( $hyperlink_label !== '' && $hyperlink_html !== '' ) {
                            array_push( $hyperlinks, [
                                'hyperlink_label'  => $hyperlink_label,
                                'hyperlink_target' => $hyperlink_target,
                                'hyperlink_html'   => $hyperlink_html,
                            ] );
                        }
                    }
                }
            }
            $nl2br = ( isset( $_REQUEST['nl2br'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['nl2br'] ) ) : '' );
            // input var okay.
            
            if ( 'on' === $nl2br || 'yes' === $nl2br || 'true' === $nl2br ) {
                $nl2br = 'on';
            } else {
                if ( '' !== $pub_id ) {
                    if ( isset( $publication[0]['pub_table_options_nl2br'] ) ) {
                        $nl2br = $publication[0]['pub_table_options_nl2br'];
                    }
                }
            }
            
            $rows = $wpdadb->get_results( $query, 'ARRAY_N' );
            // WPCS: unprepared SQL OK; db call ok; no-cache ok.
            $rows_final = [];
            foreach ( $rows as $row ) {
                $row_orig = $row;
                if ( 'on' === $nl2br && null !== $nl2br ) {
                    // Replace NL with BR tags
                    for ( $nl = 0 ;  $nl < sizeof( $row ) ;  $nl++ ) {
                        $row[$nl] = nl2br( $row[$nl] );
                    }
                }
                foreach ( $hyperlinks_column_index as $key => $value ) {
                    
                    if ( isset( $hyperlinks[$value] ) ) {
                        $hyperlink_html = ( isset( $hyperlinks[$value]['hyperlink_html'] ) ? $hyperlinks[$value]['hyperlink_html'] : '' );
                        
                        if ( '' !== $hyperlink_html ) {
                            $i = 0;
                            foreach ( $column_array as $column ) {
                                $column_name = str_replace( '`', '', $column );
                                $hyperlink_html = str_replace( "\$\${$column_name}\$\$", $row[$i], $hyperlink_html );
                                $i++;
                            }
                        }
                        
                        $macro = new WPDA_Macro( $hyperlink_html );
                        $hyperlink_html = $macro->exe_macro();
                        
                        if ( '' !== $hyperlink_html ) {
                            
                            if ( false !== strpos( ltrim( $hyperlink_html ), '&lt;' ) ) {
                                $row[$key] = html_entity_decode( $hyperlink_html );
                            } else {
                                $hyperlink_label = ( isset( $hyperlinks[$value]['hyperlink_label'] ) ? $hyperlinks[$value]['hyperlink_label'] : '' );
                                $hyperlink_target = ( isset( $hyperlinks[$value]['hyperlink_target'] ) ? $hyperlinks[$value]['hyperlink_target'] : false );
                                $target = ( true === $hyperlink_target ? "target='_blank'" : '' );
                                $row[$key] = "<a href='" . str_replace( ' ', '+', $hyperlink_html ) . "' {$target}>{$hyperlink_label}</a>";
                            }
                        
                        } else {
                            $row[$key] = '';
                        }
                    
                    } else {
                        $row[$key] = 'ERROR';
                    }
                
                }
                for ( $i = 0 ;  $i < sizeof( $imagesurl_array ) ;  $i++ ) {
                    $row[$imagesurl_array[$i]] = '<img src="' . $row[$imagesurl_array[$i]] . '" width="100%">';
                }
                for ( $i = 0 ;  $i < sizeof( $images_array ) ;  $i++ ) {
                    $image_ids = explode( ',', $row[$images_array[$i]] );
                    $image_src = '';
                    foreach ( $image_ids as $image_id ) {
                        $url = wp_get_attachment_url( esc_attr( $image_id ) );
                        
                        if ( false !== $url ) {
                            $image_src .= ( '' !== $image_src ? '<br/>' : '' );
                            $image_src .= '<img src="' . $url . '" width="100%">';
                        }
                    
                    }
                    $row[$images_array[$i]] = $image_src;
                }
                for ( $i = 0 ;  $i < sizeof( $attachments_array ) ;  $i++ ) {
                    $media_ids = explode( ',', $row[$attachments_array[$i]] );
                    $media_links = '';
                    foreach ( $media_ids as $media_id ) {
                        $url = wp_get_attachment_url( esc_attr( $media_id ) );
                        
                        if ( false !== $url ) {
                            $mime_type = get_post_mime_type( $media_id );
                            
                            if ( false !== $mime_type ) {
                                $title = get_the_title( esc_attr( $media_id ) );
                                $media_links .= WPDA_List_Table::column_media_attachment( $url, $title, $mime_type );
                            }
                        
                        }
                    
                    }
                    $row[$attachments_array[$i]] = $media_links;
                }
                
                if ( isset( $hyperlinks_array ) ) {
                    $hyperlink_definition = ( isset( $table_settings->table_settings->hyperlink_definition ) && 'text' === $table_settings->table_settings->hyperlink_definition ? 'text' : 'json' );
                    for ( $i = 0 ;  $i < sizeof( $hyperlinks_array ) ;  $i++ ) {
                        
                        if ( 'json' === $hyperlink_definition ) {
                            $hyperlink = json_decode( $row[$hyperlinks_array[$i]], true );
                            
                            if ( is_array( $hyperlink ) && isset( $hyperlink['label'] ) && isset( $hyperlink['url'] ) && isset( $hyperlink['target'] ) ) {
                                
                                if ( '' === $hyperlink['url'] ) {
                                    $row[$hyperlinks_array[$i]] = $hyperlink['label'];
                                } else {
                                    $row[$hyperlinks_array[$i]] = "<a href='{$hyperlink['url']}' target='{$hyperlink['target']}'>{$hyperlink['label']}</a>";
                                }
                            
                            } else {
                                $row[$hyperlinks_array[$i]] = '';
                            }
                        
                        } else {
                            
                            if ( null !== $row[$hyperlinks_array[$i]] && '' !== $row[$hyperlinks_array[$i]] ) {
                                $hyperlink_label = $this->wpda_list_columns->get_column_label( $hyperlinks_array_col[$i] );
                                $row[$hyperlinks_array[$i]] = "<a href='{$row[$hyperlinks_array[$i]]}' target='_blank'>{$hyperlink_label}</a>";
                            } else {
                                $row[$hyperlinks_array[$i]] = '';
                            }
                        
                        }
                    
                    }
                }
                
                for ( $i = 0 ;  $i < sizeof( $audio_array ) ;  $i++ ) {
                    $media_ids = explode( ',', $row[$audio_array[$i]] );
                    $media_links = '';
                    foreach ( $media_ids as $media_id ) {
                        
                        if ( 'audio' === substr( get_post_mime_type( $media_id ), 0, 5 ) ) {
                            $url = wp_get_attachment_url( esc_attr( $media_id ) );
                            
                            if ( false !== $url ) {
                                $title = get_the_title( esc_attr( $media_id ) );
                                if ( false !== $url ) {
                                    $media_links .= '<div class="wpda_tooltip" title="' . $title . '">' . do_shortcode( '[audio src="' . $url . '"]' ) . '</div>';
                                }
                            }
                        
                        }
                    
                    }
                    $row[$audio_array[$i]] = $media_links;
                }
                for ( $i = 0 ;  $i < sizeof( $video_array ) ;  $i++ ) {
                    $media_ids = explode( ',', $row[$video_array[$i]] );
                    $media_links = '';
                    foreach ( $media_ids as $media_id ) {
                        
                        if ( 'video' === substr( get_post_mime_type( $media_id ), 0, 5 ) ) {
                            $url = wp_get_attachment_url( esc_attr( $media_id ) );
                            if ( false !== $url ) {
                                if ( false !== $url ) {
                                    $media_links .= do_shortcode( '[video src="' . $url . '"]' );
                                }
                            }
                        }
                    
                    }
                    $row[$video_array[$i]] = $media_links;
                }
                // Format date and time columns
                for ( $i = 0 ;  $i < sizeof( $row ) ;  $i++ ) {
                    if ( '' !== $row[$i] && null !== $row[$i] ) {
                        if ( isset( $column_array_clean[$i] ) ) {
                            if ( isset( $column_array_ordered[$column_array_clean[$i]] ) ) {
                                switch ( $column_array_ordered[$column_array_clean[$i]] ) {
                                    case 'date':
                                        $row[$i] = date_i18n( get_option( 'date_format' ), strtotime( $row[$i] ) );
                                        break;
                                    case 'time':
                                        $row[$i] = date_i18n( get_option( 'time_format' ), strtotime( $row[$i] ) );
                                        break;
                                    case 'datetime':
                                    case 'timestamp':
                                        $row[$i] = date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $row[$i] ) );
                                }
                            }
                        }
                    }
                }
                array_push( $rows_final, $row );
            }
            $row_count_estimate = WPDA::get_row_count_estimate( $database, $table_name, $table_settings );
            $rows_estimate = $row_count_estimate['row_count'];
            $do_real_count = $row_count_estimate['do_real_count'];
            
            if ( 'more' === $publication_mode ) {
                // Use estimate row count
                $count_table = $rows_estimate;
                $count_table_filtered = $rows_estimate;
            } else {
                
                if ( !$do_real_count ) {
                    // Use estimate row count
                    $count_table = $rows_estimate;
                } else {
                    // Count rows in table = real row count
                    $query2 = "select count(*) from `{$wpdadb->dbname}`.`{$table_name}`";
                    $count_rows = $wpdadb->get_results( $query2, 'ARRAY_N' );
                    // WPCS: unprepared SQL OK; db call ok; no-cache ok.
                    $count_table = $count_rows[0][0];
                    // Number of rows in table.
                }
                
                
                if ( isset( $_REQUEST['wpda_use_estimates_only'] ) && 'true' === $_REQUEST['wpda_use_estimates_only'] ) {
                    // Prevent row count, only estimates required
                    $count_table_filtered = $count_table;
                } else {
                    
                    if ( '' !== $where ) {
                        // Count rows in selection (only necessary if a search criteria was entered).
                        $query3 = "select count(*) from `{$wpdadb->dbname}`.`{$table_name}` {$where}";
                        $count_rows_filtered = $wpdadb->get_results( $query3, 'ARRAY_N' );
                        // WPCS: unprepared SQL OK; db call ok; no-cache ok.
                        $count_table_filtered = $count_rows_filtered[0][0];
                        // Number of rows in table.
                    } else {
                        // No search criteria entered: # filtered rows = # table rows.
                        $count_table_filtered = $count_table;
                    }
                
                }
            
            }
            
            // Convert query result to jQuery DataTables object.
            $obj = (object) null;
            $obj->draw = ( isset( $_REQUEST['draw'] ) ? intval( $_REQUEST['draw'] ) : 0 );
            $obj->recordsTotal = $count_table;
            $obj->recordsFiltered = $count_table_filtered;
            $obj->data = $rows_final;
            $obj->error = $wpdadb->last_error;
            if ( 'on' === WPDA::get_option( WPDA::OPTION_PLUGIN_DEBUG ) ) {
                $obj->debug = [
                    'columns_backticks' => $columns_backticks,
                    'query'             => $query,
                    'orderby'           => $orderby,
                    'filter'            => $_filter,
                ];
            }
            // Send header
            header( 'Content-type: application/json' );
            header( 'Cache-Control: no-store, no-cache, must-revalidate, max-age=0' );
            header( 'Cache-Control: post-check=0, pre-check=0', false );
            header( 'Pragma: no-cache' );
            header( 'Expires: 0' );
            // Convert object to json. jQuery DataTables needs json format.
            echo  json_encode( $obj ) ;
        }
        
        wp_die();
    }
    
    private function create_empty_response( $error = '' )
    {
        $obj = (object) null;
        $obj->draw = 0;
        $obj->recordsTotal = 0;
        $obj->recordsFiltered = 0;
        $obj->data = [];
        $obj->error = $error;
        echo  json_encode( $obj ) ;
    }

}