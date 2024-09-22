<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function dtk_clean_selector_value($values){
  $interest=["url"];
  if(is_array($values)){
    foreach ($values as $key => $value){
      if (in_array($key,$interest)) return $value;
    }
  }
  return $values;
}
function dtk_parse_selector($selector,$wrapper,$value){
  $clean_value=dtk_clean_selector_value($value);
  $selector = str_replace("{{WRAPPER}}",$wrapper,$selector);
  $selector = str_replace(["{{VALUE}}","{{URL}}","{{UNIT}}"],$clean_value,$selector);
  return $selector;
}
function dtk_recursive_unset(&$array, $unwanted_key) {
    unset($array[$unwanted_key]);
    foreach ($array as &$value) {
        if (is_array($value)) {
            recursive_unset($value, $unwanted_key);
        }
    }
}
// dynamic style for elements
function dtk_set_dynamic_style( \Elementor\Element_Base $element ) {
  global $dtk_render_loop;
  if(!$dtk_render_loop)
    return; // only act inside loop
  list($PostID,$LoopID)=explode(",",$dtk_render_loop);
  $ElementID = $element->get_ID();
  $dynamic_settings = $element->get_settings( '__dynamic__' );
  $all_controls = $element->get_controls();

  $all_controls = isset($all_controls) ? $all_controls : []; $dynamic_settings = isset($dynamic_settings) ? $dynamic_settings : [];
  $controls = array_intersect_key( $all_controls, $dynamic_settings );
  dtk_recursive_unset($dynamic_settings, 'link');//we don't need the link options
  $settings = $element->parse_dynamic_settings( $dynamic_settings, $controls); // @ <- dirty fix for that fugly controls-stack.php  Illegal string offset 'url' error

  $ECS_css="";
  $element_wrapper="#post-{$PostID} .elementor-{$LoopID} .elementor-element.elementor-element-{$ElementID}";
  
  foreach($controls as $key => $control){
    if(isset($control["selectors"])){
        foreach($control["selectors"] as $selector => $rules){
          if(isset($settings[$key])) $ECS_css.= dtk_parse_selector($selector."{".$rules."}",$element_wrapper,$settings[$key]);        
        }

    }
  }
  

  echo $ECS_css ? "<style>".$ECS_css."</style>" :"";
  /* end custom css*/
}

add_action( 'elementor/frontend/section/before_render', 'dtk_set_dynamic_style' );
add_action( 'elementor/frontend/column/before_render', 'dtk_set_dynamic_style' );

add_action( 'elementor/frontend/widget/before_render', 'dtk_set_dynamic_style' );

//keep track of index

add_action( 'elementor/frontend/widget/before_render', function ( \Elementor\Element_Base $element ) {
  global $dtk_index;
	if ( 'posts' === $element->get_name() ||  'archive-posts' === $element->get_name()) {
		$dtk_index=0;
	}
} );