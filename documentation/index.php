<?php
declare(strict_types=1);

// Session with safer cookie flags (بدون تغییر رفتار)
session_start([
    'cookie_secure'  => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
    'cookie_httponly'=> true,
    'cookie_samesite'=> 'Lax',
]);

// Security headers (طوری ست شده که با منابع فعلی تداخلی نداشته باشد)
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: strict-origin-when-cross-origin');
// CSP منعطف که منابع فعلی (local, use.fontawesome.com, fonts.googleapis.com/gstatic.com) را مجاز می‌کند
header("Content-Security-Policy: default-src 'self'; img-src 'self' data: https:; style-src 'self' https://fonts.googleapis.com 'unsafe-inline'; font-src 'self' https://fonts.gstatic.com data:; script-src 'self' https://use.fontawesome.com; connect-src 'self'; frame-ancestors 'none'; base-uri 'self'; form-action 'self'");

// --- i18n: امن‌سازی کوکی و بارگذاری زبان ---
$langFile   = 'lang/lang_en.php';
$htmlLang   = 'en';
$htmlDir    = 'ltr';

// فقط حروف کوچک انگلیسی مجاز باشد (جلوگیری از Directory Traversal)
if (isset($_COOKIE['lang'])) {
    $cookieLang = strtolower(preg_replace('/[^a-z]/', '', (string)$_COOKIE['lang']));
    if ($cookieLang !== '') {
        $candidate = 'lang/lang_' . $cookieLang . '.php';
        if (is_file($candidate)) {
            $langFile = $candidate;
            // اگر زبان‌های RTL دارید (fa و …)، جهت را RTL کنید
            $htmlLang = $cookieLang;
            $htmlDir  = in_array($cookieLang, ['fa', 'ar', 'ur'], true) ? 'rtl' : 'ltr';
        }
    }
}

require_once $langFile;
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($htmlLang, ENT_QUOTES, 'UTF-8'); ?>" <?= $htmlDir === 'rtl' ? 'dir="rtl"' : '' ?>>

<head>
    <title>BoostPanel - <?= htmlspecialchars($lang['lang_title'] ?? 'Docs', ENT_QUOTES, 'UTF-8'); ?></title>
    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- توضیحات را امن چاپ می‌کنیم -->
    <meta name="description" content="<?= htmlspecialchars($lang['lang_description'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
    <!-- SEO بهینه اما بدون تغییر رفتار -->
    <link rel="canonical" href="https://smm.bizdavar.com/">
    <meta name="robots" content="index,follow">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://smm.bizdavar.com/">
    <meta property="og:title" content="BoostPanel - <?= htmlspecialchars($lang['lang_title'] ?? 'Docs', ENT_QUOTES, 'UTF-8'); ?>">
    <meta property="og:description" content="<?= htmlspecialchars($lang['lang_description'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
    <meta property="og:image" content="/assets/images/og.jpg">

    <link rel="shortcut icon" href="favicon.ico">

    <!-- بهینه‌سازی فونت گوگل (API جدید + preconnect) -->
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">

    <!-- FontAwesome JS (بدون تغییر سورس/ورژن) -->
    <script defer src="https://use.fontawesome.com/releases/v5.8.2/js/all.js" integrity="sha384-DJ25uNYET2XCl5ZF++U8eNxPWqcKohUUBUpKGlNLMchM7q4Wjg2CUpjHLaL8yYPH" crossorigin="anonymous"></script>

    <!-- Global CSS -->
    <link rel="stylesheet" href="assets/plugins/bootstrap/css/bootstrap.min.css">
    <!-- Plugins CSS -->
    <link rel="stylesheet" href="assets/plugins/prism/prism.css">
    <link rel="stylesheet" href="assets/plugins/elegant_font/css/style.css">
    <!-- Theme CSS -->
    <link id="theme-style" rel="stylesheet" href="assets/css/styles.css">
    <?php if ($htmlDir === 'rtl'): ?>
        <!-- اگر استایل RTL دارید، این فایل را ایجاد/پر کنید -->
        <link rel="stylesheet" href="assets/css/rtl.css">
    <?php endif; ?>
