import json
import os
from flask import Flask
from flask import request, redirect
from rover_pdf import RoverPDF
from gmail_api import GmailAPI
from jira.jira_api import JiraAPI

application = Flask(__name__)

@application.route('/', methods=['POST'])
def process_quote():
    #gather all data from form
    quote = request.form
    name = quote['quote-name']
    email = quote['quote-email']
    phone = quote['quote-phone']
    company = quote['quote-company']
    address = quote['quote-address-1']
    address_2 = quote.get('quote-address-2', '')
    city = quote.get('quote-city', quote['quote-country'])
    country = quote['quote-country']
    state = quote.get('quote-state', '')
    zipcode = quote['quote-zipcode']
    # cart data comes as json string. use json loads to turn into python dict
    cart_data = json.loads(quote['cart-data'])
    shipping_phone = quote['quote-phone-shipping']
    shipping_company = quote['quote-company-shipping']
    shipping_address = quote['quote-address-1-shipping']
    shipping_city = quote['quote-city-shipping']
    shipping_state = quote.get('quote-state-shipping', '')
    shipping_address_2 = quote.get('quote-address-2-shipping', '')
    shipping_zipcode = quote['quote-zipcode-shipping']

    # init pdf object
    pdf = RoverPDF(name, email, phone,company, address,address_2, country, city, state, zipcode, cart_data, shipping_phone, shipping_company, shipping_address,shipping_address_2, shipping_city, shipping_state, shipping_zipcode)
    #create pdf
    pdf = pdf.create_pdf()
    gmail = GmailAPI(email_from="nick@roverrobotics.com", email_to=email, email_subject="Quote Request For Rover Robotics", pdf=pdf)
    #send message
    gmail.send_message()
    #redirect user
    return redirect("https://roverrobotics.com/pages/quote-request?submitted=true", code=302)


@application.route('/contact', methods=['POST'])
def contact():
    form = request.form
    name = form['contact[name]']
    email = form['contact[email]']
    company = form['contact[company]']
    body = form['contact[body]']

    data = {
        "fields": {
            "project":
                {
                    "key": "ERP"
                },
            "summary": f"New Lead: {name}",
            "description": f"name: {name}\n email: {email} \n company: {company} \n\n {body}",

            "issuetype": {
                "name": "ERP - Sales Leads and Orders"
            },
            "customfield_10055": email,
        },
    }

    try:
        jira = JiraAPI(os.environ['jira_username'], os.environ['jira_password'])
        gmail = GmailAPI(email_from="nick@roverrobotics.com", email_to="nick@roverrobotics.com",
                         email_subject="Rover Contact Form Submission", data={"name": name, "email": email, "company": company, "body": body})
        jira.create_issue(data)
        gmail.send_message()
        return redirect("https://roverrobotics.com/pages/contact?submitted=true", code=302)
    except:
        gmail = GmailAPI(email_from="nick@roverrobotics.com", email_to="nick@roverrobotics.com",
                         email_subject="Rover Contact Form Submission Failed!", data={"name": name, "email": email, "company": company, "body": body})
        gmail.send_message()
        redirect("https://roverrobotics.com/pages/contact?submitted=false", code=302)


@application.route('/order-webhook', methods=["POST"])
def shopify_order():
    order = request.get_json()
    # process address into string
    address_format = ""
    for k, v in zip(order['shipping_address'].keys(), order['shipping_address'].values()):
        address_format += f"{k} : {v} \n"

    jira = JiraAPI(os.environ['jira_username'], os.environ['jira_password'])

    data = {
        "fields": {
            "project":
                {
                    "key": "ERP"
                },
            "summary": f"Shopify Online Order",
            "description": f"A a online order has been submitted through Shopify: \n\n {address_format}",

            "issuetype": {
                "name": "ERP - Sales Leads and Orders"
            },
            "customfield_10055": order['email'],
            "customfield_10047": address_format
        },
    }

    # create a new issue
    created = jira.create_issue(data)
    # grab key
    issue_key = created['key']
    # transition status to order
    transition = jira.set_transition(issue_key, "111")

    print("added transition",transition)






if __name__ == '__main__':
    application.run()
