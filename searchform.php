<!-- Search Button Outline Secondary Right -->
<form class="searchform input-group" method="get" action="<?= esc_url(home_url('/')); ?>">
  <input type="text" name="s" class="form-control" placeholder="<?php esc_attr_e('Search', 'bootscore'); ?>" aria-label="<?php esc_attr_e( 'Search', 'bootscore' ); ?>">
  <button type="submit" class="input-group-text btn btn-outline-secondary" aria-label="<?php esc_attr_e( 'Submit search', 'bootscore' ); ?>"><i class="fa-solid fa-magnifying-glass"></i><span class="visually-hidden-focusable">Search</span></button>
</form>
