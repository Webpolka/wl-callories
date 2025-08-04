<?php
/*
Plugin Name: WL-Callories
Description: Кастомный плагин для реализации калькулятора каллорий и слайдера с гарнирами и салатами на любой странице или записи блога, при помощью шорткодов.
Version: 1.0
Author: WebLegko
*/

function add_callories_menu()
{
    add_menu_page(
        'WL-Каллории',   // Заголовок
        'WL-Каллории',   // Название меню
        'manage_options', // Права
        'callories',     // Слаг
        'callories_page_callback',   // callback — не выводит ничего
        'dashicons-food', // Иконка
        6               // Позиция
    );

    // Добавляем подменю "Салаты" 
    add_submenu_page(
        'callories',    // Родительский слаг
        'Салаты',        // Заголовок страницы
        'Салаты',        // Название пункта
        'manage_options',
        'edit.php?post_type=salaty' // Перенаправление к типу "Салаты"
    );

    // Добавляем подменю "Гарниры" 
    add_submenu_page(
        'callories',
        'Гарниры',
        'Гарниры',
        'manage_options',
        'edit.php?post_type=garniry'
    );

}
add_action('admin_menu', 'add_callories_menu');


// Регистрация типов записей при инициализации
function create_custom_post_types()
{
    // Тип "Гарниры"
    register_post_type('garniry', array(
        'labels' => array(
            'name' => 'Гарниры',
            'singular_name' => 'Гарнир',
            'add_new' => 'Добавить новый',
            'add_new_item' => 'Добавить новый гарнир',
            'edit_item' => 'Редактировать гарнир',
            'new_item' => 'Новый гарнир',
            'view_item' => 'Посмотреть гарнир',
            'search_items' => 'Искать гарниры',
            'not_found' => 'Гарниры не найдены',
            'not_found_in_trash' => 'В корзине гарниры не найдены',
        ),
        'show_in_menu' => false, // скрыть из меню
        'public' => true,
        'has_archive' => true,
        'rewrite' => array('slug' => 'garniry'),
        'supports' => array('title', 'thumbnail'),
    ));

    // Тип "Салаты"
    register_post_type('salaty', array(
        'labels' => array(
            'name' => 'Салаты',
            'singular_name' => 'Салат',
            'add_new' => 'Добавить новый',
            'add_new_item' => 'Добавить новый салат',
            'edit_item' => 'Редактировать салат',
            'new_item' => 'Новый салат',
            'view_item' => 'Посмотреть салат',
            'search_items' => 'Искать салаты',
            'not_found' => 'Салаты не найдены',
            'not_found_in_trash' => 'В корзине салаты не найдены',
        ),
        'show_in_menu' => false, // скрыть из меню
        'public' => true,
        'has_archive' => true,
        'rewrite' => array('slug' => 'salaty'),
        'supports' => array('title', 'thumbnail'),
    ));
}
add_action('init', 'create_custom_post_types');

function callories_page_callback()
{
    echo '<h2>Рекомендаци по использованию слайдера WL-Callories-Slider !</h2>';
    echo '<p>1. Загрузите изображения салатов и гарниров, и заполните входные данные в соответствующих разделах.</p>';
    echo '<p>2. Вставьте шорткод в нужном месте страницы или записи:</p>';
    echo '<strong style="display:block;">[wl-callories-slider]</strong>';
    echo '<br>';
    echo '<h2>Как вставить калькулятор WL-Callories-Calculator ?</h2>';
    echo '<p>Просто разместите этот шорткод в нужном месте страницы или записи:</p>';
    echo '<strong style="display:block;">[wl-callories-calculator]</strong>';
    echo '<p>если хотите поменять заголовок, стили заголовка воспользуйтесь атрибутами:</p>';
    echo '<strong style="display:block;">[wl-callories-calculator title="Ваш новый заголовок" title_class="your-title-class"]</strong>';
}

// Добавляем мета-боксы для каждого типа записи
add_action('add_meta_boxes', function () {
    $post_types = array('garniry', 'salaty'); // замените на ваши типы, если другие
    foreach ($post_types as $ptype) {
        add_meta_box(
            'nutrition_fields', // ID
            'Cвойства данного блюда', // Заголовок
            'render_nutrition_fields', // Колбэк
            $ptype, // Тип записи
            'normal',
            'default'
        );
    }
});

