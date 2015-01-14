<?php
/**
 * Best Sellers Reloaded v1.1
 *
 * best_sellers_reloaded module - prepares content for display
 *
 * @package modules
 * @author Alex Clarke (aclarke@ansellandclarke.co.uk)
 * @copyright Copyright 2003-2006 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: best_sellers_reloaded.php 2007-07-22 aclarke $
 */
if (!defined('IS_ADMIN_FLAG')) {
  die('Illegal Access');
}

$zc_show_best_sellers = (((int)SHOW_PRODUCT_INFO_MAIN_BEST_SELLERS) > 0);
if ($zc_show_best_sellers) {
  $max_display_best_sellers = (((int)MAX_DISPLAY_SEARCH_RESULTS_BEST_SELLERS) <= 0) ? 9 : (int)MAX_DISPLAY_SEARCH_RESULTS_BEST_SELLERS;
  
  $from_clause = " FROM "  . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd";
  $where_clause = " WHERE p.products_status = '1' AND p.products_ordered > 0 AND p.products_id = pd.products_id AND pd.language_id = " . (int)$_SESSION['languages_id'];
  $limit_clause = ($max_display_best_sellers <= 0) ? '' : " LIMIT $max_display_best_sellers";
  
  if (BEST_SELLERS_RELOADED_SHOW_OUT_OF_STOCK == 'false') {
    $where_clause .= ' AND p.products_quantity > 0';
    
  }

  if (isset ($current_category_id) && $current_category_id > 0) {
    $from_clause .= ", " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_CATEGORIES . " c";
    $where_clause .= " AND p.products_id = p2c.products_id AND p2c.categories_id = c.categories_id AND " . (int)$current_category_id . " IN (c.categories_id, c.parent_id)";
    
  }
  
  $best_sellers_reloaded_query = "SELECT DISTINCT p.products_id, pd.products_name, p.products_image, p.products_ordered$from_clause$where_clause ORDER BY p.products_ordered desc, pd.products_name$limit_clause";
  $best_sellers_reloaded = $db->Execute ($best_sellers_reloaded_query);
  $num_products_count = $best_sellers_reloaded->RecordCount();
  if ($num_products_count > 0) {
    $best_sellers_columns = (int)SHOW_PRODUCT_INFO_COLUMNS_BEST_SELLERS;
    if ($num_products_count < $best_sellers_columns || $best_sellers_columns == 0) {
      $col_width = floor (100 / $num_products_count);
      
    } else {
      $col_width = floor (100 / $best_sellers_columns);
      
    }
 
    $row = 0;
    $col = 0;
    $list_box_contents = array();
    $title = '';

    while (!$best_sellers_reloaded->EOF) {
      $products_price = zen_get_products_display_price ($best_sellers_reloaded->fields['products_id']);

      $list_box_contents[$row][$col] = array ('params' =>'class="centerBoxContentsBestSellers centeredContent back"' . ' ' . 'style="width:' . $col_width . '%;"',
      'text' => (($best_sellers_reloaded->fields['products_image'] == '' and PRODUCTS_IMAGE_NO_IMAGE_STATUS == 0) ? '' : '<a href="' . zen_href_link (zen_get_info_page ($best_sellers_reloaded->fields['products_id']), 'products_id=' . $best_sellers_reloaded->fields['products_id']) . '">' . zen_image (DIR_WS_IMAGES . $best_sellers_reloaded->fields['products_image'], $best_sellers_reloaded->fields['products_name'], IMAGE_BEST_SELLERS_LISTING_WIDTH, IMAGE_BEST_SELLERS_LISTING_HEIGHT) . '</a><br />') . '<a href="' . zen_href_link (zen_get_info_page ($best_sellers_reloaded->fields['products_id']), 'products_id=' . $best_sellers_reloaded->fields['products_id']) . '">' . $best_sellers_reloaded->fields['products_name'] . '</a><br />' . $products_price);

      $col++;
      if ($col > ($best_sellers_columns - 1)) {
        $col = 0;
        $row++;
        
      }
      $best_sellers_reloaded->MoveNext ();
      
    }

    if ($num_products_count) {
      if (isset ($new_products_category_id) && $new_products_category_id != 0) {
        $category_title = zen_get_categories_name ((int)$new_products_category_id);
        $title = '<h2 class="centerBoxHeading">' . TABLE_HEADING_BEST_SELLERS . ($category_title != '' ? ' - ' . $category_title : '') . '</h2>';
        
      } else {
        $title = '<h2 class="centerBoxHeading">' . TABLE_HEADING_BEST_SELLERS . '</h2>';
        
      }
      $zc_show_best_sellers = true;
      
    }
  }
}