<?php


error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once('setup.php');
include_once('functions.php');


createDigest($analytics);

function createDigest(&$analytics) {
  try {

    // Step 2. Get the user's first view (profile) ID.
    $profileId = GOOGLE_ANALYTICS_PROFILE_ID;

    if (isset($profileId)) {

      $totalActiveUsers = totalActiveUsers($analytics, $profileId);

      $pagePaths = pagePath($analytics, $profileId);

      $pagePaths = array_reverse($pagePaths);

      $topPosts = '';
      $count = 0;

      for ($count = 0; $count <= 6; $count++) {
	      if ($pagePaths[$count] != null) {
        	if ( strcmp($pagePaths[$count][0], '/') !== 0 ) {
	        	$topPosts .= "\n<https://www.google.com/analytics/web/?hl=en#realtime/rt-content/".GOOGLE_ANALYTICS_WEB_ID."/%3Ffilter.list%3D10%3D%3D".urlencode($pagePaths[$count][0])."|".$pagePaths[$count][1].">\t<".YOUR_DOMAIN.$pagePaths[$count][0]."|".str_replace( ".html", "", preg_replace('/\/\d+\/\d+\/\d+\//', '', $pagePaths[$count][0]) ).">";
        	}
        }
      }

			if ($topPosts == '') {
				$message = "*REALTIME*\nCurrently no active users. Share a story!";
			} else {
				$message = "*REALTIME*\n Active Users:\t *<https://www.google.com/analytics/web/?hl=en#realtime/rt-overview/".GOOGLE_ANALYTICS_WEB_ID."/|$totalActiveUsers>* \n" . $topPosts;
			}

      // Step 4. Output the results.
      slackMessage($message, "#analytics");
    }

  } catch (apiServiceException $e) {
    // Error from the API.
    print 'There was an API error : ' . $e->getCode() . ' : ' . $e->getMessage();

  } catch (Exception $e) {
    print 'There was a general error : ' . $e->getMessage();
  }
}

function totalActiveUsers(&$analytics, $profileId) {
   $result = $analytics->data_realtime->get(
     'ga:' . $profileId,
     'rt:activeUsers'
     );

   return $result->rows[0][0];
}

function pagePath(&$analytics, $profileId) {
   $result = $analytics->data_realtime->get(
     'ga:' . $profileId,
     'rt:activeUsers',
     array(
        'sort' => 'rt:activeUsers',
        'dimensions' => 'rt:pagePath'
      )
     );

   return $result->rows;
}


?>
