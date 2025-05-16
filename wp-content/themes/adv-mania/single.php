<?php get_header(); 
$var = variables();
$url  = $var['url'];

$archive_link = get_post_type_archive_link('post');
$postType = get_post_type();
$thumbnail_url  = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <section class="bread-crumbs">
        <div class="container">
            <ul>
                <li><a href="<?php echo $url; ?>"><?php echo pll_e('Main');?></a></li>
                <li><a href="<?php echo $archive_link;?>"><?php echo pll_e('blog');?></a></li>
                <li><?php the_title(); ?></li>
            </ul>
        </div>
    </section>
    <section class="post-page">
        <div class="container">
            <div class="data-post"><?php echo get_the_author_meta('first_name');?> <?php echo get_the_author_meta('last_name');  ?> • <?php $timestamp = get_the_time('U'); echo date('j M Y', $timestamp); ?></div>
            <div class="title-section"><?php the_title(); ?></div>
            <div class="text-section"><?php echo the_excerpt(); ?></div>
            <?php if ( $thumbnail_url ) : ?>
                <div class="post-image">
                    <img src="<?php echo esc_url( $thumbnail_url ); ?>" alt="<?php the_title(); ?>">
                </div>
            <?php endif; ?>
            <div class="post-content">
                <?php the_content(); ?>
            </div>
            <div class="author-info">
                <div class="author-desc">
                <div class="author-media">
                <?php 
                    $author_image = get_avatar_url(get_the_author_meta('ID'));
                    $crb_image = carbon_get_user_meta(get_the_author_meta('ID'), 'crb_image');

                    if ($crb_image) {
                        echo '<img src="' . esc_url($crb_image) . '" alt="Fallback Image">';
                    } elseif ($author_image) {
                        echo '<img src="' . esc_url($author_image) . '" alt="' . esc_attr(get_the_author()) . '">';
                    } else {
                        echo 'No image available';
                    }
                    ?>
                </div>
                    <div class="author-inner">
                        <div class="author-title"><?php echo get_the_author_meta('first_name');?> <?php echo get_the_author_meta('last_name');?></div>
                        <div class="author-text"><?php echo get_the_author_meta('description');?></div>
                    </div>
                </div>
                <div class="author-social">
                    <ul>
                        <li>
                        <a href="#" id="copyPostLink" onclick="copyToClipboard('<?php echo get_permalink(); ?>')" class="link-line">Copy Link</a></li>
                        <li><a href="#" class="link-line">Share</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <?php endwhile; endif; ?>
	<section class="other-section">
		<div class="container">
			<div class="top-section">
				<div class="title-section"><?php echo pll_e('other blogs');?></div>
				<div class="btn-blog">
					<a href="/blog" class="btn btn-red"><?php echo pll_e('View all posts');?></a>
				</div>
			</div>
			<?php
			$current_post_id = get_the_ID();
			$categories = get_the_category($current_post_id);
			$tags = get_the_tags($current_post_id);
			$category_ids = wp_list_pluck($categories, 'term_id');
			$tag_ids = wp_list_pluck($tags, 'term_id');
			$args = array(
				'post_type' => 'post',
				'post__not_in' => array($current_post_id), 
				'posts_per_page' => 12, 
				'orderby' => 'rand', 
			);
			if (!empty($category_ids)) {
				$args['tax_query'][] = array(
					'taxonomy' => 'category',
					'field' => 'term_id',
					'terms' => $category_ids,
				);
			}
			if (!empty($tag_ids)) {
				$args['tax_query'][] = array(
					'taxonomy' => 'post_tag',
					'field' => 'term_id',
					'terms' => $tag_ids,
				);
			}
			$similar_posts_query = new WP_Query($args); ?>
			<?php if ($similar_posts_query->have_posts()) : ?>
				
			<div class="blog-slider">
				<?php while ($similar_posts_query->have_posts()) : $similar_posts_query->the_post(); ?>
				<div class="slide">
					<div class="item">
						
							<div class="item-media">
								<a href="<?php the_permalink(); ?>">
								<?php 
									$image_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
									if ($image_url) {
										echo '<img src="' . esc_url($image_url) . '" alt="' . esc_attr(get_the_title()) . '">';
									}
									?>    
								</a>
							</div>  
							<div class="item-desc">
								<div class="item-date"><?php echo get_the_author_meta('first_name');?> <?php echo get_the_author_meta('last_name');  ?> • <?php $timestamp = get_the_time('U'); echo date('j M Y', $timestamp); ?></div>
								<div class="item-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></div>
								<div class="item-text"><?php the_excerpt(); ?></div>
								<div class="item-bottom">
									<a href="<?php the_permalink(); ?>" class="item-book btn btn-red"><?php echo pll_e('Read More');?></a>
								</div>
							</div>
						
					</div>
				</div>
				<?php endwhile; ?>
			</div>
			<?php endif;?>			
			<?php wp_reset_postdata();?>
		</div>
	</section>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
<?php get_footer(); ?>

