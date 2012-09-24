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
	var $ptID = 'boxes';

	
	function post_meta_setup(){
		
			$type_meta_array = array(
				
				'FeatPicBoxes_setup' => array(
					'type'		=> 'multi_option', 
					'title'		=> __('Individual Box Options', 'pagelines'), 
					'shortexp'	=> __('Basic setup options for handling of this box', 'pagelines'),
					'selectvalues'	=> array(
						
						'FeatPicBoxes_box_icon' 		=> array(
								'type' 		=> 'image_upload',					
								'inputlabel' 	=> __( 'Box Image', 'pagelines'),
							), 
						'FeatPicBoxes_box_icon_link'		=> array(
								'type' => 'text',					
								'inputlabel' => __( 'Box Link (Optional)', 'pagelines'),
							), 
						'FeatPicBoxes_box_icon_target'		=> array(
								'type' => 'check',					
								'inputlabel' => __( 'Open link in New Window?', 'pagelines'),
							),
						'FeatPicBoxes_class' => array(
							'default'		=> '',
							'type' 			=> 'text',
							'size'			=> 'small',
							'inputlabel' 	=> __( 'Boxes Custom Class', 'pagelines'),
						),
					),
				),
			
			);

			$post_types = array($this->id); 
			
			$type_metapanel_settings = array(
					'id' 		=> 'boxes-metapanel',
					'name' 		=> THEMENAME.' Box Options',
					'posttype' 	=> $post_types,
				);
			
			global $boxes_meta_panel;
			
			$boxes_meta_panel =  new PageLinesMetaPanel( $type_metapanel_settings );
			
			$type_metatab_settings = array(
				'id' 		=> 'boxes-type-metatab',
				'name' 		=> 'Box Setup Options',
				'icon' 		=> $this->icon,
			);

			$boxes_meta_panel->register_tab( $type_metatab_settings, $type_meta_array );
		
	}

	function section_optionator( $settings ){
		
		$settings = wp_parse_args($settings, $this->optionator_default);
		
			$tab = array(
				'box_setup' => array(
					'type'		=> 'multi_option', 
					'title'		=> __('Box Setup Options', 'pagelines'), 
					'shortexp'	=> __('Basic setup options for handling of boxes.', 'pagelines'),
					'selectvalues'	=> array(
						
						'box_set' => array(
							'default'		=> 'default-boxes',
							'type' 			=> 'select_taxonomy',
							'taxonomy_id'	=> $this->taxID,				
							'inputlabel'	=> __( 'Box Set To Show', 'pagelines'),
						), 
						'box_col_number' => array(
							'type' 			=> 'count_select',
							'default'		=> '3',
							'count_number'	=> '5', 
							'count_start'	=> '1',
							'inputlabel' 		=> __( "Boxes Per Row", 'pagelines'),
						), 
						'box_items' => array(
							'default'		=> '6',
							'type' 			=> 'text_small',
							'size'			=> 'small',
							'inputlabel' 	=> __( 'Maximum Boxes To Show', 'pagelines'),
						),
					),
				),
					
					'box_ordering' => array(
						'type'		=> 'multi_option', 
						'title'		=> __('Box Ordering Options', 'pagelines'), 
						'shortexp'	=> __('Optionally control the ordering of the boxes', 'pagelines'),
						'exp'		=> __('The easiest way to order boxes is using a post type order plugin for WordPress. However, if you would like to do it algorithmically, we have provided these options for you.', 'pagelines'),
						'selectvalues'	=> array(
							
							'box_orderby' => array(
								'type'			=> 'select',
								'default'		=> 'ID',
								'inputlabel'	=> 'Order Boxes By (If Not With Post Type Order Plugin)',
								'selectvalues' => array(
									'ID' 		=> array('name' => __( 'Post ID (default)', 'pagelines') ),
									'title' 	=> array('name' => __( 'Title', 'pagelines') ),
									'date' 		=> array('name' => __( 'Date', 'pagelines') ),
									'modified' 	=> array('name' => __( 'Last Modified', 'pagelines') ),
									'rand' 		=> array('name' => __( 'Random', 'pagelines') ),							
								)
							),
							'box_order' => array(
									'default' => 'DESC',
									'type' => 'select',
									'selectvalues' => array(
										'DESC' 		=> array('name' => __( 'Descending', 'pagelines') ),
										'ASC' 		=> array('name' => __( 'Ascending', 'pagelines') ),
									),
									'inputlabel'=> __( 'Select sort order', 'pagelines'),
							),
						),
					),
					
					'box_class' => array(
						'default'		=> '',
						'type' 			=> 'text',
						'size'			=> 'small',
						'inputlabel' 	=> __( 'Add custom css class to these boxes', 'pagelines'),
						'title' 		=> __( 'Custom CSS class', 'pagelines'),
						'shortexp' 		=> __( 'Add a custom CSS class to this set of boxes.', 'pagelines'),
					)
			);
		
			$tab_settings = array(
					'id' 		=> 'FeatPicBoxes_meta',
					'name' 		=> 'FeatPicBoxes',
					'icon' 		=> $this->icon, 
					'clone_id'	=> $settings['clone_id'], 
					'active'	=> $settings['active']
				);

			register_metatab($tab_settings, $tab);
	}

	/**
	* Section template.
	*/
   function section_template( $clone_id = null ) {    
		
		// Options
			$per_row = ( ploption( 'box_col_number', $this->oset) ) ? ploption( 'box_col_number', $this->oset) : 3; 
			$box_set = ( ploption( 'box_set', $this->oset ) ) ? ploption( 'box_set', $this->oset ) : null;
			$box_limit = ploption( 'box_items', $this->oset );
			$this->thumb_type = ( ploption( 'box_thumb_type', $this->oset) ) ? ploption( 'box_thumb_type', $this->oset) : 'inline_thumbs';	
			$this->thumb_size = ploption('box_thumb_size', $this->oset);
			$this->framed = ploption('box_thumb_frame', $this->oset);
			
			
			$class = ( ploption( 'box_class', $this->oset ) ) ? ploption( 'box_class', $this->oset ) : null;
			
		// Actions	
			// Set up the query for this page
				$orderby = ( ploption('box_orderby', $this->oset) ) ? ploption('box_orderby', $this->oset) : 'ID';
				$order = ( ploption('box_order', $this->oset) ) ? ploption('box_order', $this->oset) : 'DESC';
				$params = array( 'orderby'	=> $orderby, 'order' => $order, 'post_type'	=> $this->ptID );
				$params[ 'showposts' ] = ( ploption('box_items', $this->oset) ) ? ploption('box_items', $this->oset) : $per_row;
				$params[ $this->taxID ] = ( ploption( 'box_set', $this->oset ) ) ? ploption( 'box_set', $this->oset ) : null;
				$params[ 'no_found_rows' ] = 1;

				$q = new WP_Query( $params );
				
				if(empty($q->posts)){
					echo setup_section_notify( $this, 'Add Box Posts To Activate.', admin_url('edit.php?post_type='.$this->ptID), 'Add Posts' );
					return;
				}
			
			// Grid Args
				$args = array( 'per_row' => $per_row, 'callback' => array(&$this, 'draw_boxes'), 'class' => $class );

			// Call the Grid
				printf('<div class="fboxes fix">%s</div>', grid( $q, $args ));
		
	}

	function draw_boxes($p, $args){ 

		setup_postdata($p); 
		
		$oset = array('post_id' => $p->ID);
	 	$box_link = plmeta('the_box_icon_link', $oset);
		$box_icon = plmeta('the_box_icon', $oset);
		
		$class = ( plmeta( 'box_class', $oset ) ) ? plmeta( 'box_class', $oset ) : null;
		
		$title_text = ($box_link) ? sprintf('<a href="%s">%s</a>', $box_link, $p->post_title ) : $p->post_title; 
	
		$title = do_shortcode(sprintf('<div class="fboxtitle"><h3>%s</h3></div>', $title_text));
				
		return sprintf('
		<div class="featpicbox-dummy"></div>
		<div id="%s" class="fbox %s">
			<div class="featpicbox-image" style="background-image:url(\'%s\');">
				<div class="featpicbox-shading">%s</div>
			</div>
		</div>', 'fbox_'.$p->ID, $class, $box_icon, $title_text);
	
	}

	function _get_box_image( $bpost, $box_icon, $box_link = false, $box_thumb_size = false, $box_target){
			global $pagelines_ID;
			
			$frame = ($this->framed) ? 'pl-imageframe' : '';
			
			if($this->thumb_type == 'inline_thumbs'){
				$max_width = ($box_thumb_size) ? $box_thumb_size : 65;
				$image_style = 'max-width: 100%';
				$wrapper_style = sprintf('width: 22%%; max-width:%dpx', $max_width);
				$wrapper_class = sprintf('fboxgraphic img %s', $frame);
			} else {
				$max_width = ($box_thumb_size) ? $box_thumb_size.'px' : '100%';
				$image_style = 'max-width: 100%';
				$wrapper_style = sprintf('max-width:%s', $max_width);
				$wrapper_class = sprintf('fboxgraphic %s', $frame);
			}
			
			// Make the image's tag with url
			$image_tag = sprintf('<img src="%s" alt="%s" style="%s" />', $box_icon, esc_html($bpost->post_title), $image_style);
			
			// If link for box is set, add it
			$image_output = ( $box_link ) ? sprintf('<a href="%s" title="%s" %s>%s</a>', $box_link, esc_html($bpost->post_title), $box_target, $image_tag ) : $image_tag;
			
			$wrapper = sprintf('<div class="%s" style="%s">%s</div>', $wrapper_class, $wrapper_style, $image_output );
			
			// Filter output
			return apply_filters('pl_box_image', $wrapper, $bpost->ID);
	}
	
	function column_display($column){
		global $post;

		switch ($column){
			case 'bdescription':
				the_excerpt();
				break;
			case 'bmedia':
				if(get_post_meta($post->ID, 'the_box_icon', true ))
					echo '<img src="'.get_post_meta($post->ID, 'the_box_icon', true ).'" style="max-width: 80px; margin: 10px; border: 1px solid #ccc; padding: 5px; background: #fff" />';	
	
				break;
			case $this->taxID:
				echo get_the_term_list($post->ID, 'box-sets', '', ', ','');
				break;
		}
	}
}
