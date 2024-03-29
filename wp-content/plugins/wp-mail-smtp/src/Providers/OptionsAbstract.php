<?php

namespace WPMailSMTP\Providers;

use WPMailSMTP\Options;

/**
 * Abstract Class ProviderAbstract to contain common providers functionality.
 *
 * @since 1.0.0
 */
abstract class OptionsAbstract implements OptionsInterface {

	/**
	 * @var string
	 */
	private $logo_url = '';
	/**
	 * @var string
	 */
	private $slug = '';
	/**
	 * @var string
	 */
	private $title = '';
	/**
	 * @var string
	 */
	private $description = '';
	/**
	 * @since 1.6.0
	 *
	 * @var array
	 */
	private $notices = array();
	/**
	 * @since 1.6.0
	 *
	 * @var bool
	 */
	private $recommended = false;
	/**
	 * @since 1.7.0
	 *
	 * @var bool
	 */
	private $disabled = false;
	/**
	 * @var string
	 */
	private $php = WPMS_PHP_VER;
	/**
	 * @var Options
	 */
	protected $options;

	/**
	 * An array with mailer supported setting fields.
	 *
	 * @since 2.3.0
	 *
	 * @var array
	 */
	protected $supports;

	/**
	 * ProviderAbstract constructor.
	 *
	 * @since 1.0.0
	 * @since 2.3.0 Added supports parameter.
	 *
	 * @param array $params The mailer options parameters.
	 */
	public function __construct( $params ) {

		if (
			empty( $params['slug'] ) ||
			empty( $params['title'] )
		) {
			return;
		}

		$this->slug  = sanitize_key( $params['slug'] );
		$this->title = sanitize_text_field( $params['title'] );

		if ( ! empty( $params['description'] ) ) {
			$this->description = wp_kses_post( $params['description'] );
		}

		if ( ! empty( $params['notices'] ) ) {
			foreach ( (array) $params['notices'] as $key => $notice ) {
				$key = sanitize_key( $key );
				if ( empty( $key ) ) {
					continue;
				}

				$notice = wp_kses(
					$notice,
					array(
						'br'     => true,
						'strong' => true,
						'em'     => true,
						'a'      => array(
							'href'   => true,
							'rel'    => true,
							'target' => true,
						),
					)
				);
				if ( empty( $notice ) ) {
					continue;
				}

				$this->notices[ $key ] = $notice;
			}
		}

		if ( isset( $params['recommended'] ) ) {
			$this->recommended = (bool) $params['recommended'];
		}
		if ( isset( $params['disabled'] ) ) {
			$this->disabled = (bool) $params['disabled'];
		}

		if ( ! empty( $params['php'] ) ) {
			$this->php = sanitize_text_field( $params['php'] );
		}

		if ( ! empty( $params['logo_url'] ) ) {
			$this->logo_url = esc_url_raw( $params['logo_url'] );
		}

		$this->supports = ( ! empty( $params['supports'] ) ) ? $params['supports'] : $this->get_supports_defaults();

		$this->options = new Options();
	}

	/**
	 * @inheritdoc
	 */
	public function get_logo_url() {
		return apply_filters( 'wp_mail_smtp_providers_provider_get_logo_url', $this->logo_url, $this );
	}

	/**
	 * @inheritdoc
	 */
	public function get_slug() {
		return apply_filters( 'wp_mail_smtp_providers_provider_get_slug', $this->slug, $this );
	}

	/**
	 * @inheritdoc
	 */
	public function get_title() {
		return apply_filters( 'wp_mail_smtp_providers_provider_get_title', $this->title, $this );
	}

	/**
	 * @inheritdoc
	 */
	public function get_description() {
		return apply_filters( 'wp_mail_smtp_providers_provider_get_description', $this->description, $this );
	}

	/**
	 * Some mailers may display a notice above its options.
	 *
	 * @since 1.6.0
	 *
	 * @param string $type
	 *
	 * @return string
	 */
	public function get_notice( $type ) {

		$type = sanitize_key( $type );

		return apply_filters( 'wp_mail_smtp_providers_provider_get_notice', isset( $this->notices[ $type ] ) ? $this->notices[ $type ] : '', $this );
	}

	/**
	 * @inheritdoc
	 */
	public function get_php_version() {
		return apply_filters( 'wp_mail_smtp_providers_provider_get_php_version', $this->php, $this );
	}

