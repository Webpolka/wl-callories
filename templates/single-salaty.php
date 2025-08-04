<?php
/*
 * 
 * Template name: Single Salaty
 *
 * Стартовый шаблон для вывода страницы - Гарниры 
 *
 */

get_header();

// Проверяем, что это запись типа garniry
if (have_posts()):
    while (have_posts()):
        the_post();

        // Получение мета-данных
        $mass = get_post_meta(get_the_ID(), '_mass_serving', true);
        $protein = get_post_meta(get_the_ID(), '_protein', true);
        $fat = get_post_meta(get_the_ID(), '_fat', true);
        $carbohydrates = get_post_meta(get_the_ID(), '_carbohydrates', true);
        $calories = get_post_meta(get_the_ID(), '_calories', true);
        $ingredients = get_post_meta(get_the_ID(), '_ingredients', true);
        $recipe = get_post_meta(get_the_ID(), '_recipe', true);

        ?>

        <article class="salaty-single" style="position:relative; z-index: 1;">

            <!-- Кнопка назад -->
            <?php
            $referer = wp_get_referer();
            if ($referer) {
                echo '<a href="' . esc_url($referer) . '" class="button">Вернуться назад</a>';
            } else {
                echo '<a href="' . esc_url(home_url()) . '" class="button">На главную</a>';
            }
            ?>

            <?php
            // Проверяем, есть ли миниатюра и выводим её
            if (has_post_thumbnail()) { ?>
                <div class="garniry-thumbnail" style="display:flex; justify-content: center;">
                    <img style="width: 25%;" src="<?php echo get_url_image_for_slider(get_the_ID()); ?>"
                        alt="<?php the_title(); ?>>">
                    <img style="transform:rotate(180deg); width: 25%;" src="<?php echo get_url_image_for_slider(get_the_ID()); ?>"
                        alt="<?php the_title(); ?>>">
                </div>
            <?php } ?>

            <!-- Заголовок -->
            <h1 style="font-size: 45px;"><?php the_title(); ?></h1>

            <div class="salaty-details">
                <p><strong>в 100 гр:</strong></p>
                <br>
                <p><strong>Калорий:</strong> <?php echo esc_html($calories); ?> ккал</p>
                <br>
                <p><strong>Белки:</strong> <?php echo esc_html($protein); ?> г</p>
                <p><strong>Жиры:</strong> <?php echo esc_html($fat); ?> г</p>
                <p><strong>Углеводы:</strong> <?php echo esc_html($carbohydrates); ?> г</p>
                <br>
                <p><strong>Масса порции:</strong> <?php echo esc_html($mass); ?> г</p>
            </div>

            <div class="salaty-ingredients">
                <h2>Ингредиенты</h2>
                <p><?php echo text_to_ul(esc_html($ingredients)); ?></p>
            </div>

            <div class="salaty-recipe">
                <h2>Рецепт</h2>
                <p><?php echo nl2br(esc_html($recipe)); ?></p>
            </div>

        </article>

        <?php
    endwhile;
else:
    echo '<p>Запись не найдена.</p>';
endif;

get_footer();
?>