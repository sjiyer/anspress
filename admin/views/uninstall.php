<?php
/**
 * Tools page
 *
 * @link https://anspress.io
 * @since 4.0
 * @author Rahul Aryan <support@anspress.io>
 * @package WordPress/AnsPress
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
global $wpdb;
?>

<div class="wrap">
	<?php do_action( 'ap_before_admin_page_title' ) ?>
	<div class="ap-uninstall-warning">
		<?php esc_attr_e( 'If you are unsure about this section please do use any of these options below.', 'anspress-question-answer' ); ?>
	</div>

	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row" valign="top">
					<label><?php esc_attr_e( 'Permanently delete all questions and answers?', 'anspress-question-answer' ); ?></label>
				</th>
				<td>
					<?php
						$total_qa = $wpdb->get_var( "SELECT count(*) FROM $wpdb->posts WHERE post_type='question' OR post_type='answer'" );
					?>
					<a href="#" class="button ap-uninstall-btn" data-id="qa" data-total="<?php echo esc_attr( $total_qa ); ?>"><?php printf( esc_attr__( 'Delete %d Q&A', 'anspress-question-answer' ), $total_qa ); ?></a>
					<p class="description"><?php esc_attr_e( 'Clicking this button will delete all questions and answers data from database', 'anspress-question-answer' ); ?></p>
				</td>
			</tr>
			<tr>
				<th scope="row" valign="top">
					<label><?php esc_attr_e( 'Permanently delete all answers?', 'anspress-question-answer' ); ?></label>
				</th>
				<td>
					<?php
						$total_answers = $wpdb->get_var( "SELECT count(*) FROM $wpdb->posts WHERE post_type='answer'" );
					?>
					<a href="#" class="button ap-uninstall-btn" data-id="answers" data-total="<?php echo esc_attr( $total_answers ); ?>"><?php printf( esc_attr__( 'Delete %d answers', 'anspress-question-answer' ), $total_answers ); ?></a>
					<p class="description"><?php esc_attr_e( 'Clicking this button will delete all answers and its related data from database', 'anspress-question-answer' ); ?></p>
				</td>
			</tr>
			<tr>
				<th scope="row" valign="top">
					<label><?php esc_attr_e( 'Permanently delete all AnsPress user data?', 'anspress-question-answer' ); ?></label>
				</th>
				<td>
					<a href="#" class="button ap-uninstall-btn" data-id="userdata" data-total="1"><?php esc_attr_e( 'Delete all user data', 'anspress-question-answer' ); ?></a>
					<p class="description"><?php esc_attr_e( 'Clicking this button will delete all user data added by AnsPress', 'anspress-question-answer' ); ?></p>
				</td>
			</tr>
			<tr>
				<th scope="row" valign="top">
					<label><?php esc_attr_e( 'Permanently delete all AnsPress options?', 'anspress-question-answer' ); ?></label>
				</th>
				<td>
					<a href="#" class="button ap-uninstall-btn" data-id="options" data-total="1"><?php esc_attr_e( 'Delete all options', 'anspress-question-answer' ); ?></a>
					<p class="description"><?php esc_attr_e( 'Clicking this button will delete all AnsPress options', 'anspress-question-answer' ); ?></p>
				</td>
			</tr>
			<tr>
				<th scope="row" valign="top">
					<label><?php esc_attr_e( 'Permanently delete all AnsPress terms?', 'anspress-question-answer' ); ?></label>
				</th>
				<td>
					<a href="#" class="button ap-uninstall-btn" data-id="terms" data-total="1"><?php esc_attr_e( 'Delete all terms', 'anspress-question-answer' ); ?></a>
					<p class="description"><?php esc_attr_e( 'Clicking this button will delete all AnsPress terms data', 'anspress-question-answer' ); ?></p>
				</td>
			</tr>
		</tbody>
	</table>
</div>

<script type="text/javascript">
	function ap_ajax_uninstall_data(el, done){
		done = done||0;
		var action = jQuery(el).attr('data-id');
		var total = jQuery(el).attr('data-total');
		console.log(total, done);

		jQuery.ajax({
			url: ajaxurl,
			method: 'POST',
			data: { __nonce: '', action: 'ap_uninstall_data', data_type: action },
			success: function(data){
				if(data.done > 0){
					done = done + data.done;
					jQuery(el).attr('data-total', data.total);
					jQuery(el).next().find('span').animate({width: (done/data.total)*100 + '%'}, 300);
					ap_ajax_uninstall_data(el, done);
				}

			}
		});
	}
	jQuery(document).ready(function($){
		$('.ap-uninstall-btn').click(function(e){
			e.preventDefault();
			if (confirm('<?php esc_attr_e( 'Do you wish to proceed? This cannot be undone.', 'anspress-question-answer' ); ?>') == true) {
				ap_ajax_uninstall_data(this);

				if(!$(this).next().is('.ap-progress'))
					$(this).after('<div class="ap-progress"><span></span></div>');
			}
		})
	});
</script>