	/**
	 * @inheritdoc
	 */
	public function display_options() {
		?>

		<!-- SMTP Host -->
		<div id="wp-mail-smtp-setting-row-<?php echo esc_attr( $this->get_slug() ); ?>-host" class="wp-mail-smtp-setting-row wp-mail-smtp-setting-row-text wp-mail-smtp-clear">
			<div class="wp-mail-smtp-setting-label">
				<label for="wp-mail-smtp-setting-<?php echo esc_attr( $this->get_slug() ); ?>-host"><?php esc_html_e( 'SMTP Host', 'wp-mail-smtp' ); ?></label>
			</div>
			<div class="wp-mail-smtp-setting-field">
				<input name="wp-mail-smtp[<?php echo esc_attr( $this->get_slug() ); ?>][host]" type="text"
					value="<?php echo esc_attr( $this->options->get( $this->get_slug(), 'host' ) ); ?>"
					<?php echo $this->options->is_const_defined( $this->get_slug(), 'host' ) ? 'disabled' : ''; ?>
					id="wp-mail-smtp-setting-<?php echo esc_attr( $this->get_slug() ); ?>-host" spellcheck="false"
				/>
			</div>
		</div>

		<!-- SMTP Encryption -->
		<div id="wp-mail-smtp-setting-row-<?php echo esc_attr( $this->get_slug() ); ?>-encryption" class="wp-mail-smtp-setting-row wp-mail-smtp-setting-row-radio wp-mail-smtp-clear">
			<div class="wp-mail-smtp-setting-label">
				<label><?php esc_html_e( 'Encryption', 'wp-mail-smtp' ); ?></label>
			</div>
			<div class="wp-mail-smtp-setting-field">

				<label for="wp-mail-smtp-setting-<?php echo esc_attr( $this->get_slug() ); ?>-enc-none">
					<input type="radio" id="wp-mail-smtp-setting-<?php echo esc_attr( $this->get_slug() ); ?>-enc-none"
						name="wp-mail-smtp[<?php echo esc_attr( $this->get_slug() ); ?>][encryption]" value="none"
						<?php echo $this->options->is_const_defined( $this->get_slug(), 'encryption' ) ? 'disabled' : ''; ?>
						<?php checked( 'none', $this->options->get( $this->get_slug(), 'encryption' ) ); ?>
					/>
					<?php esc_html_e( 'None', 'wp-mail-smtp' ); ?>
				</label>

				<label for="wp-mail-smtp-setting-<?php echo esc_attr( $this->get_slug() ); ?>-enc-ssl">
					<input type="radio" id="wp-mail-smtp-setting-<?php echo esc_attr( $this->get_slug() ); ?>-enc-ssl"
						name="wp-mail-smtp[<?php echo esc_attr( $this->get_slug() ); ?>][encryption]" value="ssl"
						<?php echo $this->options->is_const_defined( $this->get_slug(), 'encryption' ) ? 'disabled' : ''; ?>
						<?php checked( 'ssl', $this->options->get( $this->get_slug(), 'encryption' ) ); ?>
					/>
					<?php esc_html_e( 'SSL', 'wp-mail-smtp' ); ?>
				</label>

				<label for="wp-mail-smtp-setting-<?php echo esc_attr( $this->get_slug() ); ?>-enc-tls">
					<input type="radio" id="wp-mail-smtp-setting-<?php echo esc_attr( $this->get_slug() ); ?>-enc-tls"
						name="wp-mail-smtp[<?php echo esc_attr( $this->get_slug() ); ?>][encryption]" value="tls"
						<?php echo $this->options->is_const_defined( $this->get_slug(), 'encryption' ) ? 'disabled' : ''; ?>
						<?php checked( 'tls', $this->options->get( $this->get_slug(), 'encryption' ) ); ?>
					/>
					<?php esc_html_e( 'TLS', 'wp-mail-smtp' ); ?>
				</label>

				<p class="desc">
					<?php esc_html_e( 'For most servers TLS is the recommended option. If your SMTP provider offers both SSL and TLS options, we recommend using TLS.', 'wp-mail-smtp' ); ?>
				</p>
			</div>
		</div>

		<!-- SMTP Port -->
		<div id="wp-mail-smtp-setting-row-<?php echo esc_attr( $this->get_slug() ); ?>-port" class="wp-mail-smtp-setting-row wp-mail-smtp-setting-row-number wp-mail-smtp-clear">
			<div class="wp-mail-smtp-setting-label">
				<label for="wp-mail-smtp-setting-<?php echo esc_attr( $this->get_slug() ); ?>-port"><?php esc_html_e( 'SMTP Port', 'wp-mail-smtp' ); ?></label>
			</div>
			<div class="wp-mail-smtp-setting-field">
				<input name="wp-mail-smtp[<?php echo esc_attr( $this->get_slug() ); ?>][port]" type="number"
					value="<?php echo esc_attr( $this->options->get( $this->get_slug(), 'port' ) ); ?>"
					<?php echo $this->options->is_const_defined( $this->get_slug(), 'port' ) ? 'disabled' : ''; ?>
					id="wp-mail-smtp-setting-<?php echo esc_attr( $this->get_slug() ); ?>-port" class="small-text" spellcheck="false"
				/>
			</div>
		</div>

		<!-- PHPMailer SMTPAutoTLS -->
		<div id="wp-mail-smtp-setting-row-<?php echo esc_attr( $this->get_slug() ); ?>-autotls" class="wp-mail-smtp-setting-row wp-mail-smtp-setting-row-checkbox-toggle wp-mail-smtp-clear <?php echo $this->options->is_const_defined( $this->get_slug(), 'encryption' ) || 'tls' === $this->options->get( $this->get_slug(), 'encryption' ) ? 'inactive' : ''; ?>">
			<div class="wp-mail-smtp-setting-label">
				<label for="wp-mail-smtp-setting-<?php echo esc_attr( $this->get_slug() ); ?>-autotls"><?php esc_html_e( 'Auto TLS', 'wp-mail-smtp' ); ?></label>
			</div>
			<div class="wp-mail-smtp-setting-field">
				<label for="wp-mail-smtp-setting-<?php echo esc_attr( $this->get_slug() ); ?>-autotls">
					<input type="checkbox" id="wp-mail-smtp-setting-<?php echo esc_attr( $this->get_slug() ); ?>-autotls"
						name="wp-mail-smtp[<?php echo esc_attr( $this->get_slug() ); ?>][autotls]" value="yes"
						<?php echo $this->options->is_const_defined( $this->get_slug(), 'autotls' ) ? 'disabled' : ''; ?>
						<?php checked( true, (bool) $this->options->get( $this->get_slug(), 'autotls' ) ); ?>
					/>
					<span class="wp-mail-smtp-setting-toggle-switch"></span>
					<span class="wp-mail-smtp-setting-toggle-checked-label"><?php esc_html_e( 'On', 'wp-mail-smtp' ); ?></span>
					<span class="wp-mail-smtp-setting-toggle-unchecked-label"><?php esc_html_e( 'Off', 'wp-mail-smtp' ); ?></span>
				</label>
				<p class="desc">
					<?php esc_html_e( 'By default TLS encryption is automatically used if the server supports it, which is recommended. In some cases, due to server misconfigurations, this can cause issues and may need to be disabled.', 'wp-mail-smtp' ); ?>
				</p>
			</div>
		</div>

		<!-- SMTP Authentication -->
		<div id="wp-mail-smtp-setting-row-<?php echo esc_attr( $this->get_slug() ); ?>-auth" class="wp-mail-smtp-setting-row wp-mail-smtp-setting-row-checkbox-toggle wp-mail-smtp-clear">
			<div class="wp-mail-smtp-setting-label">
				<label for="wp-mail-smtp-setting-<?php echo esc_attr( $this->get_slug() ); ?>-auth"><?php esc_html_e( 'Authentication', 'wp-mail-smtp' ); ?></label>
			</div>
			<div class="wp-mail-smtp-setting-field">
				<label for="wp-mail-smtp-setting-<?php echo esc_attr( $this->get_slug() ); ?>-auth">
					<input type="checkbox" id="wp-mail-smtp-setting-<?php echo esc_attr( $this->get_slug() ); ?>-auth"
						name="wp-mail-smtp[<?php echo esc_attr( $this->get_slug() ); ?>][auth]" value="yes"
						<?php echo $this->options->is_const_defined( $this->get_slug(), 'auth' ) ? 'disabled' : ''; ?>
						<?php checked( true, (bool) $this->options->get( $this->get_slug(), 'auth' ) ); ?>
					/>
					<span class="wp-mail-smtp-setting-toggle-switch"></span>
					<span class="wp-mail-smtp-setting-toggle-checked-label"><?php esc_html_e( 'On', 'wp-mail-smtp' ); ?></span>
					<span class="wp-mail-smtp-setting-toggle-unchecked-label"><?php esc_html_e( 'Off', 'wp-mail-smtp' ); ?></span>
				</label>
			</div>
		</div>

		<!-- SMTP Username -->
		<div id="wp-mail-smtp-setting-row-<?php echo esc_attr( $this->get_slug() ); ?>-user" class="wp-mail-smtp-setting-row wp-mail-smtp-setting-row-text wp-mail-smtp-clear <?php echo ! $this->options->is_const_defined( $this->get_slug(), 'auth' ) && ! $this->options->get( $this->get_slug(), 'auth' ) ? 'inactive' : ''; ?>">
			<div class="wp-mail-smtp-setting-label">
				<label for="wp-mail-smtp-setting-<?php echo esc_attr( $this->get_slug() ); ?>-user"><?php esc_html_e( 'SMTP Username', 'wp-mail-smtp' ); ?></label>
			</div>
			<div class="wp-mail-smtp-setting-field">
				<input name="wp-mail-smtp[<?php echo esc_attr( $this->get_slug() ); ?>][user]" type="text"
					value="<?php echo esc_attr( $this->options->get( $this->get_slug(), 'user' ) ); ?>"
					<?php echo $this->options->is_const_defined( $this->get_slug(), 'user' ) ? 'disabled' : ''; ?>
					id="wp-mail-smtp-setting-<?php echo esc_attr( $this->get_slug() ); ?>-user" spellcheck="false" autocomplete="new-password"
				/>
			</div>
		</div>

		<!-- SMTP Password -->
		<div id="wp-mail-smtp-setting-row-<?php echo esc_attr( $this->get_slug() ); ?>-pass" class="wp-mail-smtp-setting-row wp-mail-smtp-setting-row-password wp-mail-smtp-clear <?php echo ! $this->options->is_const_defined( $this->get_slug(), 'auth' ) && ! $this->options->get( $this->get_slug(), 'auth' ) ? 'inactive' : ''; ?>">
			<div class="wp-mail-smtp-setting-label">
				<label for="wp-mail-smtp-setting-<?php echo esc_attr( $this->get_slug() ); ?>-pass"><?php esc_html_e( 'SMTP Password', 'wp-mail-smtp' ); ?></label>
			</div>
			<div class="wp-mail-smtp-setting-field">
				<?php if ( $this->options->is_const_defined( $this->get_slug(), 'pass' ) ) : ?>
					<input type="text" value="*************" disabled id="wp-mail-smtp-setting-<?php echo esc_attr( $this->get_slug() ); ?>-pass"/>

					<?php $this->display_const_set_message( 'WPMS_SMTP_PASS' ); ?>

					<p class="desc">
						<?php
						printf(
							/* translators: %s - constant name: WPMS_SMTP_PASS. */
							esc_html__( 'To change the password you need to change the value of the constant there: %s', 'wp-mail-smtp' ),
							'<code>define( \'WPMS_SMTP_PASS\', \'your_old_password\' );</code>'
						);
						?>
						<br>
						<?php
						printf(
							/* translators: %1$s - wp-config.php file, %2$s - WPMS_ON constant name. */
							esc_html__( 'If you want to disable the use of constants, find in %1$s file the constant %2$s and turn if off:', 'wp-mail-smtp' ),
							'<code>wp-config.php</code>',
							'<code>WPMS_ON</code>'
						);
						?>
					</p>
					<pre>
						define( 'WPMS_ON', false );
					</pre>
					<p class="desc">
						<?php esc_html_e( 'All the defined constants will stop working and you will be able to change all the values on this page.', 'wp-mail-smtp' ); ?>
					</p>
				<?php else : ?>
					<input name="wp-mail-smtp[<?php echo esc_attr( $this->get_slug() ); ?>][pass]" type="password"
						value="<?php echo esc_attr( $this->options->get( $this->get_slug(), 'pass' ) ); ?>"
						id="wp-mail-smtp-setting-<?php echo esc_attr( $this->get_slug() ); ?>-pass" spellcheck="false" autocomplete="new-password"
					/>
					<p class="desc">
						<?php esc_html_e( 'The password is encrypted in the database, but for improved security we recommend using your site\'s WordPress configuration file to set your password.', 'wp-mail-smtp' ); ?>
						<br>
						<a href="https://wpmailsmtp.com/docs/how-to-secure-smtp-settings-by-using-constants/" target="_blank" rel="noopener noreferrer">
							<strong><?php esc_html_e( 'Learn More', 'wp-mail-smtp' ); ?></strong>
						</a>
					</p>
				<?php endif; ?>
			</div>
		</div>

		<?php
	}