// Колбэк для отображения полей
function render_nutrition_fields($post)
{
    wp_nonce_field('save_nutrition_fields', 'nutrition_fields_nonce');

    // Получение текущих значений
    $mass = get_post_meta($post->ID, '_mass_serving', true);
    $protein = get_post_meta($post->ID, '_protein', true);
    $fat = get_post_meta($post->ID, '_fat', true);
    $carbohydrates = get_post_meta($post->ID, '_carbohydrates', true);
    $calories = get_post_meta($post->ID, '_calories', true);
    $ingredients = get_post_meta($post->ID, '_ingredients', true);
    $recipe = get_post_meta($post->ID, '_recipe', true);

    // Выводим поля

    echo '<p><label for="calories">Ккал на 100 г:</label><br />';
    echo '<input type="number" step="any" id="calories" name="calories" value="' . esc_attr($calories) . '" /></p>';

    echo '<p><label for="protein">Белок на 100 г:</label><br />';
    echo '<input type="number" step="any" id="protein" name="protein" value="' . esc_attr($protein) . '" /></p>';

    echo '<p><label for="fat">Жиры на 100 г:</label><br />';
    echo '<input type="number" step="any" id="fat" name="fat" value="' . esc_attr($fat) . '" /></p>';

    echo '<p><label for="carbohydrates">Углеводы на 100 г:</label><br />';
    echo '<input type="number" step="any" id="carbohydrates" name="carbohydrates" value="' . esc_attr($carbohydrates) . '" /></p>';

    echo '<p><label for="mass_serving">Масса порции (грамм):</label><br />';
    echo '<input type="number" id="mass_serving" name="mass_serving" value="' . esc_attr($mass) . '" /></p>';

    echo '<p><label for="ingredients">Ингредиенты:</label><br />';
    echo '<textarea id="ingredients" name="ingredients" rows="5" style="width: 100%;">' . esc_textarea($ingredients) . '</textarea></p>';

    echo '<p><label for="recipe">Рецепт приготовления:</label><br />';
    echo '<textarea id="recipe" name="recipe" rows="5" style="width: 100%;">' . esc_textarea($recipe) . '</textarea></p>';
}

// Сохраняем значения при сохранении поста
add_action('save_post', function ($post_id) {
    if (!isset($_POST['nutrition_fields_nonce']) || !wp_verify_nonce($_POST['nutrition_fields_nonce'], 'save_nutrition_fields')) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $fields = array(
        'mass_serving' => '_mass_serving',
        'protein' => '_protein',
        'fat' => '_fat',
        'carbohydrates' => '_carbohydrates',
        'calories' => '_calories',
        'ingredients' => '_ingredients',
        'recipe' => '_recipe'
    );

    foreach ($fields as $field_name => $meta_key) {
        if (isset($_POST[$field_name])) {
            $value = $_POST[$field_name];
            if (in_array($field_name, array('ingredients', 'recipe'))) {
                $value = sanitize_textarea_field($value);
            } else {
                $value = sanitize_text_field($value);
            }

            if ($value !== '') {
                update_post_meta($post_id, $meta_key, $value);
            } else {
                delete_post_meta($post_id, $meta_key);
            }
        }
    }
});



// Подключаем скрипты и стили Callories
function wl_callories_enqueue_scripts()
{
    // Регистрация и подключение CSS
    wp_enqueue_style(
        'wl-callories-slider-styles', // уникальный хэндл
        plugins_url('wl-callories/postcss/main.min.css', __FILE__), // путь к файлу стилей
        array(), // зависимости
        '1.0', // версия
        'all' // медиа
    );

    // Регистрация и подключение JS
    wp_enqueue_script(
        'wl-callories-slider-scripts', // уникальный хэндл
        plugins_url('wl-callories/js/main.min.js', __FILE__), // путь к файлу скрипта
        array('jquery'), // зависимости
        '1.0', // версия
        true // в футере
    );
}
add_action('wp_enqueue_scripts', 'wl_callories_enqueue_scripts');


