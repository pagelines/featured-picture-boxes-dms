<?php
/*
    Section: Featured Picture Boxes
    Author: Ryan Varley
    Author URI: http://ryanvarley.co.uk
    Description: Oraganise boxes with a picture with overlaying text
    Class Name: FeatPicBoxes
    Workswith: templates, main, header, morefoot
    Cloning: true
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
                                        'boxes'     => array('name' => __( 'Box Posts', 'FeatPicBoxes' ) ),
                                        'post_cat'         => array('name' => __( 'Use Post Category', 'FeatPicBoxes' ) ),
                                        'post'     => array('name' => __( 'Use all Posts', 'FeatPicBoxes' ) ),
                                    ),
                                    'inputlabel'    => __( 'Select Feature Post Source (Optional - Defaults to boxes)', 'FeatPicBoxes' ),
                                ),
                            'FeatPicBoxes_category'        => array(
                                    'default'        => 1,
                                    'type'            => 'select',
                                    'selectvalues'    => $this->get_cats(),
                                    'inputlabel'    => __( 'Select Post Category (Post category source only)', 'FeatPicBoxes' ),
                                ),
                        )
                ),
                
                'FeatPicBoxes_setup' => array(
                    'type'        => 'multi_option', 
                    'title'        => __('Box Setup Options (box source only)', 'FeatPicBoxes'), 
                    'shortexp'    => __('Basic setup options for handling of boxes.', 'FeatPicBoxes'),
                    'selectvalues'    => array(
                        
                        'FeatPicBoxes_set' => array(
                            'default'        => 'default-boxes',
                            'type'             => 'select_taxonomy',
                            'taxonomy_id'    => $this->taxID,                
                            'inputlabel'    => __( 'Box Set To Show', 'FeatPicBoxes'),
                        ), 
                        'FeatPicBoxes_col_number' => array(
                            'type'             => 'count_select',
                            'default'        => '3',
                            'count_number'    => '10', 
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
                    'shortexp'    => __('Colour and theme options for the boxes', 'FeatPicBoxes'),
                    'exp'         => __('First choose your theme and hover style (more coming!). Then choose your aspect ratio 
                        (width is set by the no. boxes per row and the space you put it in) which is height/width. 1 is a square and ', 'FeatPicBoxes'),
                    'selectvalues'    => array(

                        'FeatPicBoxes_theme' => array(
                                'type'            => 'select',
                                'default'        => 'hover',
                                'inputlabel'    => __('Select a theme (view them here)', 'FeatPicBoxes'),
                                'selectvalues' => array(
                                    'standard'         => array('name' => __( 'standard (no hover)', 'FeatPicBoxes') ),
                                    'hover'     => array('name' => __( 'hover', 'FeatPicBoxes') ),
                                    'acordian' => array('name' => __( 'acordian theme', 'FeatPicBoxes') ),
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
                            'inputlabel'     => __( 'Aspect Ratio (this controls the width to height. 1 is square (and the default))', 'FeatPicBoxes'),
                            'shortexp'    => __('Enter the aspect ration the boxes will maintain. This is the width/height.', 'FeatPicBoxes'),
                            'exp'        => __('i.e to have the height double that of the width enter 0.5')
                        ), 
                        'FeatPicBoxes_color' => array(
                            'type'             => 'colorpicker',
                            'inputlabel'     => __( 'Theme color', 'FeatPicBoxes'),
                            'shortexp' => 'By default the section will use your background color. You can overwrite that here',
                        ),
                        'FeatPicBoxes_trans_hover' => array(
                            'type'             => 'check',
                            'inputlabel'     => __( 'make hover overlay transparent', 'FeatPicBoxes'),
                            'shortexp' => 'If checked make the hover overlay transparent instead of a solid color (where applicable)',
                        ),
                        'FeatPicBoxes_border' => array(
                            'type'             => 'check',
                            'inputlabel'     => __( 'Add a border round boxes?', 'FeatPicBoxes'),
                            'shortexp' => 'If checked adds a border around the boxes with the color specified',
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
                    'FeatPicBoxes_defaultImage'     => array(
                        'type'             => 'image_upload',
                        'inputlabel'    => __('Default image', 'FeatPicBoxes'),
                        'title'         => __('Replace the default image for boxes and posts without them (optional)', 'FeatPicBoxes'),
                        'shortexp'        => __('Whenever a post or box is displayed that dosnt have its own image specified this will be used instead.', 'FeatPicBoxes'),
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
                    'name'         => 'FeatPicBoxes',
                    'icon'         => $this->icon, 
                    'clone_id'    => $settings['clone_id'], 
                    'active'    => $settings['active']
                );

            register_metatab($tab_settings, $tab);
    }
    
    function get_cats() {
    
        $cats = get_categories();
        foreach( $cats as $cat )
            $categories[ $cat->cat_ID ] = array( 'name' => $cat->name );
            
        return ( isset( $categories) ) ? $categories : array();
    }
    
    
    /**
    * Section template.
    */
    

    
   function section_template( $clone_id = null ) {
        
        // Options
            $per_row = ( ploption( 'FeatPicBoxes_col_number', $this->oset) ) ? ploption( 'FeatPicBoxes_col_number', $this->oset) : 3; 
            $box_set = ( ploption( 'FeatPicBoxes_set', $this->oset ) ) ? ploption( 'FeatPicBoxes_set', $this->oset ) : null; # TODO: test if working
            $box_limit = ploption( 'FeatPicBoxes_items', $this->oset ); # TODO: test if working

            $post_source = ( ploption( 'FeatPicBoxes_source', $this->oset ) ) ? ploption( 'FeatPicBoxes_source', $this->oset ) : 'boxes';
            $post_category = ( ploption( 'FeatPicBoxes_category', $this->oset ) ) ? ploption( 'FeatPicBoxes_category', $this->oset ) : null;
            
            $class = ( ploption( 'box_class', $this->oset ) ) ? ploption( 'box_class', $this->oset ) : null;
            
            $this->parse_theme();
            
        // Actions    
            // Set up the query for this page
                $orderby = ( ploption('FeatPicBoxes_orderby', $this->oset) ) ? ploption('FeatPicBoxes_orderby', $this->oset) : 'ID';
                $order = ( ploption('FeatPicBoxes_order', $this->oset) ) ? ploption('FeatPicBoxes_order', $this->oset) : 'DESC';
                $params = array( 'orderby'    => $orderby, 'order' => $order);
                
                $params[ 'showposts' ] = ( ploption('FeatPicBoxes_items', $this->oset) ) ? ploption('FeatPicBoxes_items', $this->oset) : $per_row;
                
                if ($post_source == 'post_cat') {
                    $params[ 'post_type' ] = 'project';
                    $params[ 'cat' ] = $post_category;
                }
                else {$params[ 'post_type' ] = $post_source;}
                
                if ($post_source == 'boxes') { $params[ $this->taxID ] = ( ploption( 'FeatPicBoxes_set', $this->oset ) ) ? ploption( 'FeatPicBoxes_set', $this->oset ) : null;}
                
                $params[ 'no_found_rows' ] = 1;

                $q = new WP_Query( $params );
                
                
                if(empty($q->posts)){
                    echo setup_section_notify( $this, 'Add Box Posts To Activate.', admin_url('edit.php?post_type='.$this->ptID), 'Add Posts' );
                    return;
                }
            
            // Grid Args
                $args = array( 'per_row' => $per_row, 'callback' => array(&$this, 'draw_boxes'), 'class' => $class );

            // Call the Grid
                printf('<div class="%s fix">%s</div>',$this->themeclass, grid( $q, $args )); //
        
    }
    
    function draw_boxes($p, $args){ 
        setup_postdata($p);

        $aspectRatio = ( ploption( 'FeatPicBoxes_aspectRatio', $this->oset ) ) ? ploption( 'FeatPicBoxes_aspectRatio', $this->oset ) : 1;
        
        
        $post_source = ( ploption( 'FeatPicBoxes_source', $this->oset ) ) ? ploption( 'FeatPicBoxes_source', $this->oset ) : 'boxes';
        $default_image = ( ploption( 'FeatPicBoxes_defaultImage', $this->oset ) ) ? ploption( 'FeatPicBoxes_defaultImage', $this->oset ) : $this->base_url.'/images/default-image.png';
        
        

        $oset = array('post_id' => $p->ID);
        $box_link = plmeta('the_box_icon_link', $oset);
        $box_icon = plmeta('the_box_icon', $oset);
        
        if ($post_source == 'post_cat' || $post_source == 'post') {
            if ( has_post_thumbnail( $p->ID ) ) {
                $box_icon_array = wp_get_attachment_image_src( get_post_thumbnail_id( $p->ID ), 'single-post-thumbnail'  );
                $box_icon = $box_icon_array[0]; // just the URL
                }
            else { $box_icon = null; }
            }
            
        if ($box_icon == null){ $box_icon = $default_image;}
        
        $class = ( plmeta( 'box_class', $oset ) ) ? plmeta( 'box_class', $oset ) : null;
        
        $title_text = $p->post_title; 

        $shading_layout = sprintf('margin-top: %s%%; height: %s%%;',((1-$this->shading_height)*$aspectRatio)*100,$this->shading_height*100);
        $shading_style = sprintf('%s background-color:%s; ',$shading_layout,$this->hover_color);

        $title = do_shortcode(sprintf('<div class="featpicbox-shading" style="%s"><h1>%s</h1></div>',$shading_style, $title_text));
        
        
        // output
        return sprintf('
        <div class="featpicbox-dummy" style="margin-top:%s%%"></div>
        
        <div id="%s" class="fbox %s %s">
        <a class="featpicbox-link" href="%s">
            <div class="featpicbox-image" style="background-image:url(\'%s\');">
                %s
            </div>
        </a>
        </div>',$aspectRatio*100, 'fbox_'.$p->ID, $class, $this->themeclass, $box_link, $box_icon, $title);
    
    }
    
    function parse_theme(){
        // from options

        $this->color_overide = ( ploption( 'FeatPicBoxes_color', $this->oset ) ) ? ploption( 'FeatPicBoxes_color', $this->oset ) : False; // coded in draw_boxes

        $this->theme_color = ( ploption( 'FeatPicBoxes_color', $this->oset ) ) ? ploption( 'FeatPicBoxes_color', $this->oset ) : '#FF0000'; // TODO get from bgcolor option

        $this->theme = ( ploption( 'FeatPicBoxes_theme', $this->oset ) ) ? ploption( 'FeatPicBoxes_theme', $this->oset ) : 'hover'; // TODO write code to parse theme
        $this->border = ( ploption( 'FeatPicBoxes_border', $this->oset ) ) ? ploption( 'FeatPicBoxes_border', $this->oset ) : True; // TODO wrtie code to parse border
        $trans_hover = ( ploption( 'FeatPicBoxes_trans_hover', $this->oset ) ) ? ploption( 'FeatPicBoxes_trans_hover', $this->oset ) : False;
        $shading_style = ( ploption( 'FeatPicBoxes_shadingHeight', $this->oset ) ) ? ploption( 'FeatPicBoxes_shadingHeight', $this->oset ) : 'part';
        // set some variables here, call functions to set others.
        
        // theme
        $this->themeclass = '';
                
        if ($this->theme == 'hover'){
            $this->themeclass += 'fpb-text-on-hover ';
        }
        elseif ($this->theme == 'standard') {
            $this->themeclass = 'fpb-standard ';
        }
        
        $this->shading_height = ($shading_style == 'part') ? 0.4 : 1;

        $this->hover_color = ($trans_hover) ? hex2rgba($this->theme_color,0.6) : $this->theme_color;
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
