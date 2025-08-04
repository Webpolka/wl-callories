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

     // Хук для обработки сохранения настроек
    add_action('admin_init', 'register_my_settings');

}
add_action('admin_menu', 'add_callories_menu');

function register_my_settings() {
    // Регистрируем опцию
    register_setting('my_settings_group', 'wl_checkbox_option');  
}


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
    echo '<h2>Как использовать слайдер WL-Callories-Slider ?</h2>';
    echo '<p>1. Загрузите изображения салатов и гарниров, и заполните входные данные в соответствующих разделах.</p>';
    echo '<p>Изображения могут быть любых размеров но пропорция должна быть 1/2 (рекомендовано: ширина - 750 / высота - 1500), формата webp </p>';            
    echo '<p>2. Вставьте шорткод в нужном месте страницы или записи:</p>';
    echo '<strong style="display:block;">[wl-callories-slider]</strong>';
    echo '<p>если хотите поменять заголовок, стили заголовка, максимальную ширины(max_width) слайдера, цвет бегунка(color_accent) в модальном окне или кол-во изображений(initial_img) для подгрузки воспользуйтесь атрибутами:</p>';        
    echo '<strong style="display:block;">ПРИМЕР: [wl-callories-slider max_width="1000" title="Ваш новый заголовок" title_class="your-title-class" initial_img="2" color_accent="orange"]';
    echo '<p>для возможности перехода на страницу блюда при клике, скопируйте из папки templates плагина стартовые шаблоны <b>single-garniry.php</b> и <b>single-salaty.php</b> в корень вашей темы.</p>';            
    echo '<br>';
    echo '<h2>Как использовать WL-Callories-Calculator ?</h2>';
    echo '<p>Просто разместите этот шорткод в нужном месте страницы или записи:</p>';
    echo '<strong style="display:block;">[wl-callories-calculator]</strong>';
    echo '<p>если хотите поменять заголовок, стили заголовка или цвет кнопки и бегунка(color_accent) воспользуйтесь атрибутами:</p>'; 
    echo '<strong style="display:block;">ПРИМЕР: [wl-callories-calculator title="Ваш новый заголовок" title_class="your-title-class" color_accent="orange"]</strong>';
    echo '<br>';
    echo '<h2>ОБЩАЯ РЕКОМЕНДАЦИЯ ! Не вствляйте сразу два одинаковых элемента на одной странице. Скрипт обработает только первые.</h2>';
    echo '<br>';
    echo '<br>';
    ?><div class="wrap">
        <h2>Настройки</h2>
        <form method="post" action="options.php">
            <?php settings_fields('my_settings_group'); ?>
            <?php do_settings_sections('my_settings_group'); ?>

            <table class="form-table">
                <tr valign="top">
                    <th scope="row">ВЫВОДИТЬ ВСЁ В МОДАЛКУ: </th>
                    <td>
                        <input type="checkbox" name="wl_checkbox_option" value="1" <?php checked(1, get_option('wl_checkbox_option'), true); ?> />
                    </td>                    
                </tr>
            </table>
            
            <?php submit_button(); ?>
        </form>  
    </div>
    <?php
      // Подключаем скрипты и стили
    add_action('admin_enqueue_scripts', 'enqueue_color_picker_assets');
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

// Создание пользовательского размера изображения с пропорциями 1:2
add_image_size('custom-size-1-2', 600, 1200, true); // или false для жесткой обрезки

function get_url_image_for_slider($post_id, $size = 'thumbnail')
{
    // Получить тип поста
    $post_type = get_post_type($post_id);

    // Проверка типа
    if (in_array($post_type, array('garniry', 'salaty'))) {
        // Использовать нужный размер
        $size = 'custom-size-1-2';
    }

    // Получить миниатюру
    if (has_post_thumbnail($post_id)) {
        return get_the_post_thumbnail_url($post_id, $size);
    }
    return '';
}

function add_custom_thumbnail_message($content, $post_id)
{
    $post_type = get_post_type($post_id);
    if (in_array($post_type, array('garniry', 'salaty'))) {
        $message = '<p style="color: #555; font-size: 18px; text-transform: uppercase;"><span style="color: red">Внимание !</span><br/>пропорции 1:2 обязательны</p>';
        return $content . $message;
    }
    return $content;
}
add_filter('admin_post_thumbnail_html', 'add_custom_thumbnail_message', 10, 2);

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
        plugins_url('wl-callories/dist/wl-callories/postcss/main.min.css', __FILE__), // путь к файлу стилей
        array(), // зависимости
        '1.0', // версия
        'all' // медиа
    );

    // Регистрация и подключение JS
    wp_enqueue_script(
        'wl-callories-slider-scripts', // уникальный хэндл
        plugins_url('wl-callories/dist/wl-callories/js/main.min.js', __FILE__), // путь к файлу скрипта
        array('jquery'), // зависимости
        '1.0', // версия
        true // в футере
    );
    
    wp_localize_script('wl-callories-slider-scripts', 'MyAjax', array(
        'ajaxurl' => admin_url('admin-ajax.php')
    ));


}
add_action('wp_enqueue_scripts', 'wl_callories_enqueue_scripts');


