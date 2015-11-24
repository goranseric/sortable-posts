<?php
/**
 * Makes taxonomies sortable
 *
 * @package Sortable Posts For WordPress
 * @category class
 * @author Carlos Rios
 * @version 0.1
 */

class SortablePosts_Taxonomies {

	/**
	 * Stores an array of sortable taxonomies
	 * @var array
	 * @since 0.1
	 */
	public $sortable_taxes = array( 'project_type' );

	/**
	 * Registers the required hooks
	 * @since 0.1
	 * @return [type] [description]
	 */
	public function register_hooks()
	{
		// Only loads if this is a Sortable Posts registered taxonomy
		add_action( 'admin_init', array( $this, 'register_custom_taxonomy_columns' ) );
	}

	/**
	 * Register sortable taxonomy columns
	 * @since 0.1
	 */
	public function register_custom_taxonomy_columns()
	{
		// Add column to each registered taxonomy.
		foreach( (array) $this->sortable_taxes as $tax )
		{
			if( taxonomy_exists( $tax ) )
			{
				// Add column.
				add_filter( "manage_edit-{$tax}_columns", array( 'SortablePosts', 'create_custom_column' ), 10, 1 );

				// Add html to the row.
				add_filter( "manage_{$tax}_custom_column", array( $this, 'manage_custom_taxonomy_column' ), 10, 3 );

				// Remove column sortability for all other columns.
				add_filter( "manage_edit-{$tax}_sortable_columns", array( 'SortablePosts', 'remove_sortable_columns' ) );
			}
		}
	}

	/**
	 * Add html to our custom taxonomy column
	 * @param $output column html output
	 * @param $column specific taxonomy column
	 * @param $term_id id of the current term
	 * @since 0.1
	 */
	public function manage_custom_taxonomy_column( $output, $column, $term_id )
	{
		$term_position = get_term_meta( $term_id, 'term_order', true );

		if( $column == 'sortable-posts-order' ){
			$output .= '<strong class="sortable-posts-order-position">' . $term_position . '</strong>';
		}
		return $output;
	}

}

/**
 * Initiates SortablePosts_Taxonomies
 */
function init_sp_taxonomies()
{
	$object = new SortablePosts_Taxonomies();
	$object->register_hooks();
}

// Get it started
init_sp_taxonomies();