	/**
	 * Whether this mailer is recommended or not.
	 *
	 * @since 1.6.0
	 *
	 * @return bool
	 */
	public function is_recommended() {

		return (bool) apply_filters( 'wp_mail_smtp_providers_provider_is_recommended', $this->recommended, $this );
	}

	/**
	 * Whether this mailer is disabled or not.
	 * Used for displaying Pro mailers inside Lite plugin.
	 *
	 * @since 1.7.0
	 *
	 * @return bool
	 */
	public function is_disabled() {

		return (bool) apply_filters( 'wp_mail_smtp_providers_provider_is_disabled', $this->disabled, $this );
	}

	/**
	 * Check whether we can use this provider based on the PHP version.
	 * Valid for those, that use SDK.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function is_php_correct() {
		return version_compare( phpversion(), $this->php, '>=' );
	}

	/**
	 * Display a helpful message to those users, that are using an outdated version of PHP,
	 * which is not supported by the currently selected Provider.
	 *
	 * @since 1.0.0
	 */
	protected function display_php_warning() {
		?>

		<blockquote>
			<?php
			printf(
				/* translators: %1$s - Provider name; %2$s - PHP version required by Provider; %3$s - current PHP version. */
				esc_html__( '%1$s requires PHP %2$s to work and does not support your current PHP version %3$s. Please contact your host and request a PHP upgrade to the latest one.', 'wp-mail-smtp' ),
				esc_html( $this->get_title() ),
				esc_html( $this->php ),
				esc_html( phpversion() )
			);
			?>
			<br>
			<?php esc_html_e( 'Meanwhile you can switch to some other mailers.', 'wp-mail-smtp' ); ?>
		</blockquote>

		<?php
	}