// 1. Создадим шорткод для вывода калькулятора каллорий
function wl_callories_calculator_shortcode($atts)
{
    ob_start();

    $checkbox_value = get_option('wl_checkbox_option');

    $atts = shortcode_atts(array(
        'title' => 'Калькулятор калорий для похудения',
        'title_class' => 'calorie-calculator-title',
        'color_accent' => 'rgb(76, 175, 80)',
    ), $atts, 'wl-callories-calculator'); ?>

    <section id="calorie-calculator" class="calorie-calculator" <?php echo $atts['color_accent'] ? 'style="--wl-accent-color: ' . htmlspecialchars($atts['color_accent'] ) . ';"' : ''; ?>>
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
                <?php if ($checkbox_value) { ?>
                    <button type="submit" data-modal="true">Рассчитать</button>
                    <button type="reset" data-modal="true">Сбросить</button>
                <?php } else { ?>  
                    <button type="submit" data-modal="false">Рассчитать</button>
                    <button type="reset" data-modal="false">Сбросить</button>
                <?php }; ?>                      
                    
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
        'color_accent' => 'rgb(76, 175, 80)',        
        'initial_img' => 1        
    ), $atts, 'wl-callories-slider'); 
        
   $checkbox_value = get_option('wl_checkbox_option');
    ?>      
    
    <section class="calorie-slider" <?php echo $atts['color_accent'] ? 'style="--wl-accent-color: ' . htmlspecialchars($atts['color_accent'] ) . '; max-inline-size:'. $atts['max_width'].'px;"' : 'style="max-inline-size:'. $atts['max_width'].'px;"'; ?> id="calorie-slider" itemscope itemtype="https://schema.org/WebPage">
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
                <div class="swiper" id="garnirs-swiper" data-initial="<?php echo $atts['initial_img']; ?>" itemprop="itemList">
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
                                    
                                    <?php if ($checkbox_value) { ?>
                                            <div class="calorie-card wl-open-modal" data-postid="<?php echo get_the_ID(); ?>" itemscope
                                            itemtype="https://schema.org/Thing" data-title="<?php the_title();?>"
                                              data-p="<?php echo $gProtein;?>" data-f="<?php echo $gFats;?>" data-c="<?php echo $gCarbo;?>"
                                            data-kkal="<?php echo $gKkal;?>" data-serving="<?php echo $gServing;?>">

                                            <?php } else { ?>  

                                            <a href="<?php the_permalink();?>" class="calorie-card" itemprop="url" itemscope
                                            itemtype="https://schema.org/Thing" data-title="<?php the_title();?>"
                                              data-p="<?php echo $gProtein;?>" data-f="<?php echo $gFats;?>" data-c="<?php echo $gCarbo;?>"
                                            data-kkal="<?php echo $gKkal;?>" data-serving="<?php echo $gServing;?>">

                                        <?php }; ?>                                       
                                          
                                            <figure class="calorie-card_image" itemprop="image" itemscope
                                                itemtype="https://schema.org/ImageObject">
                                                <img data-src="<?php echo get_url_image_for_slider(get_the_ID());?>" data-updated="false" src="<?php echo get_template_directory_uri();?>/images/1x1.png" 
                                                    aria-hidden="true">
                                            </figure>                                            
                                            <meta itemprop="name" content="<?php the_title();?>">
                                       
                                        <?php  if ($checkbox_value) { ?>
                                        </div>
                                            <?php } else { ?>  
                                            </a>   
                                        <?php }; ?>  

                                    </article>
                                    <span class="calorie-preloader">
                                        <span class="calorie-preloader_body"></span>
                                    </span>
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
                <div class="swiper" id="salads-swiper" data-initial="<?php echo $atts['initial_img']; ?>" itemprop="itemList">
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
                                          <?php if ($checkbox_value) { ?>
                                            <div class="calorie-card wl-open-modal" data-postid="<?php echo get_the_ID(); ?>" itemscope 
                                            itemtype="https://schema.org/Thing" data-title="<?php the_title();?>"
                                              data-p="<?php echo $sProtein;?>" data-f="<?php echo $sFats;?>" data-c="<?php echo $sCarbo;?>"
                                            data-kkal="<?php echo $sKkal;?>" data-serving="<?php echo $sServing;?>">

                                            <?php } else { ?>  

                                            <a href="<?php the_permalink();?>" class="calorie-card" itemprop="url" itemscope
                                            itemtype="https://schema.org/Thing" data-title="<?php the_title();?>"
                                              data-p="<?php echo $sProtein;?>" data-f="<?php echo $sFats;?>" data-c="<?php echo $sCarbo;?>"
                                            data-kkal="<?php echo $sKkal;?>" data-serving="<?php echo $sServing;?>">

                                        <?php }; ?>                                       
                                          
                                            <figure class="calorie-card_image" itemprop="image" itemscope
                                                itemtype="https://schema.org/ImageObject">
                                                <img data-src="<?php echo get_url_image_for_slider(get_the_ID());?>" data-updated="false" src="<?php echo get_template_directory_uri();?>/images/1x1.png" 
                                                    aria-hidden="true">
                                            </figure>                                            
                                            <meta itemprop="name" content="<?php the_title();?>">
                                       
                                        <?php  if ($checkbox_value) { ?>
                                        </div>
                                            <?php } else { ?>  
                                            </a>   
                                        <?php }; ?>  
                                    </article>
                                    <span class="calorie-preloader">
                                        <span class="calorie-preloader_body"></span>
                                    </span>
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
    </section>    