</head>

<body class="body-green">
    <div class="page-wrapper">
        <!-- ******Header****** -->
        <header id="header" class="header">
            <div class="container">
                <div class="branding">
                    <h1 class="logo d-flex justify-content-center">
                        <a href="index.php">
                            <img src="assets/images/logo_white.png" alt="image">
                        </a>
                        <ul class="list-inline d-flex justify-content-center ml-3">
                            <!-- لینک‌ها را دست‌نخورده می‌گذاریم تا روال فعلی شما حفظ شود -->
                            <li class="list-inline-item"><a href="english"><img src="assets/images/us.png" alt="image" width="25"></a></li>
                            <li class="list-inline-item"><a href="brazilian"><img src="assets/images/br.png" alt="image" width="25"></a></li>
                        </ul>
                    </h1>
                </div>
                <!--//branding-->
            </div>
            <!--//container-->
        </header>
        <!--//header-->
        <div class="doc-wrapper">
            <div class="container">
                <div id="doc-header" class="doc-header text-center">
                    <h1 class="doc-title"><i class="icon fa fa-paper-plane"></i> <?= htmlspecialchars($lang['lang_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?></h1>
                </div>
                <!--//doc-header-->
                <div class="doc-body row">
                    <div class="doc-content col-md-9 col-12 order-1">
                        <div class="content-inner">

                            <section id="introduction" class="doc-section">
                                <h2 class="section-title"><?= htmlspecialchars($lang['lang_introduction'] ?? '', ENT_QUOTES, 'UTF-8'); ?></h2>
                                <div class="section-block">
                                    <p><?= $lang['lang_subintroduction'] ?? ''; ?></p>

                                    <div class="alert alert-danger mt-3">
                                        <i class="fas fa-exclamation-circle"></i> <?= $lang['lang_recommended_https'] ?? ''; ?>
                                    </div>
                                </div>
                            </section>
                            <!--//doc-section-->

                            <section id="requirements" class="doc-section">
                                <h2 class="section-title"><?= htmlspecialchars($lang['lang_requirements'] ?? '', ENT_QUOTES, 'UTF-8'); ?></h2>
                                <p class="mt-3">
                                    <i class="fas fa-circle-notch text-success"></i> PHP >= 5.6<br>
                                    <i class="fas fa-circle-notch text-success"></i> PHP CURL<br>
                                    <i class="fas fa-circle-notch text-success"></i> PHP OpenSSL<br>
                                    <i class="fas fa-circle-notch text-success"></i> PHP PDO<br>
                                    <i class="fas fa-circle-notch text-success"></i> Mod Rewrite<br>
                                    <i class="fas fa-circle-notch text-success"></i> Mbstring PHP Extension<br>
                                    <i class="fas fa-circle-notch text-success"></i> Allow url fopen On<br>
                                    <i class="fas fa-circle-notch text-success"></i> Zip Extension<br>
                                    <i class="fas fa-circle-notch text-success"></i> Configured Cronjob<br>
                                </p>
                            </section>

                            <section id="installation" class="doc-section">
                                <h2 class="section-title"><?= htmlspecialchars($lang['lang_installation'] ?? '', ENT_QUOTES, 'UTF-8'); ?></h2>
                                <div id="start_installation" class="section-block">
                                    <?= $lang['lang_step_installation'] ?? ''; ?>
                                </div>
                                <!--//section-block-->
                            </section>
                            <!--//doc-section-->

                            <section id="configure_cronjob" class="doc-section">
                                <h2 class="section-title"><?= htmlspecialchars($lang['lang_configure_cronjob'] ?? '', ENT_QUOTES, 'UTF-8'); ?></h2>
                                <div class="section-block">
                                    <p>
                                        <?= $lang['lang_subcronjob'] ?? ''; ?><br><br>
                                        <ol>
                                            <li><?= $lang['lang_cronjob_how_access_token'] ?? ''; ?></li>
                                        </ol>
                                    </p>

                                    <a href="assets/images/cronjob_token.png" target="_blank" loading="lazy">
                                        <img src="assets/images/cronjob_token.png" class="img-fluid rounded" alt="image" loading="lazy">
                                    </a>

                                    <div class="alert alert-info mt-3">
                                        <div class="text-dark">
                                            <strong><?= htmlspecialchars($lang['lang_note'] ?? 'Note', ENT_QUOTES, 'UTF-8'); ?></strong><br>
                                            <?= sprintf($lang['lang_note_cronjob'] ?? '%s', '<img src="assets/images/icon_update_token_cron.png" class="img-fluid" alt="image" loading="lazy">'); ?>
                                        </div>
                                    </div>

                                    <div class="callout-block callout-success mt-3">
                                        <div class="icon-holder">
                                            <i class="fas fa-thumbs-up"></i>
                                        </div>
                                        <div class="content">
                                            <h4 class="callout-title"><?= htmlspecialchars($lang['lang_links_cronjob'] ?? '', ENT_QUOTES, 'UTF-8'); ?></h4><br>
                                            <p>
                                                <code>https://yourwebsite.com/cron/orders?security=<span class="text-danger"><?= htmlspecialchars($lang['lang_your_token_cronjob'] ?? 'TOKEN', ENT_QUOTES, 'UTF-8'); ?></span></code><br><br>
                                                <code>https://yourwebsite.com/cron/subscriptions?security=<span class="text-danger"><?= htmlspecialchars($lang['lang_your_token_cronjob'] ?? 'TOKEN', ENT_QUOTES, 'UTF-8'); ?></span></code><br><br>
                                                <code>https://yourwebsite.com/cron/status_orders?security=<span class="text-danger"><?= htmlspecialchars($lang['lang_your_token_cronjob'] ?? 'TOKEN', ENT_QUOTES, 'UTF-8'); ?></span></code><br><br>
                                                <code>https://yourwebsite.com/cron/status_subscriptions?security=<span class="text-danger"><?= htmlspecialchars($lang['lang_your_token_cronjob'] ?? 'TOKEN', ENT_QUOTES, 'UTF-8'); ?></span></code><br><br>
                                                <code>https://yourwebsite.com/cron/payments_status?security=<span class="text-danger"><?= htmlspecialchars($lang['lang_your_token_cronjob'] ?? 'TOKEN', ENT_QUOTES, 'UTF-8'); ?></span></code>
                                            </p>
                                        </div>
                                    </div>

                                    <h4><?= htmlspecialchars($lang['lang_example_links_cronjob'] ?? '', ENT_QUOTES, 'UTF-8'); ?></h4>
                                    <br>

                                    <a href="assets/images/cronjob_config.png" target="_blank" loading="lazy">
                                        <img src="assets/images/cronjob_config.png" class="img-fluid rounded" alt="image" loading="lazy">
                                    </a>
                                </div>
                            </section>

                            <section id="api_providers" class="doc-section">
                                <h2 class="section-title"><?= htmlspecialchars($lang['lang_api_providers'] ?? '', ENT_QUOTES, 'UTF-8'); ?></h2>
                                <div class="section-block">
                                    <p>
                                        <?= $lang['lang_how_using_api_third'] ?? ''; ?><br><br>
                                        <ol>
                                            <li><?= $lang['lang_go_menu_admin_api_providers'] ?? ''; ?></li>
                                        </ol>

                                        <a href="assets/images/api_providers/api_providers.png" target="_blank" loading="lazy">
                                            <img src="assets/images/api_providers/api_providers.png" class="img-fluid rounded" alt="image" loading="lazy">
                                        </a><br><br>

                                        <h5><?= htmlspecialchars($lang['lang_add_api_provider'] ?? '', ENT_QUOTES, 'UTF-8'); ?></h5><br>

                                        <a href="assets/images/api_providers/add_api_provider.png" target="_blank" loading="lazy">
                                            <img src="assets/images/api_providers/add_api_provider.png" class="img-fluid rounded" alt="image" loading="lazy">
                                        </a><br><br>
                                    </p>

                                    <div class="alert alert-info">
                                        <div class="text-dark">
                                            <strong><?= htmlspecialchars($lang['lang_note'] ?? 'Note', ENT_QUOTES, 'UTF-8'); ?></strong><br>
                                            <?= $lang['lang_note_api_providers'] ?? ''; ?>
                                        </div>
                                    </div>

                                    <div class="callout-block callout-success mt-3">
                                        <div class="icon-holder">
                                            <i class="fas fa-thumbs-up"></i>
                                        </div>
                                        <div class="content">
                                            <h4 class="callout-title"><?= htmlspecialchars($lang['lang_congratulations_text'] ?? '', ENT_QUOTES, 'UTF-8'); ?></h4><br>
                                            <p>
                                                <b>Name</b> - API Name<br>
                                                <b>API Url</b> - Example <em>https://serviceapi.com/api/v2</em><br>
                                                <b>Parameter Type</b> - Select the API parameter correctly <em>(key or api_token)</em><br>
                                                <b>API Key</b> - Get the secret key of the API<br>
                                                <b>Status</b> - Enables and Disables the System API<br><br>
                                                <span class="text-danger"><strong><?= htmlspecialchars($lang['lang_note'] ?? 'Note', ENT_QUOTES, 'UTF-8'); ?>:</strong> <?= $lang['lang_note_success_api_provider'] ?? ''; ?></span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </section>

                            <section id="google_recaptcha" class="doc-section">
                                <h2 class="section-title">Google reCAPTCHA V2</h2>
                                <div class="section-block">
                                    <p>
                                        <?= $lang['lang_subgooglerecaptcha'] ?? ''; ?><br><br>
                                        <ol>
                                            <strong>1.</strong> <?= sprintf($lang['lang_access_google_recaptcha'] ?? '%s', 'https://www.google.com/recaptcha/intro/v3.html'); ?>
                                        </ol>

                                        <a href="assets/images/google_recaptcha/google_recaptcha.png" target="_blank" loading="lazy">
                                            <img src="assets/images/google_recaptcha/google_recaptcha.png" class="img-fluid rounded" alt="image" loading="lazy">
                                        </a><br>

                                        <div class="alert alert-info mt-3">
                                            <div class="text-dark">
                                                <strong><?= htmlspecialchars($lang['lang_note'] ?? 'Note', ENT_QUOTES, 'UTF-8'); ?></strong><br>
                                                <?= $lang['lang_note_google_recaptcha'] ?? ''; ?>
                                            </div>
                                        </div>
                                    </p>

                                    <h5><?= htmlspecialchars($lang['lang_see_screen'] ?? '', ENT_QUOTES, 'UTF-8'); ?></h5><br>

                                    <a href="assets/images/google_recaptcha/register_new_google_recaptcha.png" target="_blank" loading="lazy">
                                        <img src="assets/images/google_recaptcha/register_new_google_recaptcha.png" class="img-fluid rounded" alt="image" loading="lazy">
                                    </a><br><br>

                                    <h5><?= htmlspecialchars($lang['lang_fill_out_form_img_google_recaptcha'] ?? '', ENT_QUOTES, 'UTF-8'); ?></h5><br>

                                    <a href="assets/images/google_recaptcha/fill_out_form_google_recaptcha.png" target="_blank" loading="lazy">
                                        <img src="assets/images/google_recaptcha/fill_out_form_google_recaptcha.png" class="img-fluid rounded" alt="image" loading="lazy">
                                    </a><br><br>

                                    <h5><?= htmlspecialchars($lang['lang_after_click_submit'] ?? '', ENT_QUOTES, 'UTF-8'); ?></h5><br>

                                    <a href="assets/images/google_recaptcha/submit_google_recaptcha.png" target="_blank" loading="lazy">
                                        <img src="assets/images/google_recaptcha/submit_google_recaptcha.png" class="img-fluid rounded" alt="image" loading="lazy">
                                    </a><br><br>

                                    <p>
                                        <ol>
                                            <strong>2.</strong> <?= $lang['lang_access_menu_settings_recaptcha'] ?? ''; ?>
                                        </ol>
                                    </p>

                                    <a href="assets/images/google_recaptcha/config_recaptcha.png" target="_blank" loading="lazy">
                                        <img src="assets/images/google_recaptcha/config_recaptcha.png" class="img-fluid rounded" alt="image" loading="lazy">
                                    </a><br>

                                    <div class="callout-block callout-success mt-3">
                                        <div class="icon-holder">
                                            <i class="fas fa-thumbs-up"></i>
                                        </div>
                                        <div class="content">
                                            <h4 class="callout-title"><?= htmlspecialchars($lang['lang_congratulations_text'] ?? '', ENT_QUOTES, 'UTF-8'); ?></h4><br>
                                            <p><?= $lang['lang_finally_config_recaptcha'] ?? ''; ?></p>
                                        </div>
                                    </div>
                                </div>
                            </section>

                            <section id="filesBoostpanel" class="doc-section">
                                <h2 class="section-title"><?= htmlspecialchars($lang['lang_files_boostpanel'] ?? '', ENT_QUOTES, 'UTF-8'); ?></h2>
                                <div class="section-block">
                                    <p><?= $lang['lang_files'] ?? ''; ?></p>
                                </div>
                            </section>
                        </div>
                    </div>

                    <div class="doc-sidebar col-md-3 col-12 order-0 d-none d-md-flex">
                        <div id="doc-nav" class="doc-nav">
                            <nav id="doc-menu" class="nav doc-menu flex-column sticky">
                                <a class="nav-link scrollto" href="#introduction"><?= htmlspecialchars($lang['lang_introduction'] ?? '', ENT_QUOTES, 'UTF-8'); ?></a>
                                <a class="nav-link scrollto" href="#requirements"><?= htmlspecialchars($lang['lang_requirements'] ?? '', ENT_QUOTES, 'UTF-8'); ?></a>
                                <a class="nav-link scrollto" href="#installation"><?= htmlspecialchars($lang['lang_installation'] ?? '', ENT_QUOTES, 'UTF-8'); ?></a>
                                <nav class="doc-sub-menu nav flex-column">
                                    <a class="nav-link scrollto" href="#start_installation"><?= htmlspecialchars($lang['lang_start_installation'] ?? '', ENT_QUOTES, 'UTF-8'); ?></a>
                                    <a class="nav-link scrollto" href="#configure_cronjob"><?= htmlspecialchars($lang['lang_configure_cronjob'] ?? '', ENT_QUOTES, 'UTF-8'); ?></a>
                                    <a class="nav-link scrollto" href="#api_providers"><?= htmlspecialchars($lang['lang_api_providers'] ?? '', ENT_QUOTES, 'UTF-8'); ?></a>
                                    <a class="nav-link scrollto" href="#google_recaptcha">Google reCAPTCHA V2</a>
                                </nav>
                                <a class="nav-link scrollto" href="#filesBoostpanel"><?= htmlspecialchars($lang['lang_files_boostpanel'] ?? '', ENT_QUOTES, 'UTF-8'); ?></a>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer id="footer" class="footer text-center">
        <div class="container">
            <small class="copyright">
                Template <i class="fas fa-heart"></i> by
                <a href="https://themes.3rdwavemedia.com/" target="_blank" rel="noopener">Xiaoying Riley</a>
            </small>
        </div>
    </footer>

    <!-- Main Javascript -->
    <script src="assets/plugins/jquery-3.3.1.min.js"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/plugins/prism/prism.js"></script>
    <script src="assets/plugins/jquery-scrollTo/jquery.scrollTo.min.js"></script>
    <script src="assets/plugins/stickyfill/dist/stickyfill.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>
