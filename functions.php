<?php
/**
 * Enqueue script and styles for child theme
 */
function woodmart_child_enqueue_styles() {
	wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array( 'woodmart-style' ), woodmart_get_theme_info( 'Version' ) );
}
//add_action( 'wp_enqueue_scripts', 'woodmart_child_enqueue_styles', 10010 );

function my_scripts_and_styles(){

	$cache_buster = '1.0.2'; //date("YmdHi", filemtime( get_stylesheet_directory() . '/css/main.css')); //'1.0.0'
	wp_enqueue_style( 'main-style', get_stylesheet_directory_uri() . '/css/main.css', array( 'woodmart-style' ), $cache_buster, 'all' );

}
add_action( 'wp_enqueue_scripts', 'my_scripts_and_styles', 99999);

// Make postal code optional
add_filter( 'woocommerce_default_address_fields', 'customize_extra_fields', 1000, 1 );
function customize_extra_fields( $address_fields ) {
    $address_fields['postcode']['required'] = false; //Postcode
    return $address_fields;
}

// Hide checkout fields
function reorder_billing_fields($fields) {
    $billing_order = [
        'billing_first_name',
        'billing_last_name',
		    'billing_email',
        'billing_country',
        'billing_address_1',
        'billing_postcode',
        'billing_phone'
    ];

    foreach ($billing_order as $field) {
		if('billing_phone' == $fields['billing'][$field]){
			
		}
        $ordered_fields[$field] = $fields['billing'][$field];
    }

	$ordered_fields['billing_phone']['required'] = false;


    $fields['billing'] = $ordered_fields;

    return $fields;
}

add_filter('woocommerce_checkout_fields', 'reorder_billing_fields');

add_action('wp_footer', function(){
	
	$output = <<<EOT
<script>
  (function (s, e, n, d, er) {
    s['Sender'] = er;
    s[er] = s[er]  function () {
      (s[er].q = s[er].q  []).push(arguments)
    }, s[er].l = 1 * new Date();
    var a = e.createElement(n),
        m = e.getElementsByTagName(n)[0];
    a.async = 1;
    a.src = d;
    m.parentNode.insertBefore(a, m)
  })(window, document, 'script', 'https://cdn.sender.net/accounts_resources/universal.js', 'sender');
  sender('94004659510774')
</script>
EOT;
	echo $output;
});