<?php return ob_get_clean(); }
//Зарегистрируем шорткод с тегом [wl-callories-slider]
add_shortcode('wl-callories-slider', 'wl_callories_slider_shortcode');



// Вывод все дополнимтельных полей пользовательского типа записи
add_action('wp_ajax_get_custom_fields', 'get_custom_fields_callback');
add_action('wp_ajax_nopriv_get_custom_fields', 'get_custom_fields_callback');
function get_custom_fields_callback() {
    $post_id = intval($_POST['post_id']);

    // Получаем все мета поля

    $title = get_the_title($post_id);
    $thumbnail_url = get_the_post_thumbnail_url($post_id, 'small'); // Можно указать размер, например 'thumbnail', 'medium', 'large', или 'full'
    
    $calories = get_post_meta($post_id, '_calories', true);     
    
    $protein = get_post_meta($post_id, '_protein', true);
    $fat = get_post_meta($post_id, '_fat', true);
    $carbohydrates = get_post_meta($post_id, '_carbohydrates', true);
   
    $mass = get_post_meta($post_id, '_mass_serving', true);

    $ingredients = get_post_meta($post_id, '_ingredients', true);
    $recipe = get_post_meta($post_id, '_recipe', true);
    
    $meta = get_post_meta($post_id);
    
    if (empty($meta)) {
        echo 'У этого поста нет данных.';
        wp_die();
    }?>
    
    <section class="calorie-slider-modalContent" id="calorie-slider-modalContent" itemscope itemtype="https://schema.org/WebPage">
	<h2 class="visually-hidden"><?php echo $title;?></h2>
	<article class="calorie-slider-modalContent-article">	
        <?php 
        $justify = '';
        $post_type = get_post_type($post_id);
        if($post_type == 'garniry'){$justify='garniry';}
        if($post_type == 'salaty'){$justify='salaty';}
        ?>

        <div class="calorie-slider-modalContent_grid">
    		<div class="calorie-slider-modalContent_image <?php echo $justify;?>"> <!-- ПРАВО ЛЕВО (salad, garnir) -->
	    		<img src="<?php echo $thumbnail_url;?>" alt="<?php echo $title;?>" aria-hidden="true"/>
	    	</div>

		    <div class ="calorie-slider-modalContent_header">
		    	<span class="calorie-slider-modalContent_title"><?php echo $title;?></span>

                <div class="calorie-slider-modalContent_nutrients">
		        	<strong>на 100 г:</strong>
		        	<p><?php echo $calories;?> ккал</p>
			        <p>Белок: <?php echo $protein;?> г</p>
			        <p>Жиры: <?php echo $fat;?> г</p>
			        <p>Углеводы: <?php echo $carbohydrates;?> г</p>                 
		        </div>

                <div class="calorie-slider-modalContent_serving">
			        <span>*размер порции <?php echo $mass;?> г</span>
		        </div>
		    </div>		
        </div>		

		<div class="calorie-slider-modalContent_content">
			<h3>Состав / ингредиенты</h3>
			<?php echo text_to_ul($ingredients);?>
			<h3>Рецепт</h3>
			<p>
                <?php echo $recipe;?>
            </p>
		</div>
	</article>
    </section>
    

    <?php wp_die();
}

// Преобразовуем в список ul li
function text_to_ul( $text ) {
    // Разбиваем текст по переносам строк
    $lines = preg_split('/\r\n|\r|\n/', $text);
    
    // Удаляем пустые строки
    $lines = array_filter( $lines, 'trim' );
    
    // Оборачиваем каждую строку в <li>
    $list_items = array_map( function( $line ) {
        return '<li>' . esc_html( trim( $line ) ) . '</li>';
    }, $lines );
    
    // Объединяем в список
    return '<ul>' . implode( '', $list_items ) . '</ul>';
}