<?php
/**
 * Find the web-host for the current IP address.
 *
 * @see https://en.wikipedia.org/wiki/Autonomous_system_(Internet)
 *
 * @link              https://BrianHenryIE.com
 * @since             1.0.0
 * @package           BrianHenryIE/WC_My_Host
 * @license           GPL v2+
 */

namespace BrianHenryIE\WC_My_Host;

use ipinfo\ipinfo\IPinfo;
use WC_Geolocation;

/**
 * Find the server IP address using WC_Geolocation::get_external_ip_address(),
 * find that IP address's ASN using ipinfo.io's API,
 * return the web-host's name if it is known.
 *
 * Class My_Host
 *
 * @package BrianHenryIE\WC_My_Host
 */
class My_Host {

	/**
	 * API token for ipinfo.io.
	 *
	 * @see https://ipinfo.io/signup
	 *
	 * @var string API token.
	 */
	protected string $ipinfo_api_token;

	/**
	 * $api_token can be provided, or can be defined in wp-config.php as `IPINFO_API_TOKEN`.
	 *
	 * My_Host constructor.
	 *
	 * @param string|null $api_token ipinfo.io, if null will be read from constants.
	 * @throws \Exception
	 */
	public function __construct( ?string $api_token = null ) {

		if ( is_null( $api_token ) && defined( 'IPINFO_API_TOKEN' ) ) {
			$this->ipinfo_api_token = IPINFO_API_TOKEN;
		} elseif ( ! is_null( $api_token ) ) {
			$this->ipinfo_api_token = $api_token;
		} else {
			throw new \Exception();
		}
	}

	/**
	 * Array of substrings => friendly names to search for => return in AS org names.
	 *
	 * @var array<string, string>>
	 */
	protected array $known_hosts = array(
		'Liquid Web'   => 'Liquid Web/Nexcess',
		'DigitalOcean' => 'DigitalOcean',
	);

	/**
	 * Get a web host's friendly name from its AS org name.
	 *
	 * Basically just a "string-contains".
	 *
	 * @param string $asn_org_name The text AS name, probably beginning with the AS number.
	 * @return string|null The friendly web-host name.
	 */
	protected function known_hosts_lookup( string $asn_org_name ): ?string {

		foreach ( $this->known_hosts as $search => $result ) {
			if ( false !== strpos( $asn_org_name, $search ) ) {
				return $result;
			}
		}

		// TODO: Handle other cases, e.g. SiteGround here.

		return null;
	}

	/**
	 * Use the WC_Geolocation class to find the server's external IP address. This finds the true IP address
	 * even behind a CDN (e.g. Cloudflare).
	 * Then uses ipinfo.io API to get the AS org name.
	 * Then uses known_hosts_lookup() to find the friendly name of known web-hosting providers.
	 *
	 * @see WC_Geolocation::get_external_ip_address();
	 *
	 * @param string|null $ip_address An IP address to lookup, or null to return the current server's web-host.
	 * @return string
	 */
	public function get_host_provider( ?string $ip_address = null ): ?string {

		$ip_address = $ip_address ?? WC_Geolocation::get_external_ip_address();

		$asn_org_name = $this->get_ip_as_org_name( $ip_address );

		return $this->known_hosts_lookup( $asn_org_name );
	}

	/**
	 * Return the ASN name for an IP address, using the ipinfo.io API.
	 *
	 * @see IPinfo::getDetails()
	 *
	 * e.g. "AS14061 DigitalOcean, LLC"
	 * e.g. "AS40819 Liquid Web, L.L.C"
	 * e.g. "AS15169 Google LLC"
	 *
	 * @param string $ip_address The IP address to lookup.
	 * @return string The ASN
	 * @throws \ipinfo\ipinfo\IPinfoException
	 */
	protected function get_ip_as_org_name( string $ip_address ): string {

		$access_token = $this->ipinfo_api_token;
		$client       = new IPinfo( $access_token );

		$details = $client->getDetails( $ip_address );

		return $details->org;

	}

}
