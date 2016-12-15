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

      $yesterday = new DateTime();
      $yesterday->sub(new DateInterval('P1D'));

      $yesterday_pageviews = pageviewsForDates( $analytics, $profileId, $yesterday->format('Y-m-d'), $yesterday->format('Y-m-d') );

      $yesterday->sub(new DateInterval('P1D'));

      $lastweek = new DateTime();
      $lastweek->sub(new DateInterval('P8D'));

      $lastweek_pageviews = pageviewsForDates( $analytics, $profileId, $lastweek->format('Y-m-d'), $lastweek->format('Y-m-d') );

      $message = "*DAILY DIGEST*\n Yesterday (" . $yesterday->format('l') . "), we had <https://www.google.com/analytics/web/?hl=en#report/visitors-overview/" . GOOGLE_ANALYTICS_WEB_ID . "/%3F_u.date00%3D" . $yesterday->format('Ymd') . "%26_u.date01%3D" . $yesterday->format('Ymd') . "%26overview-graphOptions.selected%3Danalytics.nthHour/|" . number_format($yesterday_pageviews) . " pageviews>. Last " . $lastweek->format('l') . ", we had <https://www.google.com/analytics/web/?hl=en#report/visitors-overview/" . GOOGLE_ANALYTICS_WEB_ID . "/%3F_u.date00%3D" . $lastweek->format('Ymd') . "%26_u.date01%3D" . $lastweek->format('Ymd') . "%26overview-graphOptions.selected%3Danalytics.nthHour/|" . number_format($lastweek_pageviews) . " pageviews> (" . ($yesterday_pageviews-$lastweek_pageviews > 0 ? "+" : "-" ) . number_format($yesterday_pageviews-$lastweek_pageviews) . ").";
      // Step 4. Output the results. Add your custom channels here
      slackMessage($message, "#analytics");
    }

  } catch (apiServiceException $e) {
    // Error from the API.
    print 'There was an API error : ' . $e->getCode() . ' : ' . $e->getMessage();

  } catch (Exception $e) {
    print 'There wan a general error : ' . $e->getMessage();
  }
}

function pageviewsForDates(&$analytics, $profileId, $start_date, $end_date) {
   $result = $analytics->data_ga->get(
     'ga:' . $profileId,
     $start_date,
     $end_date,
     'ga:pageviews'
     );

   return $result->rows[0][0];
}


?>