	/**
	 * Display a helpful message to those users, that are using an outdated version of PHP,
	 * which is not supported by the currently selected Provider.
	 *
	 * @since 1.5.0
	 */
	protected function display_ssl_warning() {
		?>

		<blockquote>
			<?php
			printf(
				wp_kses( /* translators: %s - Provider name */
					__( '%s requires an SSL certificate, and so is not currently compatible with your site. Please contact your host to request a SSL certificate, or check out <a href="https://www.wpbeginner.com/wp-tutorials/how-to-add-ssl-and-https-in-wordpress/" target="_blank">WPBeginner\'s tutorial on how to set up SSL</a>.', 'wp-mail-smtp' ),
					[
						'a' => [
							'href'   => [],
							'target' => [],
						],
					]
				),
				esc_html( $this->get_title() )
			);
			?>
			<br>
			<br>
			<?php esc_html_e( 'If you\'d prefer not to set up SSL, or need an SMTP solution in the meantime, please select a different mailer option.', 'wp-mail-smtp' ); ?>
		</blockquote>

		<?php
	}

	/**
	 * Display a message of a constant that was set inside wp-config.php file.
	 *
	 * @since 1.5.0
	 *
	 * @param string $constant Constant name.
	 */
	protected function display_const_set_message( $constant ) {
		?>

		<p class="desc">
			<?php
			printf( /* translators: %1$s - constant that was used; %2$s - file where it was used. */
				esc_html__( 'The value of this field was set using a constant %1$s most likely inside %2$s of your WordPress installation.', 'wp-mail-smtp' ),
				'<code>' . esc_attr( $constant ) . '</code>',
				'<code>wp-config.php</code>'
			);
			?>
		</p>

		<?php
	}

	/**
	 * Return the defaults for the mailer supported settings.
	 *
	 * @since 2.3.0
	 *
	 * @return array
	 */
	public function get_supports_defaults() {

		return [
			'from_email'       => true,
			'from_name'        => true,
			'return_path'      => true,
			'from_email_force' => true,
			'from_name_force'  => true,
		];
	}

	/**
	 * Get the mailer supported settings.
	 *
	 * @since 2.3.0
	 *
	 * @return array
	 */
	public function get_supports() {
		return apply_filters( 'wp_mail_smtp_providers_provider_get_supports', $this->supports, $this );
	}
}
