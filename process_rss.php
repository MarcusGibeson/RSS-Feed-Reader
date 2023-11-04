<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RSS Feed Reader</title>
</head>
<body>
    <h1> RSS Feed Reader </h1>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["rss-url"])) {
        $rssURL = $_POST["rss-url"];

        //Validate the RSS feed URL
        if (filter_var($rssURL, FILTER_VALIDATE_URL) === false) {
            echo "<p>Invalid RSS feed URL. Please enter a valid URL. </p>";
        } else {
            //Initialize cURL session
            $ch = curl_init();

            //Set cURL options
            curl_setopt($ch, CURLOPT_URL, $rssURL);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            //Execute cURL session
            $rssData = curl_exec($ch);

            //Close cURL session
            curl_close($ch);

            if($rssData) {
                //Parse the RSS feed
                $rss = simplexml_load_string($rssData);
            }

            if ($rss) {
                echo "<h2>{$rss->channel->title}</h2>";
                foreach ($rss->channel->item as $item) {
                    echo "<h3>{$item->title}</h3>";
                    echo "<p>{$item->description}</p>";
                    echo "<a href='{$item->link}' target='_blank'>Read more</a>";
                }
            } else {
                echo "<p>Unable to fetch or parse the RSS feed.</p>";
            }
        }
    }
    ?>
</body>
</html>