// 1. Создадим шорткод для вывода калькулятора каллорий
function wl_callories_calculator_shortcode($atts)
{
    ob_start();

    $atts = shortcode_atts(array(
        'title' => 'Калькулятор калорий для похудения',
        'title_class' => 'calorie-calculator-title'
    ), $atts, 'wl-callories-calculator'); ?>

    <section class="calorie-calculator">
        <h2 class="<?php echo $atts['title_class']; ?>"><?php echo $atts['title']; ?></h2>
        <div class="calorie-calculator-container">
            <form class="calorie-calculator-form" method="post" id="calorieForm">
                <p>*Указывайте целые числа</p>

                <div class="form-group">
                    <p class="calorie-strong">Укажите параметры, пол:</p>
                    <div class="gender-options">
                        <label>
                            <input name="gender" required type="radio" value="female"> Женский
                        </label>
                        <label>
                            <input name="gender" required type="radio" value="male"> Мужской
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label for="age">Возраст:</label>
                    <input id="age" class="input-field" name="age" required type="number" min="3" max="150">
                </div>

                <div class="form-group">
                    <label for="height">Рост, см:</label>
                    <input id="height" class="input-field" name="height" required type="number" min="50" max="250">
                </div>

                <div class="form-group">
                    <label for="weight">Вес, кг:</label>
                    <input id="weight" class="input-field" name="weight" required type="number" min="10" max="250">
                </div>

                <div class="form-group">
                    <label for="lifestyle">Укажите ваш образ жизни</label>
                    <select id="lifestyle" required name="lifestyle">
                        <option value="">Ваш образ жизни</option>
                        <option value="1.2">Сидячий и малоподвижный</option>
                        <option value="1.375">Легкая активность (упражнения 1-3 раза в неделю)</option>
                        <option value="1.55">Средняя активность (тренировки 3-5 раз в неделю)</option>
                        <option value="1.725">Высокая активность (высокие нагрузки каждый день)</option>
                        <option value="1.9">Экстремально-высокая активность</option>
                    </select>
                </div>

                <div class="form-group">
                    <p class="calorie-strong">Ваша цель</p>
                    <div class="goal-options">
                        <label>
                            <input name="goal" required type="radio" value="loss"> сбросить вес
                        </label>
                        <label>
                            <input name="goal" required type="radio" value="maintain"> сохранить вес
                        </label>
                        <label>
                            <input name="goal" required type="radio" value="gain"> набрать вес
                        </label>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit">Рассчитать</button>
                    <button type="reset">Сбросить</button>
                </div>
            </form>

            <div id="calorie-results"></div>
        </div>
    </section>
    <?php return ob_get_clean();
}

//Зарегистрируем шорткод с тегом [wl-callories-calculator]
add_shortcode('wl-callories-calculator', 'wl_callories_calculator_shortcode');



