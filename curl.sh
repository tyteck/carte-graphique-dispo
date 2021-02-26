#!/bin/bash

curl -s --user 'api:cf978cbcba1f0dc17c3b721345b0cbf5-d32d817f-3202360d' \
    https://api.mailgun.net/v3/sandboxb1ac16d183ae4c9d9889262bdfc405d8.mailgun.org/messages \
    -F from='Excited User <mailgun@sandboxb1ac16d183ae4c9d9889262bdfc405d8.mailgun.org>' \
    -F to=YOU@sandboxb1ac16d183ae4c9d9889262bdfc405d8.mailgun.org \
    -F to=bar@example.com \
    -F subject='Hello' \
    -F text='Testing some Mailgun awesomeness!'
