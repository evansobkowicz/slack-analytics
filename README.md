# Google Analytics Slack Notifications

This repository was originally cloned from <a href="https://github.com/BoingBoing/slack-analytics">BoingBoing/slack-analytics</a> because they haven't updated their code since October 2014 and have not responded to issues and pull requests.

## Setup

###Enable the Google Analytics API
1. Go to the <a href="https://console.developers.google.com">Google Developers Console.</a>
2. Select a project, or create a new one.
3. In the sidebar on the left, expand **APIs & auth**. Next, click **APIs**. Select the Enabled APIs link in the API section to see a list of all your enabled APIs. Make sure that the **Analytics API** is on the list of enabled APIs. If you have not enabled it add Analytics API from the list of APIs, then select the **Enable API** button.
4. In the sidebar on the left, select **Credentials**.

###Create a client ID
From the Credentials page, click **Create new Client ID**  to create your OAuth 2.0 credentials.

1. For the *APPLICATION TYPE* select **Service account.**
2. A JSON key will be downloaded to your computer.  This is not needed.
3. Select **Generate new P12 key**
4. Save the newly downloaded file as "privatekey.p12"
5. Upload privatekey.p12 to the slack-analytics folder on your server
6. Verify the server has read access to the privatekey.p12 file and change permissions if needed.

###Add the service account to Google Analytics
The newly created service account will have an email address, <projectId>-<uniqueId>@developer.gserviceaccount.com; Use this email address to add a user to the Google analytics account you want to access via the API.

###Create a Slack webhook
<a href="https://yourslack.slack.com/services/new/incoming-webhook">https://yourslack.slack.com/services/new/incoming-webhook</a>

###Edit setup.php
Edit setup.php and add in your information


## dailydigest.php

Reports daily pageviews in thousands for the previous day to #general, with a comparison against the same day in the previous week. Includes links to the two days' data in Google Analytics.

**Example Output**

    DAILY DIGEST: Yesterday (Wednesday), we did ###k pageviews. Last Wednesday, we did ###k pageviews (+##k).

**Usage**

Set up a cronjob to run dailydigest.php a little after noon EST. This is when Google Analytics has generally completed its update. For sites with less activity, you may be able to run it at other times of the day.

**Example cron entry**

    0 15 * * * php /path/to/dailydigest.php >> /tmp/crontab.log


## toppostsrealtime.php

Reports the current real time stats the top URLs in #analytics. Excludes the root URL. Provides handy links to each URL and their individual real time statistics pages in Google Analytics.

**Example Output**

    Current users on the site: ####

    Top posts:

    ###    brad-pitt-louie-c-k-and-zac
    ##    a-ship-launch-goes-terribly-wr
    ##    recaptioning-new-yor
    ##    u-s-senator-to-internet-provi
    ##    watch-moron-vs-metal-gate
    ##    watch-photo-math-a-smartphon

**Example cron entry**

    */15 * * * * php /path/slack-analytics/toppostsrealtime.php
    0 15 * * * php /path/slack-analytics/dailydigest.php
