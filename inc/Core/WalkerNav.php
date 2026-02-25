<?php

namespace Listzen\Core;
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Walker_Nav_Menu;

/**
 * Custom WalkerNav class
 */
class WalkerNav extends Walker_Nav_Menu {

	public function start_lvl( &$output, $depth = 0, $args = [] ) {
		$indent  = str_repeat( "\t", $depth );
		$submenu = ( $depth > 0 ) ? ' sub-menu' : '';
		$output  .= "\n$indent<ul class=\"dropdown-menu$submenu depth_$depth\" >\n";
	}

	public function start_el( &$output, $item, $depth = 0, $args = [], $id = 0 ) {
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		$li_attributes = '';
		$class_names   = $value = '';

		$has_item_desc = strlen( $item->description ) > 2;
		$item_image    = $item->listzen_menu_image ?? '';
		$classes       = empty( $item->classes ) ? [] : (array) $item->classes;
		// managing divider: add divider class to an element to get a divider before it.
		$divider_class_position = array_search( 'divider', $classes );
		if ( $divider_class_position !== false ) {
			$output .= "<li class=\"divider\"></li>\n";
			unset( $classes[ $divider_class_position ] );
		}
		$classes[] = ( $args->has_children ) ? 'dropdown' : '';
		$classes[] = ( $item->current || $item->current_item_ancestor ) ? 'active' : '';
		$classes[] = 'menu-item-' . $item->ID;
		$classes[] = $item_image ? 'has-menu-image' : '';
		$classes[] = $has_item_desc ? 'has-description' : '';

		$_mega_menu = get_post_meta( $item->ID, 'listzen_mega_menu', true );
		if ( ! empty( $_mega_menu ) ) {
			$classes[] = $_mega_menu;
		}

		if ( $depth && $args->has_children ) {
			$classes[] = 'dropdown-submenu';
		}

		// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
		$class_names = implode( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
		$class_names = ' class="' . esc_attr( $class_names ) . '"';

		// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
		$id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args );
		$id = strlen( $id ) ? ' id="' . esc_attr( $id ) . '"' : '';

		$output .= $indent . '<li' . $id . $value . $class_names . $li_attributes . '>';

		$has_url    = ( ! empty( $item->url ) && '#' != $item->url ) ? 'has-url' : 'no-url';
		$attributes = ! empty( $item->attr_title ) ? ' title="' . esc_attr( $item->attr_title ) . '"' : '';
		$attributes .= ! empty( $item->target ) ? ' target="' . esc_attr( $item->target ) . '"' : '';
		$attributes .= ! empty( $item->xfn ) ? ' rel="' . esc_attr( $item->xfn ) . '"' : '';
		//$attributes .= ! empty( $item->url ) ? ' href="' . esc_attr( $item->url ) . '"' : '';

		if ( ! empty( $item->url ) ) {
			if ( strpos( $item->url, '/' ) === 0 && strpos( $item->url, '//' ) !== 0 ) {
				$full_url = home_url( $item->url );
			} else {
				$full_url = $item->url;
			}
			$attributes .= ' href="' . esc_url( $full_url ) . '"';
		}

		if ( $args->has_children ) {
			$attributes .= ' class="menu-link dropdown-toggle ' . $has_url . '" data-toggle="dropdown"';
		} else {
			$attributes .= ' class="menu-link ' . $has_url . '"';
		}
		// $attributes .= ( $args->has_children ) ? ' class="dropdown-toggle '.$has_url.'" data-toggle="dropdown"' : '';

		$item_output = $args->before;

		$item_output .= '<a' . $attributes . '>';

		if ( $item_image ) {
			$item_output .= '<img class="menu-image" src="' . esc_url( $item_image ) . '" />';
		}
		$item_output .= "<div class='menu-link'>";
		$item_output .= "<div class='menu-label-wrap'>";
		$item_output .= "<div class='menu-label'>";

		// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
		$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
		// add support for menu item title.
		if ( strlen( $item->attr_title ) > 2 ) {
			$tit_class   = strtolower( $item->attr_title );
			$item_output .= "<span class='tit {$tit_class}'>" . $item->attr_title . "</span>";
		}
		$item_output .= "</div>";

		$caret       = '<i class="rt-icon-angle-small-down"></i>';
		$item_output .= ( ( $depth == 0 || 1 ) && $args->has_children ) ? ' <b class="caret">' . $caret . '</b>' : '';
		$item_output .= "</div>";

		// add support for menu item descriptions.
		if ( $has_item_desc ) {
			$item_output .= '<p class="sub">' . $item->description . '</p>';
		}

		$item_output .= "</div>";
		$item_output .= "</a>";
		$item_output .= $args->after;

		// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

	public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {
		if ( ! $element ) {
			return;
		}

		$id_field = $this->db_fields['id'];

		// display this element
		if ( is_array( $args[0] ) ) {
			$args[0]['has_children'] = ! empty( $children_elements[ $element->$id_field ] );
		} elseif ( is_object( $args[0] ) ) {
			$args[0]->has_children = ! empty( $children_elements[ $element->$id_field ] );
		}

		$cb_args = array_merge( [ &$output, $element, $depth ], $args );
		call_user_func_array( [ $this, 'start_el' ], $cb_args );

		$id = $element->$id_field;

		// descend only when the depth is right and there are childrens for this element
		if ( ( $max_depth == 0 || $max_depth > $depth + 1 ) && isset( $children_elements[ $id ] ) ) {
			foreach ( $children_elements[ $id ] as $child ) {
				if ( ! isset( $newlevel ) ) {
					$newlevel = true;
					// start the child delimiter
					$cb_args = array_merge( [ &$output, $depth ], $args );
					call_user_func_array( [ $this, 'start_lvl' ], $cb_args );
				}
				$this->display_element( $child, $children_elements, $max_depth, $depth + 1, $args, $output );
			}
			unset( $children_elements[ $id ] );
		}

		if ( isset( $newlevel ) && $newlevel ) {
			// end the child delimiter
			$cb_args = array_merge( [ &$output, $depth ], $args );
			call_user_func_array( [ $this, 'end_lvl' ], $cb_args );
		}

		// end this element
		$cb_args = array_merge( [ &$output, $element, $depth ], $args );
		call_user_func_array( [ $this, 'end_el' ], $cb_args );
	}

}
