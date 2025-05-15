<?php get_header();
$var = variables();
$url  = $var['url'];
?>
    <section class="bread-crumbs">
        <div class="container">
            <ul>
                <li><a href="<?php echo $url; ?>">Main</a></li>						
                <li><?php the_title();?></li>
            </ul>
        </div>
    </section>
    <section class="post-page sample-page">
        <div class="container">
            <div class="post-content">
                <h1><?php the_title();?></h1>
                <?php the_content();?>
            </div>
        </div>
    </section>
<?php get_footer();?>