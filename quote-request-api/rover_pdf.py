
import os
import os.path as path
import sys
import subprocess
import time
import pydf
import base64
import jinja2
import time
from flask import render_template
from io import BytesIO



class RoverPDF:
    def __init__(self, name, email, phone, company, address, country, city, state, zipcode, cart_data, shipping_phone, shipping_company, shipping_address, shipping_city, shipping_state, shipping_zipcode):
        self.name = name
        self.email = email
        self.phone = phone
        self.company = company
        self.address = address
        self.country = country
        self.city = city
        self.state = state
        self.zipcode = zipcode
        self.cart_data = cart_data
        self.shipping_phone = shipping_phone
        self.shipping_company = shipping_company
        self.shipping_address = shipping_address
        self. shipping_city = shipping_city
        self.shipping_state = shipping_state
        self.shipping_zipcode = shipping_zipcode
        #shipping rates (int: weight in lbs, int: max price)
        self.shipping_rates = shipping_rates = [(5,9), (15,36), (35,75), (60,115),(95,190),(120,230), (155,305), (180,345), (215,420),(240,460),(275,535), (300,575), (335,650), (360,690), (395,765), (420,805), (455,880), (480,920)]
        self.uk_shipping = 460
        self.grams_to_lb = 453.592

    def create_pdf(self):
        current_path = os.path.dirname(os.path.realpath(__file__))
        templateLoader = jinja2.FileSystemLoader(os.path.abspath(
            os.path.join(current_path, "templates")))
        env = jinja2.Environment(loader=templateLoader)
        template = env.get_template(f'quote.html')
        self.create_shipping_price()
        self.get_images()
        try:
            outputText = template.render(
                name=self.name, email=self.email, phone=self.phone, company=self.company, address=self.address, country=self.country, city=self.city, state=self.state, zipcode=self.zipcode, cart_data=self.cart_data, shipping_phone=self.shipping_phone ,shipping_company=self.shipping_company, shipping_address=self.shipping_address, shipping_city=self.shipping_city, shipping_state=self.shipping_state, shipping_zipcode=self.shipping_zipcode)

        except Exception as e:
            raise Exception(f'Error creating jinja 2 template: {e}')
        try:
            pdf = pydf.generate_pdf(outputText)
        except Exception as e:
            raise Exception(f"Error generating pdf with pypdf: {e}")
        return BytesIO(pdf)

    def create_shipping_price(self):
        if self.country == "UK":
            self.cart_data['shipping_price'] = self.uk_shipping
            return
        cart_grams = self.cart_data['total_weight']
        cart_lbs = (cart_grams / self.grams_to_lb)
        price = 0
        last_rate = 0
        for rate in self.shipping_rates:
            if cart_lbs < rate[0] and cart_lbs >= last_rate:
                price = rate[1]
                last_rate = rate[0]
        self.cart_data['shipping_price'] = price

    def get_images(self):
        with open('./templates/roverlogo.jpg', "rb") as image:
            image_read = image.read()
            image_64_encode = base64.encodestring(image_read).decode("utf-8")
            self.cart_data['logo'] = image_64_encode
            return image_64_encode
