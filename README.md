# feeds

# To run the app,

cd /feeds/scripts/
Run php bbc.php

Expected Output :

{
    "results": [
        {
            "href": "http:\/\/www.bbc.co.uk\/news\/magazine-31818370",
            "size": "116.92 KB",
            "most_used_word": "her",
            "title": "1: The lion hugger"
        },
        {
            "href": "http:\/\/www.bbc.co.uk\/news\/world-asia-india-31960557",
            "size": "97.76 KB",
            "most_used_word": "students",
            "title": "2: Indians caught 'cheating' in exams"
        },
        {
            "href": "http:\/\/www.bbc.co.uk\/news\/science-environment-31941482",
            "size": "116.10 KB",
            "most_used_word": "from",
            "title": "3: Wet wipe litter on beaches 'up 50%'"
        },
        {
            "href": "http:\/\/www.bbc.co.uk\/news\/science-environment-31905764",
            "size": "118.95 KB",
            "most_used_word": "that",
            "title": "4: DNA study: Celts not a single group"
        },
        {
            "href": "http:\/\/www.bbc.co.uk\/news\/health-31921098",
            "size": "111.30 KB",
            "most_used_word": "eclipse",
            "title": "5: 'Selfie danger' during solar eclipse"
        }
    ]
}


# To run unit tests,

cd /feeds/tests/
run php phpunit.phar .

The Unit tests results will look like :
php phpunit.phar .