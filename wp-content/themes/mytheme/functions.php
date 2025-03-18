<?php

require_once "vendor/autoload.php";

use Kucrut\Vite;

add_theme_support('menus');
add_theme_support( 'custom-logo' );

add_action( 'after_setup_theme', function () {
    register_nav_menu( 'primary', 'Primary Menu' );
} );

add_action( 'wp_enqueue_scripts', function (): void {
    Vite\enqueue_asset(
        __DIR__ . '/dist',
        'main.js',
        [
            'handle' => 'mytheme-handle',
            'dependencies' => ['jquery'],
            'css-dependencies' => [],
            'css-media' => 'all',
            'css-only' => false,
            'in-footer' => true,
        ]
    );
} );

add_filter('show_admin_bar', '__return_false');

// translation strings
pll_register_string('chapter', 'chapter');
pll_register_string('menu', 'menu');

function validate_captcha_answer($result, $tag) {
    if ($tag['name'] !== 'captcha') {
        return $result;
    }

    $answer = isset($_POST['captcha']) ? intval($_POST['captcha']) : 0;

    $task = $_POST['captcha_task'] ?? null;

    if (!$task || !$answer) {
        $result->invalidate($tag, pll__('Please answer the captcha.'));
        return $result;
    }

    // Розділяємо питання на дві частини
    $task_parts = explode('+', $task);
    if (count($task_parts) !== 2) {
        $result->invalidate($tag, pll__('Incorrect captcha question.'));
        return $result;
    }

    // Обчислюємо правильну відповідь
    $correct = intval($task_parts[0]) + intval($task_parts[1]);

    // Перевіряємо, чи відповідає введена відповідь
    if ($answer !== $correct) {
        $result->invalidate($tag, pll__('Incorrect answer to the captcha.'));
    }

    return $result;
}
add_filter('wpcf7_validate_text*', 'validate_captcha_answer', 10, 2);

function form_select_shortcode(): bool|string
{
    $lang_prefix = pll_current_language();
    $form_select_title = get_field('form_select_title_'.$lang_prefix, 'options');
    $form_select = get_field('form_select_options', 'options');
    if(!$form_select_title || !$form_select){
        return '';
    }
    ob_start();
    ?>
    <div class="form-select wpcf7-form-control-wrap">
        <a href="#" class="form-select-toggle form-input_point" data-title="<?php echo $form_select_title; ?>">
            <span><?php echo $form_select_title; ?></span>
            <i>
                <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect width="30" height="30" rx="15" fill="#72AAFF"/>
                    <path d="M21.3733 11.3059C21.1853 11.11 20.9304 11 20.6646 11C20.3988 11 20.1439 11.11 19.9559 11.3059L14.994 16.4779L10.0322 11.3059C9.84313 11.1156 9.58992 11.0103 9.3271 11.0127C9.06427 11.015 8.81286 11.1249 8.62701 11.3186C8.44116 11.5124 8.33574 11.7744 8.33345 12.0484C8.33117 12.3223 8.4322 12.5863 8.6148 12.7833L14.2853 18.6941C14.4733 18.89 14.7282 19 14.994 19C15.2598 19 15.5147 18.89 15.7027 18.6941L21.3733 12.7833C21.5612 12.5874 21.6667 12.3217 21.6667 12.0446C21.6667 11.7676 21.5612 11.5019 21.3733 11.3059Z" fill="#F1ECE9"/>
                </svg>
            </i>
        </a>
        <input type="hidden" name="select" class="form-select-input">
        <div class="form-select-list">
            <?php foreach ( $form_select as $item ) : ?>
                <div class="form-select-list-item">
                    <a href="#" class="form-select-option" data-value="<?php echo $item['title_'.$lang_prefix]; ?>">
                        <i><img src="<?php echo $item['icon']; ?>" alt=""></i>
                        <span><?php echo $item['title_'.$lang_prefix]; ?></span>
                        <i>
                            <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10.2719 18.75C10.1291 18.7496 9.98791 18.7197 9.85709 18.6624C9.72628 18.6051 9.60865 18.5214 9.51153 18.4167L4.44903 13.0313C4.25979 12.8296 4.15841 12.561 4.1672 12.2846C4.17599 12.0082 4.29423 11.7466 4.4959 11.5573C4.69758 11.3681 4.96617 11.2667 5.24259 11.2755C5.51901 11.2843 5.78062 11.4025 5.96986 11.6042L10.2615 16.1771L19.0219 6.59378C19.1108 6.48307 19.2214 6.39171 19.3469 6.3253C19.4724 6.2589 19.6102 6.21884 19.7517 6.20761C19.8933 6.19638 20.0356 6.2142 20.17 6.25998C20.3044 6.30576 20.428 6.37853 20.5333 6.47383C20.6385 6.56912 20.7232 6.68493 20.7821 6.81414C20.8409 6.94334 20.8728 7.08322 20.8756 7.22517C20.8784 7.36713 20.8522 7.50817 20.7986 7.63962C20.7449 7.77108 20.665 7.89018 20.5636 7.98961L11.0428 18.4063C10.9466 18.5129 10.8294 18.5985 10.6985 18.6576C10.5676 18.7168 10.426 18.7482 10.2824 18.75H10.2719Z" fill="#72AAFF"/>
                            </svg>
                        </i>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('form_select', 'form_select_shortcode');

add_filter('wpcf7_form_elements', function($content) {
    return do_shortcode($content);
});

add_filter('wpcf7_autop_or_not', '__return_false');