<?php
/**
Adds Child_Pages_Widget widget.
Plugin Name:  Child Pages
Plugin URI: http://lefkomedia.com
Description: Lists out the child pages.
Version: 1.1.0
Author: Zach Johnson
Author URI: http://lefkomedia.com
*/

include_once('updater.php');

class Child_Pages_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'child_pages_widget', // Base ID
			__('Child Pages', 'text_domain'), // Name
			array( 'description' => __( 'Lists child pages.', 'text_domain' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );
		$sort = $instance['sort'];
		$sort_order = $instance['sort_order'];

		echo $args['before_widget'];
		
		global $post; 
		/* if ( is_page() && $post->post_parent )
			$childpages = wp_list_pages( 'sort_column=' . $sort . '&sort_order=' . $sort_order . '&title_li=&child_of=' . $post->post_parent . '&echo=0' );
		else
			$childpages = wp_list_pages( 'sort_column=' . $sort . '&title_li=&child_of=' . $post->ID . '&echo=0' ); */
			
		$childpages = wp_list_pages( 'sort_column=' . $sort . '&sort_order=' . $sort_order . '&title_li=&child_of=' . ( is_page() && $post->post_parent ? $post->post_parent : $post->ID ) . '&echo=0' );
		
		if ( ! empty( $title ) && ! empty( $childpages ) )
			echo $args['before_title'] . $title . $args['after_title'];
				
		if ( $childpages )
			$string = '<ul>' . $childpages . '</ul>';
				
		echo $string;
			
		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
			$sort = $instance[ 'sort' ];
			$sort_order = $instance[ 'sort_order' ];
		}
		else {
			$title = __( 'Topics', 'text_domain' );
			$sort = __( 'post_title', 'text_domain' );
			$sort_order = __( 'ASC', 'text_domain' );
		}
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'sort' ); ?>"><?php _e( 'Sort By:' ); ?></label> 
		<select id="<?php echo $this->get_field_id( 'sort' ); ?>" name="<?php echo $this->get_field_name( 'sort' ); ?>">
			<option value="menu_order" <?= ( $sort == 'menu_order' ? 'selected' : '' ) ?>>Menu Order</option>
			<option value="post_title" <?= ( $sort == 'post_title' ? 'selected' : '' ) ?>>Post Title</option>
			<option value="post_date" <?= ( $sort == 'post_date' ? 'selected' : '' ) ?>>Post Date</option>
			<option value="post_author" <?= ( $sort == 'post_author' ? 'selected' : '' ) ?>>Post Author</option>
		</select>
		<select id="<?php echo $this->get_field_id( 'sort_order' ); ?>" name="<?php echo $this->get_field_name( 'sort_order' ); ?>">
			<option value="ASC" <?= ( $sort_order == 'ASC' ? 'selected' : '' ) ?>>ASC</option>
			<option value="DESC" <?= ( $sort_order == 'DESC' ? 'selected' : '' ) ?>>DESC</option>
		</select>
		</p>
		<?php 
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['sort'] = ( ! empty( $new_instance['sort'] ) ) ? strip_tags( $new_instance['sort'] ) : 'name';
		$instance['sort_order'] = ( ! empty( $new_instance['sort_order'] ) ) ? strip_tags( $new_instance['sort_order'] ) : 'ASC';

		return $instance;
	}

} // class Child_Pages_Widget

// register Child_Pages_Widget widget
function register_child_pages_widget() {
    register_widget( 'Child_Pages_Widget' );
}
add_action( 'widgets_init', 'register_child_pages_widget' );