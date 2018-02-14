<?php
/**
 * Displays the author description and bio
 */
global $post;

// Atom values
$atom = wp_parse_args( $atom, [
    // 'attributes'        => ['itemtype'] // This element can use the itemtype to pass a default scheme
    'avatar'            => get_avatar( $post->post_author, 100 ),
    'description'       => get_the_author_meta('description'),
    'imageFloat'        => 'none',
    'imageRounded'      => true,
    'jobTitle'          => '',
    'name'              => get_the_author(),
    'prepend'           => '',  // Prepend the author name with a custom description
    'url'               => esc_url( get_author_posts_url( $post->post_author ) )
 ] );

$atom['imageRounded']               = $atom['imageRounded'] ? 'components-rounded' : ''; 
$atom['attributes']['itemprop']     = "author";
$atom['attributes']['itemscope']    = "itemscope";

if( ! isset($atom['attributes']['itemtype']) ) {
    $atom['attributes']['itemtype'] = 'http://schema.org/Person';
}
$attributes                         = MakeitWorkPress\WP_Components\Build::attributes($atom['attributes']); ?>

<div <?php echo $attributes; ?>>
    
    <?php if( $atom['avatar'] ) { ?> 
    
        <figure class="atom-author-avatar components-<?php echo $atom['imageFloat']; ?>-float <?php echo $atom['imageRounded']; ?>">
            <a class="url fn vcard" href="<?php echo $atom['url']; ?>" rel="author">
                <?php echo $atom['avatar']; ?>
            </a>
            <meta itemprop="name" content="<?php echo $atom['name']; ?>" /> 
        </figure>
    
    <?php } ?>
    
    <?php if( $atom['description'] || $atom['name'] || $atom['jobTitle'] ) { ?>
    
        <div class="atom-author-description components-<?php echo $atom['imageFloat']; ?>-float">
            
            <?php if( $atom['name'] ) { ?> 
                <h4 itemprop="name"><?php echo $atom['prepend'] . $atom['name']; ?></h4>
            <?php } ?>

            <?php if( $atom['jobTitle'] ) { ?>
                <p itemprop="jobTitle"><?php echo $atom['jobTitle']; ?></p>
            <?php } ?>            
            
            <?php if( $atom['description'] ) { ?>
                <p itemprop="text"><?php echo $atom['description']; ?></p>
            <?php } ?>
            
        </div>
    
    <?php } ?>
    
</div>