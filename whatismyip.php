<!-- <pre>
<?php print_r($_SERVER); ?>
</pre>

Your IP address is: <?php echo $_SERVER['REMOTE_ADDR']; ?> -->

<?php

function forwarded_ip() {
  $keys = array(
    // Different possible proxy server key values
    'HTTP_X_FORWARDED_FOR', 
    'HTTP_X_FORWARDED', 
    'HTTP_FORWARDED_FOR', 
    'HTTP_FORWARDED',
    'HTTP_CLIENT_IP', 
    'HTTP_X_CLUSTER_CLIENT_IP', 
  );
  
  // For the key that is set as the proxy server key value, there could be more than one IP address returned so it could be an array of IPs
  // explode array of IP addresses by comma ',' and trim the first IP
  // returns first forwarded IP match it finds (IF it is a valid IP)
  foreach($keys as $key) {
    if(isset($_SERVER[$key])) {
      $ip_array = explode(',', $_SERVER[$key]);
      foreach($ip_array as $ip) {
        $ip = trim($ip);
        if(validate_ip($ip)) {
          return $ip;          
        }
      }
    }
  }
  return '';
}

// Validating that the IP address returned is in valid IP format
// Use filter:  filter_var($variable, $filter, $options)
// Options = flags:  FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE (No Private range or Reserved range IPs)
// returns filtered data or false if it fails
function validate_ip($ip) {
  if(filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
    return false;
  } else {
    return true;
  }
}


$remote_ip = $_SERVER['REMOTE_ADDR'];
$forwarded_ip = forwarded_ip();
  
?>

Remote IP Address: <?php echo $remote_ip; ?><br />
<br />

<?php if($forwarded_ip != '') { ?>
  Forwarded For: <?php echo $forwarded_ip; ?><br />
  <br />
<?php } ?>
