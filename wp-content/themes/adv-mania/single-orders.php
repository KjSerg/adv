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
                <li><a href="<?php echo $url; ?>">Main</a></li>
                <li><?php the_title(); ?></li>
            </ul>
        </div>
    </section>
    <section class="post-page">
        <div class="container">
            <div class="title-section"><?php the_title(); ?></div>
            <?php
                // Отримуємо значення метаполів для посту
                $order_type          = carbon_get_post_meta(get_the_ID(), 'order_type');
                $order_order_tour    = carbon_get_post_meta(get_the_ID(), 'order_order_tour');
                $order_order_start   = carbon_get_post_meta(get_the_ID(), 'order_order_start');
                $order_order_end     = carbon_get_post_meta(get_the_ID(), 'order_order_end');
                $order_persons       = carbon_get_post_meta(get_the_ID(), 'order_persons');
                $order_country       = carbon_get_post_meta(get_the_ID(), 'order_country');
                $order_sum           = carbon_get_post_meta(get_the_ID(), 'order_sum');
                $order_products      = carbon_get_post_meta(get_the_ID(), 'order_products');
                $order_preparation   = carbon_get_post_meta(get_the_ID(), 'preparation_info');
                ?>

                <div class="order-info">
                    <h2>Информация по заказу</h2>
                    <p><strong>Тип заказа:</strong> <?php echo esc_html($order_type); ?></p>
                    <p><strong>Название услуги:</strong> <?php echo esc_html($order_order_tour); ?></p>
                    <p><strong>Начало бронирования:</strong> <?php echo esc_html($order_order_start); ?></p>
                    <p><strong>Окончание бронирования:</strong> <?php echo esc_html($order_order_end); ?></p>
                    <p><strong>Участники:</strong> <?php echo esc_html($order_persons); ?></p>
                    <p><strong>Страна:</strong> <?php echo esc_html($order_country); ?></p>
                    <p><strong>Сумма предоплаты:</strong> <?php echo esc_html($order_sum); ?></p>

                    <?php if (!empty($order_products)): ?>
                        <h3>Участники</h3>
                        <ul>
                            <?php foreach ($order_products as $participant): ?>
                                <li>
                                    <strong>Имя:</strong> <?php echo esc_html($participant['name']); ?><br>
                                    <strong>Страна:</strong> <?php echo esc_html($participant['country']); ?><br>
                                    <strong>Телефон:</strong> <?php echo esc_html($participant['phone']); ?><br>
                                    <strong>Метод коммуникации:</strong> <?php echo esc_html($participant['messenger']); ?><br>
                                    <strong>Email:</strong> <?php echo esc_html($participant['email']); ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>

                    <?php if (!empty($order_preparation)): ?>
                        <h3>Подготовка</h3>
                        <ul>
                            <?php foreach ($order_preparation as $preparation): ?>
                                <li>
                                    <strong>Тема:</strong> <?php echo esc_html($preparation['preparation_title']); ?><br>
                                    <strong>Описание:</strong> <?php echo wp_kses_post($preparation['preparation_value']); ?><br>
                                    <strong>Сумма:</strong> <?php echo esc_html($preparation['preparation_value']); ?><br>
                                    <strong>Дата:</strong> <?php echo esc_html($preparation['preparation_date']); ?><br>
                                    <strong>Автор:</strong> <?php echo esc_html($preparation['preparation_author']); ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>

        </div>
    </section>
<?php endwhile; endif; ?>
<?php get_footer(); ?>
<script>
function copyToClipboard(e, text) {
    e.preventDefault(); // Запобігаємо стандартній поведінці
    navigator.clipboard.writeText(text).then(function() {
    }, function(err) {
        console.error('Could not copy text: ', err);
    });
}
</script>