// 2. Создадим шорткод для вывода слайдера с блюдами 
function wl_callories_slider_shortcode($atts)
{
    ob_start();

    $atts = shortcode_atts(array(
        'title' => 'Тарелки по Методу тарелки',
        'title_class' => '',
        'max_width' => '445',
    ), $atts, 'wl-callories-slider'); ?>

    <section class="calorie-slider" style="max-inline-size:<?php echo $atts['max_width']; ?>px" id="calorie-slider" itemscope itemtype="https://schema.org/WebPage">
        <header>
           <h2 class="calorie-slider-title " itemprop="headline" class="<?php echo $atts['title_class']; ?>"><?php echo $atts['title']; ?></h2>
            <p>
                На тарелке:
                <span id="calorie-sum-weight" itemprop="weight"></span> г,
                <span id="calorie-sum-kkal" itemprop="calories"></span> ккал Б:
                <span id="calorie-sum-protein" itemprop="proteinContent"></span> г Ж:
                <span id="calorie-sum-fats" itemprop="fatContent"></span> г У:
                <span id="calorie-sum-carbo" itemprop="carbohydrateContent"></span> г
            </p>
        </header>

        <div class="calorie-slider_row">
            <!-- ЛЕВАЯ СТОРОНА -->
            <div class="calorie-slider_col" itemscope itemtype="https://schema.org/ItemList">
                <!-- Слайдер гарниров -->
                <div class="swiper" id="garnirs-swiper" itemprop="itemList">
                    <div class="swiper-button-prev" aria-label="Предыдущий слайд" role="button" aria-disabled="false">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M9.99998 17.5C9.81933 17.5 9.64084 17.4608 9.47685 17.385C9.31287 17.3093 9.16731 17.1988 9.05027 17.0612L4.68281 11.9368C4.61159 11.8527 4.57664 11.7437 4.58561 11.6338C4.59459 11.5239 4.64677 11.422 4.73071 11.3505C4.81465 11.279 4.92351 11.2437 5.03344 11.2523C5.14337 11.2609 5.24541 11.3128 5.31719 11.3965L9.58331 16.402L9.58331 2.91667C9.58331 2.80616 9.62721 2.70018 9.70535 2.62204C9.78349 2.5439 9.88947 2.5 9.99998 2.5C10.1105 2.5 10.2165 2.5439 10.2946 2.62204C10.3727 2.70018 10.4166 2.80616 10.4166 2.91667L10.4166 16.402L14.6828 11.3965C14.7546 11.3128 14.8566 11.261 14.9666 11.2523C15.0765 11.2437 15.1854 11.279 15.2693 11.3506C15.3532 11.4221 15.4054 11.5239 15.4144 11.6338C15.4234 11.7437 15.3884 11.8527 15.3172 11.9369L10.95 17.061C10.8329 17.1986 10.6873 17.3092 10.5233 17.385C10.3592 17.4608 10.1807 17.5 9.99998 17.5Z"
                                fill="#000000"></path>
                        </svg>
                    </div>
                    <div class="swiper-button-next" aria-label="Следующий слайд" role="button" aria-disabled="false">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M9.99999 2.5C10.1806 2.49996 10.3591 2.53918 10.5231 2.61495C10.6871 2.69072 10.8327 2.80123 10.9497 2.93883L15.3172 8.06317C15.3884 8.14735 15.4233 8.25632 15.4144 8.36622C15.4054 8.47613 15.3532 8.57799 15.2693 8.64949C15.1853 8.72099 15.0765 8.75631 14.9665 8.74769C14.8566 8.73908 14.7546 8.68724 14.6828 8.60354L10.4167 3.59804L10.4167 17.0833C10.4167 17.1938 10.3728 17.2998 10.2946 17.378C10.2165 17.4561 10.1105 17.5 9.99999 17.5C9.88948 17.5 9.7835 17.4561 9.70536 17.378C9.62722 17.2998 9.58332 17.1938 9.58332 17.0833L9.58332 3.59804L5.31716 8.6035C5.24537 8.6872 5.14334 8.73904 5.03341 8.74765C4.92348 8.75626 4.81462 8.72095 4.73068 8.64945C4.64674 8.57794 4.59456 8.47608 4.58558 8.36618C4.5766 8.25628 4.61156 8.1473 4.68278 8.06312L9.04999 2.93904C9.16706 2.80138 9.31265 2.69082 9.47669 2.61501C9.64072 2.5392 9.81928 2.49996 9.99999 2.5Z"
                                fill="#000000"></path>
                        </svg>
                    </div>

                    <!-- Карточки гарнира -->
                    <div class="swiper-wrapper">
                        <?php $custom_post_type = 'garniry'; // замените на ваш тип записи
                    
                        // Создаем новый запрос
                        $args = array(
                            'post_type' => $custom_post_type,
                            'posts_per_page' => -1, // Получить все посты
                        );

                        $query = new WP_Query($args);                      

                        // Проверяем, есть ли посты
                        if ($query->have_posts()) {
                            $i = 1;
                            while ($query->have_posts()) {
                                $query->the_post();

                                $gKkal = get_post_meta(get_the_ID(), '_calories', true);
                                $gProtein = get_post_meta(get_the_ID(), '_protein', true);
                                $gFats = get_post_meta(get_the_ID(), '_fat', true);
                                $gCarbo = get_post_meta(get_the_ID(), '_carbohydrates', true);
                                $gServing = get_post_meta(get_the_ID(), '_mass_serving', true);
                                ?>
                                <div class="swiper-slide" itemprop="itemListElement" itemscope
                                    itemtype="https://schema.org/ListItem">
                                    <meta itemprop="position" content="<?php echo $i;?>">
                                    <article itemscope itemtype="https://schema.org/Product">
                                        <a href="<?php the_permalink();?>" class="calorie-card" itemprop="url" itemscope
                                            itemtype="https://schema.org/Thing" data-title="<?php the_title();?>"
                                            data-p="<?php echo $gProtein;?>" data-f="<?php echo $gFats;?>" data-c="<?php echo $gCarbo;?>"
                                            data-kkal="<?php echo $gKkal;?>" data-serving="<?php echo $gServing;?>">
                                            <figure class="calorie-card_image" itemprop="image" itemscope
                                                itemtype="https://schema.org/ImageObject">
                                                <img src="<?php the_post_thumbnail_url();?>" alt="<?php the_title();?>"
                                                    itemprop="url">
                                            </figure>
                                            <meta itemprop="name" content="<?php the_title();?>">
                                        </a>
                                    </article>
                                </div>
                                <?php
                                $i++;
                            }
                            wp_reset_postdata();
                        } else {
                            echo 'Посты не найдены.';
                        }
                        ?>
                    </div>
                </div>

                <!-- Информация о карточке -->
                <div class="calorie-card-info" itemscope itemtype="https://schema.org/NutritionInformation">
                    <div class="calorie-card-info_title">
                        <p id="calorie-garnirs-title" itemprop="name"></p>
                    </div>
                    <div class="calorie-card-info_kkal">
                        <p>на 100 г: <span id="calorie-garnirs-kkal" itemprop="calories"></span> ккал,</p>
                    </div>
                    <div class="calorie-card-info_nutrients" itemscope itemtype="https://schema.org/NutritionInformation">
                        <p>
                            Б : <span id="calorie-garnirs-protein" itemprop="proteinContent"></span> г Ж :
                            <span id="calorie-garnirs-fats" itemprop="fatContent"></span> г У :
                            <span id="calorie-garnirs-carbo" itemprop="carbohydrateContent"></span> г
                        </p>
                    </div>
                </div>
            </div>

            <!-- ПРАВАЯ СТОРОНА -->
            <div class="calorie-slider_col" itemscope itemtype="https://schema.org/ItemList">
                <!-- Слайдер салатов -->
                <div class="swiper" id="salads-swiper" itemprop="itemList">
                    <div class="swiper-button-prev" aria-label="Предыдущий слайд" role="button" aria-disabled="false">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M9.99998 17.5C9.81933 17.5 9.64084 17.4608 9.47685 17.385C9.31287 17.3093 9.16731 17.1988 9.05027 17.0612L4.68281 11.9368C4.61159 11.8527 4.57664 11.7437 4.58561 11.6338C4.59459 11.5239 4.64677 11.422 4.73071 11.3505C4.81465 11.279 4.92351 11.2437 5.03344 11.2523C5.14337 11.2609 5.24541 11.3128 5.31719 11.3965L9.58331 16.402L9.58331 2.91667C9.58331 2.80616 9.62721 2.70018 9.70535 2.62204C9.78349 2.5439 9.88947 2.5 9.99998 2.5C10.1105 2.5 10.2165 2.5439 10.2946 2.62204C10.3727 2.70018 10.4166 2.80616 10.4166 2.91667L10.4166 16.402L14.6828 11.3965C14.7546 11.3128 14.8566 11.261 14.9666 11.2523C15.0765 11.2437 15.1854 11.279 15.2693 11.3506C15.3532 11.4221 15.4054 11.5239 15.4144 11.6338C15.4234 11.7437 15.3884 11.8527 15.3172 11.9369L10.95 17.061C10.8329 17.1986 10.6873 17.3092 10.5233 17.385C10.3592 17.4608 10.1807 17.5 9.99998 17.5Z"
                                fill="#000000"></path>
                        </svg>
                    </div>
                    <div class="swiper-button-next" aria-label="Следующий слайд" role="button" aria-disabled="false">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M9.99999 2.5C10.1806 2.49996 10.3591 2.53918 10.5231 2.61495C10.6871 2.69072 10.8327 2.80123 10.9497 2.93883L15.3172 8.06317C15.3884 8.14735 15.4233 8.25632 15.4144 8.36622C15.4054 8.47613 15.3532 8.57799 15.2693 8.64949C15.1853 8.72099 15.0765 8.75631 14.9665 8.74769C14.8566 8.73908 14.7546 8.68724 14.6828 8.60354L10.4167 3.59804L10.4167 17.0833C10.4167 17.1938 10.3728 17.2998 10.2946 17.378C10.2165 17.4561 10.1105 17.5 9.99999 17.5C9.88948 17.5 9.7835 17.4561 9.70536 17.378C9.62722 17.2998 9.58332 17.1938 9.58332 17.0833L9.58332 3.59804L5.31716 8.6035C5.24537 8.6872 5.14334 8.73904 5.03341 8.74765C4.92348 8.75626 4.81462 8.72095 4.73068 8.64945C4.64674 8.57794 4.59456 8.47608 4.58558 8.36618C4.5766 8.25628 4.61156 8.1473 4.68278 8.06312L9.04999 2.93904C9.16706 2.80138 9.31265 2.69082 9.47669 2.61501C9.64072 2.5392 9.81928 2.49996 9.99999 2.5Z"
                                fill="#000000"></path>
                        </svg>
                    </div>

                    <!-- Карточки салата -->
                    <div class="swiper-wrapper">
                        <?php $custom_post_type = 'salaty'; // замените на ваш тип записи
                    
                        // Создаем новый запрос
                        $args = array(
                            'post_type' => $custom_post_type,
                            'posts_per_page' => -1, // Получить все посты
                        );

                        $query = new WP_Query($args);                      

                        // Проверяем, есть ли посты
                        if ($query->have_posts()) {
                            $j = 1;
                            while ($query->have_posts()) {
                                $query->the_post();

                                $sKkal = get_post_meta(get_the_ID(), '_calories', true);
                                $sProtein = get_post_meta(get_the_ID(), '_protein', true);
                                $sFats = get_post_meta(get_the_ID(), '_fat', true);
                                $sCarbo = get_post_meta(get_the_ID(), '_carbohydrates', true);
                                $sServing = get_post_meta(get_the_ID(), '_mass_serving', true);
                                ?>
                                <div class="swiper-slide" itemprop="itemListElement" itemscope
                                    itemtype="https://schema.org/ListItem">
                                    <meta itemprop="position" content="<?php echo $j;?>">
                                    <article itemscope itemtype="https://schema.org/Product">
                                        <a href="<?php the_permalink();?>" class="calorie-card" itemprop="url" itemscope
                                            itemtype="https://schema.org/Thing" data-title="<?php the_title();?>"
                                            data-p="<?php echo $sProtein;?>" data-f="<?php echo $sFats;?>" data-c="<?php echo $sCarbo;?>"
                                            data-kkal="<?php echo $sKkal;?>" data-serving="<?php echo $sServing;?>">
                                            <figure class="calorie-card_image" itemprop="image" itemscope
                                                itemtype="https://schema.org/ImageObject">
                                                <img src="<?php the_post_thumbnail_url();?>" alt="<?php the_title();?>"
                                                    itemprop="url">
                                            </figure>
                                            <meta itemprop="name" content="<?php the_title();?>">
                                        </a>
                                    </article>
                                </div>
                                <?php
                                $j++;
                            }
                            wp_reset_postdata();
                        } else {
                            echo 'Посты не найдены.';
                        }
                        ?>
                    </div>
                </div>
                <!-- Инфо о карточке -->
                <div class="calorie-card-info" itemscope itemtype="https://schema.org/NutritionInformation">
                    <div class="calorie-card-info_title">
                        <p id="calorie-salads-title" itemprop="name"></p>
                    </div>
                    <div class="calorie-card-info_kkal">
                        <p>на 100 г: <span id="calorie-salads-kkal" itemprop="calories"></span> ккал,</p>
                    </div>
                    <div class="calorie-card-info_nutrients" itemscope itemtype="https://schema.org/NutritionInformation">
                        <p>
                            Б : <span id="calorie-salads-protein" itemprop="proteinContent"></span> г Ж :
                            <span id="calorie-salads-fats" itemprop="fatContent"></span> г У :
                            <span id="calorie-salads-carbo" itemprop="carbohydrateContent"></span> г
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>';

<?php return ob_get_clean(); }
//Зарегистрируем шорткод с тегом [wl-callories-slider]
add_shortcode('wl-callories-slider', 'wl_callories_slider_shortcode');