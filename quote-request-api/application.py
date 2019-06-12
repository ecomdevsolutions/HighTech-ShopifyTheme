from flask import Flask
from flask import request, redirect
from rover_pdf import RoverPDF
import json
from gmail_api import GmailAPI


application = Flask(__name__)

@application.route('/', methods=['POST'])
def process_quote():
    #gather all data from form
    quote = request.form
    name = quote['quote-name']
    email = quote['quote-email']
    phone = quote['quote-phone']
    company = quote['quote-company']
    address = quote['quote-address']
    city = quote['quote-city']
    country = quote['quote-country']
    state = quote.get('quote-state', '')
    zipcode = quote['quote-zipcode']
    #cart data comes as json string. use json loads to turn into python dict
    cart_data = json.loads(quote['cart-data'])
    shipping_phone = quote['quote-phone-shipping']
    shipping_company = quote['quote-company-shipping']
    shipping_address = quote['quote-address-shipping']
    shipping_city = quote['quote-city-shipping']
    shipping_state = quote.get('quote-state-shipping', '')
    shipping_zipcode = quote['quote-zipcode-shipping']

    #init pdf object
    pdf = RoverPDF(name, email, phone,company, address, country, city, state, zipcode, cart_data, shipping_phone, shipping_company, shipping_address, shipping_city, shipping_state, shipping_zipcode)
    #create pdf
    pdf = pdf.create_pdf()
    gmail = GmailAPI(email_from="matt@roverrobotics.com", email_to=email, email_subject="Quote Request", pdf=pdf)
    #send message
    gmail.send_message()
    #redirect user
    return redirect("https://roverrobotics.com/pages/quote-request?submitted=true", code=302)


if __name__ == '__main__':
    application.run()
