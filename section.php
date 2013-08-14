<?php
/*
    Section: Featured Picture Boxes
    Author: Ryan Varley
    Author URI: http://acometappears.com
    Version: 1.1
    Description: Displays posts and boxes with an emphasis on the image. This can be a picture with the text on a transparent or solid overlay or the text appearing on hover.
    Class Name: FeatPicBoxes
    Cloning: true
    External:
    Demo: http://fpbdemo.acometappears.com/
    Workswith: templates, main, header, morefoot, footer, sidebar1, sidebar2, sidebar_wrap
    V3: true
*/

class FeatPicBoxes extends PageLinesSection {

    var $taxID = 'box-sets';
    
    function section_optionator( $settings ){
        
        $settings = wp_parse_args($settings, $this->optionator_default);
        
            $tab = array(
                
               'FeatPicBoxes_source' => array(
                        'type'        => 'multi_option', 
                        'title'        => __('Featured Boxes Source and Order', 'FeatPicBoxes'), 
                        'shortexp'    => __('Advanced options for box sources and their order.', 'FeatPicBoxes'),
                        'selectvalues'    => array(
                            'FeatPicBoxes_source'    => array(
                                    'default'    => 'boxes',
                                    'type'        => 'select',
                                    'selectvalues'         => array(
                                        'boxes'    => array('name' => __( 'Box Posts', 'FeatPicBoxes' ) ),
                                        'post'     => array('name' => __( 'Posts', 'FeatPicBoxes' ) ),
                                        'page'     => array('name' => __( 'Pages', 'FeatPicBoxes' ) ),
                                    ),
                                    'inputlabel'    => __( 'Select Feature Post Source (Optional - Defaults to boxes)', 'FeatPicBoxes' ),
                                ),
                            'FeatPicBoxes_post_category'        => array(
                                    'default'        => 1,
                                    'type'            => 'select',
                                    'selectvalues'    => $this->get_cats('post'),
                                    'inputlabel'    => __( 'Select Post Category (Post source only - optional)', 'FeatPicBoxes' ),
                                ),
                            'FeatPicBoxes_page_category'        => array(
                                'default'        => 1,
                                'type'            => 'select',
                                'selectvalues'    => $this->get_page_parents('page'),
                                'inputlabel'    => __( 'Select Page Parent (Page source only - optional)', 'FeatPicBoxes' ),
                            ),
                        
                            'FeatPicBoxes_set' => array(
                                'default'        => 'default-boxes',
                                'type'             => 'select_taxonomy',
                                'taxonomy_id'    => $this->taxID,                
                                'inputlabel'    => __( 'Box Set To Show (Box source only - optional)', 'FeatPicBoxes'),
                            ), 
                            'FeatPicBoxes_col_number' => array(
                                'type'             => 'count_select',
                                'default'        => '3',
                                'count_number'    => '6', 
                                'count_start'    => '1',
                                'inputlabel'         => __( "Boxes Per Row", 'FeatPicBoxes'),
                            ), 
                            'FeatPicBoxes_items' => array(
                                'default'        => '6',
                                'type'             => 'text_small',
                                'size'            => 'small',
                                'inputlabel'     => __( 'Maximum Boxes To Show', 'FeatPicBoxes'),
                        ),
                    ),
                ),
                
                'FeatPicBoxes_themeOptions' => array(
                    'type'        => 'multi_option', 
                    'title'        => __('Box Theme Options', 'FeatPicBoxes'), 
                    'exp'         => __('First choose your theme and hover style (more coming!). Then choose your aspect ratio which is height divided by width
                        (the width is set by the number of boxes per row and the space you put the section in). 1 is a square, 0.5 will twice as wide as it is high (short and wide) and 2 will be twice as high as it is wide (tall and thin). You can type any number.', 'FeatPicBoxes'),
                    'shortexp'    => __('Color and theme options for the boxes', 'FeatPicBoxes'),
                    'selectvalues'    => array(

                        'FeatPicBoxes_theme' => array(
                                'type'            => 'select',
                                'default'        => 'hover',
                                'inputlabel'    => __('Select a theme (<a href="http://demo.ryanvarley.co.uk/featured-picture-boxes" target="_blank">view them here</a>) standard is the default', 'FeatPicBoxes'),
                                'selectvalues' => array(
                                    'standard'         => array('name' => __( 'standard (no hover)', 'FeatPicBoxes') ),
                                    'hover'     => array('name' => __( 'hover', 'FeatPicBoxes') ),
                                    //'acordian' => array('name' => __( 'acordian theme', 'FeatPicBoxes') ),
                                    ),
                        ),
                        'FeatPicBoxes_shadingHeight' => array(
                                'type'            => 'select',
                                'default'        => 'part',
                                'inputlabel'    => __('Hover / Shading Height', 'FeatPicBoxes'),
                                'selectvalues' => array(
                                    'full'         => array('name' => __( 'Full Cover', 'FeatPicBoxes') ),
                                    'part'     => array('name' => __( 'Partial Cover', 'FeatPicBoxes') ),
                                    ),
                        ),
                        'FeatPicBoxes_aspectRatio' => array(
                            'default'        => '1',
                            'type'             => 'text_small',
                            'size'            => 'small',
                            'inputlabel'     => __( 'Aspect Ratio - this controls the width to height (see more info). The default is 1 (square)', 'FeatPicBoxes'),
                            'shortexp'    => __('Enter the aspect ration the boxes will maintain. This is the width/height.', 'FeatPicBoxes'),
                            'exp'        => __('i.e to have the height double that of the width enter 0.5')
                        ), 

                                
                        'FeatPicBoxes_color_bg' => array(
                            'type'             => 'colorpicker',
                            'inputlabel'     => __( 'Hover / shading color (optional)', 'FeatPicBoxes'),
                            'shortexp' => 'By default the section will use your background color. You can overwrite that here',
                            ),

                        'FeatPicBoxes_color_text' => array(
                            'type'             => 'colorpicker',
                            'inputlabel'     => __( 'text color (optional)', 'FeatPicBoxes'),
                            'shortexp' => 'By default the section will use your normal text color. You can overwrite that here',
                            ),

                        'FeatPicBoxes_trans_hover' => array(
                            'type'             => 'check',
                            'inputlabel'     => __( 'Remove hover / shading transparency?', 'FeatPicBoxes'),
                            'shortexp' => 'If checked it will remove the hover overlay transparecy leaving a solid color',
                        ),

                        'FeatPicBoxes_shadow' => array(
                            'type'             => 'check',
                            'inputlabel'     => __( 'Add a drop shadow to boxes?', 'FeatPicBoxes'),
                            'shortexp' => 'If checked add the drop shadow to the boxes',
                        ),
                    ),
                ),
                    
                    'FeatPicBoxes_ordering' => array(
                        'type'        => 'multi_option', 
                        'title'        => __('Box Ordering Options', 'FeatPicBoxes'), 
                        'shortexp'    => __('Optionally control the ordering of the boxes', 'FeatPicBoxes'),
                        'exp'        => __('The easiest way to order boxes is using a post type order plugin for WordPress. However, if you would like to do it algorithmically, we have provided these options for you.', 'FeatPicBoxes'),
                        'selectvalues'    => array(
                            
                            'FeatPicBoxes_orderby' => array(
                                'type'            => 'select',
                                'default'        => 'ID',
                                'inputlabel'    => __('Order Boxes By (If Not With Post Type Order Plugin)', 'FeatPicBoxes'),
                                'selectvalues' => array(
                                    'ID'         => array('name' => __( 'Post ID (default)', 'FeatPicBoxes') ),
                                    'title'     => array('name' => __( 'Title', 'FeatPicBoxes') ),
                                    'date'         => array('name' => __( 'Date', 'FeatPicBoxes') ),
                                    'modified'     => array('name' => __( 'Last Modified', 'FeatPicBoxes') ),
                                    'rand'         => array('name' => __( 'Random', 'FeatPicBoxes') ),                            
                                )
                            ),
                            'FeatPicBoxes_order' => array(
                                    'default' => 'DESC',
                                    'type' => 'select',
                                    'selectvalues' => array(
                                        'DESC'         => array('name' => __( 'Descending', 'FeatPicBoxes') ),
                                        'ASC'         => array('name' => __( 'Ascending', 'FeatPicBoxes') ),
                                    ),
                                    'inputlabel'=> __( 'Select sort order', 'FeatPicBoxes'),
                            ),
                        ),
                    ),
                    'FeatPicBoxes_default_image'     => array(
                        'type'             => 'image_upload',
                        'inputlabel'    => __('Default image', 'FeatPicBoxes'),
                        'title'         => __('Replace the default image for boxes and posts without an image (optional)', 'FeatPicBoxes'),
                        'shortexp'        => __('Whenever a post or box is displayed that doesn\'t have its own image specified this will be used instead.', 'FeatPicBoxes'),
                ),
                    'FeatPicBoxes_class' => array(
                        'default'        => '',
                        'type'             => 'text',
                        'size'            => 'small',
                        'inputlabel'     => __( 'Add custom css class to these boxes', 'FeatPicBoxes'),
                        'title'         => __( 'Custom CSS class', 'FeatPicBoxes'),
                        'shortexp'         => __( 'Add a custom CSS class to this set of boxes.', 'FeatPicBoxes'),
                    ),
                    
            );
        
            $tab_settings = array(
                    'id'         => 'FeatPicBoxes_meta',
                    'name'         => 'Featured Picture Boxes',
                    'icon'         => $this->icon, 
                    'clone_id'    => $settings['clone_id'], 
                    'active'    => $settings['active']
                );

            register_metatab($tab_settings, $tab);
    }
    
    function get_cats($postType) {

        $args = array(
            'type' => $postType,
        );

        $cats = get_categories($args);
        foreach( $cats as $cat )
            $categories[ $cat->cat_ID ] = array( 'name' => $cat->name );
            
        return ( isset( $categories) ) ? $categories : array();
    }

    function get_page_parents($post_type = 'page') {

        $args = array(
            'parent' => 0,
            'post_type' => $post_type,
            'post_status' => 'publish',
        );

        $cats = get_pages($args);

        foreach( $cats as $cat )
            $categories[ $cat->ID ] = array( 'name' => $cat->post_title );

        return ( isset( $categories) ) ? $categories : array();
    }
    
    
    /**
    * Section template.
    */
    

    
   function section_template() {
        
        // Options
            $per_row = ( $this->opt( 'FeatPicBoxes_col_number', $this->oset) ) ? $this->opt( 'FeatPicBoxes_col_number', $this->oset) : 3; 
            $box_set = ( $this->opt( 'FeatPicBoxes_set', $this->oset ) ) ? $this->opt( 'FeatPicBoxes_set', $this->oset ) : null; # TODO: test if working
            $box_limit = $this->opt( 'FeatPicBoxes_items', $this->oset ); # TODO: test if working
            $this->default_image = ( $this->opt( 'FeatPicBoxes_default_image', $this->oset ) ) ? $this->opt( 'FeatPicBoxes_default_image', $this->oset ) : $this->base_url.'/images/default-image.png';
            
            $aspectRatio = $this->aspectRatio = ( $this->opt( 'FeatPicBoxes_aspectRatio', $this->oset ) ) ? $this->opt( 'FeatPicBoxes_aspectRatio', $this->oset ) : 1;
            $post_source = $this->postSource = ( $this->opt( 'FeatPicBoxes_source', $this->oset ) ) ? $this->opt( 'FeatPicBoxes_source', $this->oset ) : 'boxes';
            $post_category = ( $this->opt( 'FeatPicBoxes_post_category', $this->oset ) ) ? $this->opt( 'FeatPicBoxes_post_category', $this->oset ) : null;
            $page_parent = ( $this->opt( 'FeatPicBoxes_page_category', $this->oset ) ) ? $this->opt( 'FeatPicBoxes_page_category', $this->oset ) : null;
            
            $class = ( $this->opt( 'box_class', $this->oset ) ) ? $this->opt( 'box_class', $this->oset ) : null;
            
            $this->parse_theme();
            
            
        // Actions    
            // Set up the query for this page
                $orderby = ( $this->opt('FeatPicBoxes_orderby', $this->oset) ) ? $this->opt('FeatPicBoxes_orderby', $this->oset) : 'ID';
                $order = ( $this->opt('FeatPicBoxes_order', $this->oset) ) ? $this->opt('FeatPicBoxes_order', $this->oset) : 'DESC';
                $params = array( 'orderby'    => $orderby, 'order' => $order);
                
                $params[ 'showposts' ] = ( $this->opt('FeatPicBoxes_items', $this->oset) ) ? $this->opt('FeatPicBoxes_items', $this->oset) : $per_row;

                $params[ 'post_type' ] = $post_source;
                if ($post_source == 'post') { $params[ 'cat' ] = $post_category; }
                elseif ($post_source == 'page') {
                    $params[ 'post_parent' ] = $page_parent;
                }
                elseif ($post_source == 'boxes') { $params[ $this->taxID ] = ( $this->opt( 'FeatPicBoxes_set', $this->oset ) ) ? $this->opt( 'FeatPicBoxes_set', $this->oset ) : null;}
                
                $params[ 'no_found_rows' ] = 1;

                $q = new WP_Query( $params );
                
                
                if(empty($q->posts)){
                    echo setup_section_notify( $this, 'Add content (Box Posts or normal posts in a category) To Activate.');
                    return;
                }
            
            // Grid Args
                $args = array( 'per_row' => $per_row, 'callback' => array(&$this, 'draw_boxes'), 'class' => $class );

            // Call the Grid
                printf('<div class="%s fix">%s</div>',$this->themeclass, grid( $q, $args )); // where the theme classes are parsed
        
    }
    
    function draw_boxes($p, $args){ 
        setup_postdata($p);

        $aspectRatio = $this->aspectRatio;
        $post_source = $this->postSource;

        $oset = array('post_id' => $p->ID);
        
        if ($post_source == 'page' || $post_source == 'post') {
            if ( has_post_thumbnail( $p->ID ) ) {
                $box_icon_array = wp_get_attachment_image_src( get_post_thumbnail_id( $p->ID ), 'single-post-thumbnail'  );
                $box_icon = $box_icon_array[0]; // just the URL
                }
            else { $box_icon = null; }
            
            $box_link = $p->guid;
            }
        elseif ($post_source == 'boxes') {
            
            $box_link = plmeta('the_box_icon_link', $oset);
            $box_icon = plmeta('the_box_icon', $oset);
        }
            
        if ($box_icon == null){ $box_icon = $this->default_image;}
        
        $class = ( plmeta( 'box_class', $oset ) ) ? plmeta( 'box_class', $oset ) : null; // userset classes within a box
        
        $title_overide = get_post_meta($p->ID, 'fpb_title', true);
        if ($title_overide){
            $title_text = $title_overide;
        }
        else{
            $title_text = $p->post_title;
        }

        $shading_layout = sprintf('margin-top: %s%%; height: %s%%;',((1-$this->shading_height)*$aspectRatio)*100,$this->shading_height*100);
        $shading_style = sprintf('%s background-color:%s; ',$shading_layout,$this->hover_color);

        $title = do_shortcode(sprintf('<div class="featpicbox-shading" style="%s"><h3 style="%s">%s</h3></div>',$shading_style,$this->text_color, $title_text)); # text coor was added here but cant overule H1
        
        
        // output
        return sprintf('
        <div class="featpicbox-dummy" style="margin-top:%s%%"></div>
        
        <div id="%s" class="fpbox %s">
        <a class="featpicbox-link" href="%s">
            <div class="featpicbox-image" style="background-image:url(\'%s\');">
                %s
            </div>
        </a>
        </div>',$aspectRatio*100, $class, 'fpbox_'.$p->ID, $box_link, $box_icon, $title);
    }
    
    function parse_theme(){
        // from options

        $this->color_overide = ( $this->opt( 'FeatPicBoxes_color_bg', $this->oset ) ) ? $this->opt( 'FeatPicBoxes_color_bg', $this->oset ) : False; // coded in draw_boxes
        $this->text_color = ( $this->opt( 'FeatPicBoxes_color_text', $this->oset ) ) ? 'color:'.$this->opt( 'FeatPicBoxes_color_text', $this->oset ).'; ' : '';
        
        $this->theme = ( $this->opt( 'FeatPicBoxes_theme', $this->oset ) ) ? $this->opt( 'FeatPicBoxes_theme', $this->oset ) : 'hover'; 
        //$this->border = ( $this->opt( 'FeatPicBoxes_border', $this->oset ) ) ? $this->opt( 'FeatPicBoxes_border', $this->oset ) : False;
        $this->shadow = ( $this->opt( 'FeatPicBoxes_shadow', $this->oset ) ) ? $this->opt( 'FeatPicBoxes_shadow', $this->oset ) : False;
        $trans_hover = $this->opt( 'FeatPicBoxes_trans_hover', $this->oset) ? False : True;
        $shading_style = ( $this->opt( 'FeatPicBoxes_shadingHeight', $this->oset ) ) ? $this->opt( 'FeatPicBoxes_shadingHeight', $this->oset ) : 'part';
        $custom_class = ( $this->opt( 'FeatPicBoxes_class', $this->oset ) ) ? $this->opt( 'FeatPicBoxes_class', $this->oset ) : '';
        // set some variables here, call functions to set others.
        
        // theme
        $this->themeclass = $custom_class.' ';
                
        if ($this->theme == 'hover'){
            $this->themeclass .= 'fpb-text-on-hover ';
        }
        elseif ($this->theme == 'standard') {
            $this->themeclass .= 'fpb-standard ';
        }
        
        if ($this->shadow == True){
            $this->themeclass .= 'fpb-shadow ';
        }
        
        $this->shading_height = ($shading_style == 'part') ? 0.4 : 1;

        $this->hover_color = ($trans_hover) ? hex2rgba($this->color_overide,0.6) : $this->color_overide;
    }
    
}

function hex2rgba($hex,$alpha=0.6) {
   $hex = str_replace("#", "", $hex);

   if(strlen($hex) == 3) {
      $r = hexdec(substr($hex,0,1).substr($hex,0,1));
      $g = hexdec(substr($hex,1,1).substr($hex,1,1));
      $b = hexdec(substr($hex,2,1).substr($hex,2,1));
   } else {
      $r = hexdec(substr($hex,0,2));
      $g = hexdec(substr($hex,2,2));
      $b = hexdec(substr($hex,4,2));
   }
   
   $rgba = sprintf('rgba(%s,%s,%s,%s)',$r,$g,$b,$alpha);
   //return implode(",", $rgb); // returns the rgb values separated by commas
   return $rgba; // returns an array with the rgb values
}
