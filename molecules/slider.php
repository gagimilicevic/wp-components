<?php
/**
 * Displays a slider component based upon jQuery Flexslider
 */

// Molecule values
$molecule = wp_parse_args( $molecule, array(
    'id'            => uniqid(),            // Used to link the slider to it's variables
    'options'       => array(
        'animation'         => 'fade',      // Type of animation
        'animationSpeed'    => 500,         // Speed of animation
        'nextText'          => '&rsaquo;',  // Next indicator 
        'prevText'          => '&lsaquo;',  // Prev indicator
        'slideshowSpeed'    => 5000 ,       // Speed of slideshow
        'smoothHeight'      => false         // Smoothes the height
    ),
    'scheme'        => 'http://www.schema.org/CreativeWork',     
    'scroll'        => false,     
    'slides'        => array(),     // Supports a array with position, background (url or color value), video, image and atoms as keys.
    'size'          => 'full'       // The default size for images    
) ); 

// Set our variables
if( $molecule['options'] ) {
    
    add_action( 'wp_footer', function() use($molecule) {
        echo '<script type="text/javascript">var slider' . $molecule['id'] . ' = ' . json_encode($molecule['options']) . ';</script>';    
    } );
    
}

// Enqueue our script
if( ! wp_script_is('components-slider') || apply_filters('components_slider_script', true) )
    wp_enqueue_script('components-slider'); ?>

<div class="molecule-slider <?php echo $molecule['style']; ?>" data-id="<?php echo $molecule['id']; ?>">
    
    <?php do_action( 'components_slider_before', $molecule ); ?>
    
    <ul class="slides">
        
        <?php foreach( $molecule['slides'] as $slide ) { 
        
            // Background in a slider
            if( isset($slide['background']) ) {
                $slide['background'] = strpos($slide['background'], 'http') === 0 
                    ? 'style="background-image: url(' . $slide['background'] . ');"' 
                    : 'style="background-color: ' . $slide['background'] . ';"';
            } else {
                $slide['background'] = '';   
            }
    
            // Position of elements
            $slide['position'] = $slide['position'] ? 'components-position-' . $slide['position'] : ''; ?>

            <li class="molecule-slide" itemscope="itemscope" itemtype="<?php echo $molecule['scheme']; ?>">
                
                <?php 
    
                    // Atoms
                    if( isset($slide['atoms']) ) { 
                
                ?> 

                    <div class="molecule-slide-wrapper <?php echo $slide['position']; ?>" <?php echo $slide['background']; ?>>
                        <div class="molecule-slide-caption">

                            <?php

                                // Add our custom atoms for this caption                                
                                foreach( $slide['atoms'] as $name => $variables ) {
                                    Components\Build::atom($name, $variables);    
                                }
                            ?>

                        </div>
                    </div>

                <?php 

                    // Or... slide video or image
                    } elseif( isset($slide['image']) ) { 
                        Components\Build::atom( 'image', array($slide['image']) );
                    } elseif( isset($slide['video']) ) { 
                        Components\Build::atom( 'video', array($slide['video']) );
                    }

                ?>

            </li>

        <?php } ?>
        
    </ul>
    
    <?php 
    
        // Scroll-down button
        if( $molecule['scroll'] ) { 
            Components\Build::atom( 'scroll', $molecule['scroll'] );
        }
    
    ?> 
    
    <?php do_action( 'components_slider_after', $molecule ); ?>

</div>