<?php
//var_dump($addon);
$current_value = isset( $_POST['addon-' . sanitize_title( $addon['field-name'] ) ] ) ? wc_clean( $_POST[ 'addon-' . sanitize_title( $addon['field-name'] ) ] ) : '';


// A redéfinir 
$attr = (object) array('attribute_type' => 'color');

$class_front = new TA_WC_Variation_Swatches_Frontend();

$html_swatches = $html_select = '';
$loop = 0;
	var_dump($addon['options']);
foreach( $addon['options'] as $i => $term ){

	$loop++;
	var_dump($term);
	if( is_object($term) ){
		$term->slug = $term->slug."-".$loop;
	}
	else{
		$term = $term."-".$loop;
	}
	

	$html_select .= select_color_html( $html_select, $term , $args);
	
	$html_swatches .= $class_front->swatch_html( $html_swatches, $term, $attr, $args );	
}

?>

<p class="form-row form-row-wide addon-wrap-<?php echo sanitize_title( $addon['field-name'] ); ?>">

	<div class="variation-selector variation-select-color hidden ">
		<select
			id="pa_couleur"
			class="addon addon-select" 
			name="addon-<?php echo sanitize_title( $addon['field-name'] ); ?>"
			data-attribute_name="attribute_<?php echo sanitize_title( $addon['field-name'] ); ?>"
			data-show_option_none="yes">

		<?php if ( ! isset( $addon['required'] ) ) : ?>
			<option value=""><?php _e('None', 'woocommerce-product-addons'); ?></option>
		<?php else : ?>
			<option value=""><?php _e('Select an option...', 'woocommerce-product-addons'); ?></option>
		<?php endif; ?>

			<?php echo $html_select; ?>

		</select>
	</div>

	<div class="tawcvs-swatches" data-attribute_name="attribute_<?php echo sanitize_title( $addon['field-name'] ); ?>" >

		<?php echo $html_swatches; ?>

	</div>

</p>