<?php
/**
 * Best Sellers Reloaded v1.1
 *
 * Module Template
 *
 * @package templateSystem
 * @author Alex Clarke (aclarke@ansellandclarke.co.uk)
 * @copyright Copyright 2003-2005 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_modules_best_sellers_reloaded.php 2007-07-22 aclarke $
 */
  $zc_show_best_sellers = false;
  include(DIR_WS_MODULES . zen_get_module_directory(FILENAME_BEST_SELLERS_RELOADED_MODULE));
?>

<!-- bof - Best Sellers Reloaded v1.1 - aclarke - 2007-07-22 -->
<?php if ($zc_show_best_sellers == true) { ?>
<div class="centerBoxWrapper" id="bestSellers">
<?php
/**
 * require the list_box_content template to display the product
 */
  require($template->get_template_dir('tpl_columnar_display.php',DIR_WS_TEMPLATE, $current_page_base,'common'). '/tpl_columnar_display.php');
?>
</div>
<?php } ?>
<!-- eof - Best Sellers Reloaded v1.1 - aclarke - 2007-07-22  -->
