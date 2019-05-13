# Rover Robotics Quote Request API

This api when accessed generates a PDF containing the customers shopping cart and other data submitted from  [https://roverrobotics.com/pages/quote-request](https://roverrobotics.com/pages/quote-request).

## Installation

1. Install Python 3.5+
2. Install [pipenv](https://github.com/pypa/pipenv)



Use the package manager [pipenv](https://github.com/pypa/pipenv) to install dependencies
Note: This application wont work locally. It must me deployed on a web service.

```bash
pipenv install
```

## Basic Overview and Functionality

There's 3 main files. app.py, gmail_api.py and rover_pdf.py. When a request comes in from the shopify website  app.py processes the request using the [Flask](http://flask.pocoo.org/) web framework. Then rover_pdf.py creates a PDF by taking a template from "quote.html" and populating it with customers data using [Jinja2](2http://jinja.pocoo.org/docs/2.10/) templating engine. The template is then passed as a string to "python-pdf" which takes an HTML string and converts it to a PDF. The PDF is then passed to the gmail API service and sent to the customer.

## Key Notes
1. The shipping and tax data is hard coded into the service. Any changes on the shopify admin interface will not affect this service.
2. This service uses a [Google Service Account](https://cloud.google.com/iam/docs/service-accounts) to access the gmail API. Only emails associated with Rover Robotics can send emails.
3. The "service_key.json" was created in the G-Suite admin and was renamed in this application. It's how the service account authenticates this Python application.


Please make sure to update tests as appropriate.

## Key Resources
1. [Gmail API tutorial with G Suite](https://medium.com/lyfepedia/sending-emails-with-gmail-api-and-python-49474e32c81f) Note: Some of the code was incorrect in this tutorial and had to be re-written 
