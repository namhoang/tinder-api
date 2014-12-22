# Tinder API
This is a simple wrapper around the Tinder API.

## Example usage to retrieve recommendations and "like" the users.

````php

$tinderApi = new TinderApi('REPLACE_WITH_FB_USERNAME', 'REPLACE_WITH_FB_TOKEN');

$recs = $tinderApi->getRecs();
foreach($recs as $rec){
    $tinderBot->like($rec->_id);
}

````