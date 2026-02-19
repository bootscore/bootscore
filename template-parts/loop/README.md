# Loop templates

> [!IMPORTANT]
> This loop templates are not linked in the theme yet. Templates will be used in Bootscore v7 in `archive.php`, `search.php` and `index.php` instead an own loop in each file. There will be an option for more templates like vertical cards, heroes and a custom blank template. The reason why this files are already here is because bs Grid, bs Isotope and bs Swiper plugins will use this templates as well instead in each plugin shortcode and allows to develop this plugins right now.

### Call the loop

```php
<!-- Loop items  -->
<?php get_template_part('template-parts/loop/cards-horizontal'); ?>
```

<details>
  <summary>archive.php</summary>
  text
</details>

<details>
  <summary>index.php</summary>
text
</details>

<details>
  <summary>search.php</summary>
text
</details>

### To Do

- [ ] Move https://github.com/bootscore/bootscore/blob/main/assets/scss/bootscore/_loop.scss to deprecated
- [ ] Change loop thumbnail image link as in `template-tags.php`
- [ ] Add templates
  - [ ] `cards-horizontal.php`
  - [ ] `cards.php`
  - [ ] `heroes.php`
  - [ ] `custom.php` blank template with an action hook
- [ ] Check for superfluous actions
- [ ] Check filter names
- [ ] Link templates in files
  - [ ] `archive.php`
  - [ ] `index.php`
  - [ ] `search.php`
- [ ] Develop loop plugins
  - [ ] bs Grid > bs Loop?
  - [ ] bs Isotope
  - [ ] bs Swiper
- [ ] Documentation