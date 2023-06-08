<!doctype html>
<html class="" <?php language_attributes(); ?> x-data="{
    darkMode: localStorage.getItem('darkMode') || localStorage.setItem('darkMode', 'system'),
    prefersDarkMode: window.matchMedia('(prefers-color-scheme: dark)').matches,
    classList: document.documentElement.classList,
    setClass: function() {
        this.classList.toggle('dark', this.darkMode === 'dark' || (this.darkMode === 'system' && this.prefersDarkMode));
    },
    toggleDarkMode: function() {
        this.darkMode = this.darkMode === 'dark' ? 'light' : 'dark';
        this.setClass();
        localStorage.setItem('darkMode', this.darkMode);
    }
}"
x-init="setClass()"
x-bind:class="{'dark': darkMode === 'dark' || (darkMode === 'system' && prefersDarkMode)}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
  <script defer data-domain="triennale.fr" src="https://plausible.io/js/script.js"></script>
</head>

<body x-cloak <?php body_class('transition-all duration-400 bg-white dark:bg-black text-black dark:text-white'); ?>>
  <?php wp_body_open(); ?>
  <?php do_action('get_header'); ?>

  <div id="app">


    <?php echo view(app('sage.view'), app('sage.data'))->render(); ?>
  </div>

  <?php do_action('get_footer'); ?>
  <?php wp_footer(); ?>
</body>

</html>