<?php
/**
 * Single Product Image
 *
 * @version     3.5.1
 */

defined( 'ABSPATH' ) || exit;

global $post, $woocommerce, $product, $porto_settings, $porto_settings_optimize, $porto_product_layout;


  $attachment_ids = $product->get_gallery_image_ids();

 $attachment_id_by_dj = get_post_meta( $post->ID, '_knawatfibu_url' );
 $attachment_ids_by_dj = get_post_meta( $post->ID, '_knawatfibu_wcgallary');
 
 if(!empty($attachment_id_by_dj) && !empty($attachment_ids_by_dj)){
     
     $image_link = $attachment_id_by_dj[0]['img_url'];
     
     ?>
     <div class="product-images images">
     
  <div class="container my-4">

    <hr class="my-4">

    <!--Carousel Wrapper-->
    <div id="carousel-thumb" class="carousel carousel-fade carousel-thumbnails" data-ride="carousel">
      <!--Slides-->
      <div class="carousel-inner" role="listbox">
        
        <?php 
        foreach ( $attachment_ids_by_dj as $attachment_is_by_dj ) {
    $j=0;
        foreach ( $attachment_is_by_dj as $attachment_is_dj ) {
            
		$image_link_g = $attachment_is_dj['url'];
		
		$idinurl = explode('/', $image_link_g);
		
		?>
        <div id="<?php echo $idinurl[4]; ?>" class="carousel-item <?php if($j==0){ echo 'active';} ?>">
          <img class="d-block w-100" src="<?php echo $image_link_g; ?>">
        </div>
       
        <?php $j++; } } ?>
        
      </div>
      <!--/.Slides-->
      <!--Controls-->
      <a class="carousel-control-prev" href="#carousel-thumb" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
      </a>
      <a class="carousel-control-next" href="#carousel-thumb" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
      </a>
      <!--/.Controls-->
      <ol class="carousel-indicators">
    
                    <?php 
        foreach ( $attachment_ids_by_dj as $attachment_is_by_dj ) {
    $i=0;
        foreach ( $attachment_is_by_dj as $attachment_is_dj ) {
            
		$image_link_g = $attachment_is_dj['url'];
		
		?>
         <li data-target="#carousel-thumb" data-slide-to="<?php echo $i; ?>" class="<?php if($i==0){ echo 'active';} ?>"><img class="d-block w-100" src="<?php echo $image_link_g; ?>"
            class="img-fluid"></li>
       
        <?php $i++; } } ?>
        
            
            
      </ol>
    </div></div></div>
  <!--/.Carousel Wrapper-->

     
     
     
     
     <?php
     
     
 }
 
 else
 
 {
     $items_count            = 1;
$product_images_classes = '';
$product_image_classes  = 'img-thumbnail';
$product_images_attrs   = '';

if ( 'extended' === $porto_product_layout ) {
	$items_count               = get_post_meta( get_the_ID(), 'product_layout_columns', true );
	$items_count               = ( ! $items_count && isset( $porto_settings['product-single-columns'] ) ) ? $porto_settings['product-single-columns'] : 3;
	$product_images_attrs     .= ' data-items="3" data-centeritem';
	$columns_responsive        = array();
	$columns_responsive['768'] = 3;
	$columns_responsive['0']   = 1;
	$product_images_attrs     .= ' data-responsive="' . esc_attr( json_encode( $columns_responsive ) ) . '"';
}
if ( 'grid' === $porto_product_layout ) {
	$product_images_classes = 'product-images-block row';
	$items_count            = get_post_meta( get_the_ID(), 'product_layout_grid_columns', true );
	$items_count            = ( ! $items_count && isset( $porto_settings['product-single-columns'] ) ) ? $porto_settings['product-single-columns'] : 2;
	$items_count            = '2';
	if ( '1' === $items_count ) {
		$product_image_classes .= ' col-lg-12';
	} elseif ( '2' === $items_count ) {
		$product_image_classes .= ' col-sm-6';
	} elseif ( '3' === $items_count ) {
		$product_image_classes .= ' col-sm-6 col-lg-4';
	} elseif ( '4' === $items_count ) {
		$product_image_classes .= ' col-sm-6 col-lg-3';
	}
} elseif ( 'sticky_info' === $porto_product_layout || 'sticky_both_info' === $porto_product_layout ) {
	$product_images_classes = 'product-images-block';
} else {
	$product_images_classes = 'product-image-slider owl-carousel show-nav-hover has-ccols';
	if ( 'extended' === $porto_product_layout ) {
		$product_images_classes .= ' ccols-1 ccols-md-3';
	} else {
		$product_images_classes .= ' ccols-1';
	}
}

?>
<div class="product-images images">
	<?php
	$html          = '<div class="' . esc_attr( $product_images_classes ) . '"' . $product_images_attrs . '>';
	$attachment_id = method_exists( $product, 'get_image_id' ) ? $product->get_image_id() : get_post_thumbnail_id();

	if ( $attachment_id ) {

		$image_link = wp_get_attachment_url( $attachment_id );

		$html .= '<div class="' . esc_attr( $product_image_classes ) . '"><div class="inner">';
		$html .= wp_get_attachment_image(
			$attachment_id,
			'full_width' === $porto_product_layout ? 'full' : 'woocommerce_single',
			false,
			array(
				'href'  => esc_url( $image_link ),
				'class' => 'woocommerce-main-image img-responsive',
				'title' => _wp_specialchars( get_post_field( 'post_title', $attachment_id ), ENT_QUOTES, 'UTF-8', true ),
			)
		);
		if ( $porto_settings['product-image-popup'] && ( 'grid' === $porto_product_layout || 'sticky_info' === $porto_product_layout ) ) {
			$html .= '<a class="zoom" href="' . esc_url( $image_link ) . '"><i class="fas fa-search"></i></a>';
		}
		$html .= '</div></div>';

	} else {

		$image_link = wc_placeholder_img_src( 'woocommerce_single' );
		$html      .= '<div class="' . esc_attr( $product_image_classes ) . '"><div class="inner">';
		$html      .= '<img src="' . esc_url( $image_link ) . '" alt="placeholder" href="' . esc_url( $image_link ) . '" class="woocommerce-main-image img-responsive" />';
		$html      .= '</div></div>';
	}

	if ( $attachment_ids ) {
		foreach ( $attachment_ids as $attachment_id ) {

			$image_link = wp_get_attachment_url( $attachment_id );

			if ( ! $image_link ) {
				continue;
			}

			$html .= '<div class="' . esc_attr( $product_image_classes ) . '"><div class="inner">';
			$size  = 'full_width' === $porto_product_layout ? 'full' : 'woocommerce_single';
			if ( strpos( $product_images_classes, 'product-image-slider owl-carousel' ) !== false && isset( $porto_settings_optimize['lazyload'] ) && $porto_settings_optimize['lazyload'] ) {
				$thumb_image = wp_get_attachment_image_src( $attachment_id, $size );
				if ( $thumb_image && is_array( $thumb_image ) && count( $thumb_image ) >= 3 ) {
					$placeholder = porto_generate_placeholder( $thumb_image[1] . 'x' . $thumb_image[2] );
					$html       .= wp_get_attachment_image(
						$attachment_id,
						$size,
						false,
						array(
							'data-src' => esc_url( $thumb_image[0] ),
							'src'      => esc_url( $placeholder[0] ),
							'href'     => esc_url( $image_link ),
							'class'    => 'img-responsive owl-lazy',
						)
					);
				}
			} else {
				$html .= wp_get_attachment_image(
					$attachment_id,
					$size,
					false,
					array(
						'href'  => esc_url( $image_link ),
						'class' => 'img-responsive',
					)
				);
			}
			if ( $porto_settings['product-image-popup'] && ( 'grid' === $porto_product_layout || 'sticky_info' === $porto_product_layout ) ) {
				$html .= '<a class="zoom" href="' . esc_url( $image_link ) . '"><i class="fas fa-search"></i></a>';
			}
			$html .= '</div></div>';

		}
	}

	$html .= '</div>';

	if ( $porto_settings['product-image-popup'] && ( 'default' === $porto_product_layout || 'full_width' === $porto_product_layout || 'transparent' === $porto_product_layout || 'centered_vertical_zoom' === $porto_product_layout || 'extended' === $porto_product_layout || 'left_sidebar' === $porto_product_layout ) ) {
		$html .= '<span class="zoom" data-index="0"><i class="fas fa-search"></i></span>';
	}

	echo apply_filters( 'woocommerce_single_product_image_html', $html, $post->ID ); // phpcs:disable WordPress.XSS.EscapeOutput.OutputNotEscaped

	?>
</div>

<?php
if ( $porto_settings['product-thumbs'] ) {
	do_action( 'woocommerce_product_thumbnails' );
}


}
?>
  