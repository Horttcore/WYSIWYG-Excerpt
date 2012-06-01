<?php
/*
Plugin Name: WYSIWYG Excerpt
Plugin URI: https://github.com/Horttcore/WYSIWYG-Excerpt
Description: WordPress WYSIWYG Editor for post_excerpt
Version: 0.1
Author: Ralf Hortt
Author URI: http://horttcore.de
License: GPL2
*/



/**
 * Security, checks if WordPress is running
 **/
if ( !function_exists('add_action') ) :
	header('Status: 403 Forbidden');
	header('HTTP/1.1 403 Forbidden');
	exit();
endif;



class WYSIWYG_Excerpt {


	/**
	 * Construct
	 *
	 * @construct
	 * @return void
	 * @since 0.1
	 * @author Ralf Hortt
	 **/
	function __construct()
	{
		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_action( 'admin_print_styles-post.php', array( $this, 'enqueue_style' ) );
		add_action( 'admin_print_styles-post-new.php', array( $this, 'enqueue_style' ) );
	}



	/**
	 * Replace excerpt with wysiwyg excerpt
	 *
	 * @return void
	 * @author Ralf Hortt
	 **/
	public function admin_init()
	{
		$post_types = get_post_types();
		
		if ( $post_types ) :
		
			foreach ( $post_types as $post_type ) :
			
				if ( post_type_supports( $post_type, 'excerpt' ) ) :
				
					remove_meta_box( 'postexcerpt', $post_type, 'normal' );
					add_meta_box( 'wysiwyg_excerpt', __( 'Excerpt' ), array( $this, 'meta_box' ), $post_type );
				
				endif;
			
			endforeach;
		
		endif;
	}



	/**
	 * Enqueue CSS
	 *
	 * @access public
	 * @return void
	 * @since 0.1
	 * @author Ralf Hortt
	 **/
	public function enqueue_style()
	{
		wp_enqueue_style( 'wysiwyg-excerpt', plugin_dir_url( __FILE__ ) . 'css/wysiwyg-excerpt.css' );
	}



	/**
	 * Metabox Content
	 *
	 * @access public
	 * @return void
	 * @since 0.1
	 * @author Ralf Hortt
	 **/
	public function meta_box( $post )
	{
		the_editor( html_entity_decode( $post->post_excerpt ), 'excerpt', 'excerpt-prev' );
		?>
		<p><?php _e( 'Excerpts are optional hand-crafted summaries of your content that can be used in your theme. <a href="http://codex.wordpress.org/Excerpt" target="_blank">Learn more about manual excerpts.</a>' ); ?></p>
		<?php
	}



}

$WYSIWYG_Excerpt = new WYSIWYG_Excerpt;