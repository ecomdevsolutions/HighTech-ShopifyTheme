import os
import json
import requests
from shopify import ShopifyAPI


class WebookAPI(ShopifyAPI):
    resource = "webhooks"

    def __init__(self):
        super(WebookAPI, self).__init__("roverrobotics", "a7af588f94ba8d61014687a2642a9d87", "b6a8ad0b38729aa0cceafe626ce0b3a5", "2019-10")

    def count_total_subscribed_webhooks(self):
        url = os.path.join(self.base_uri, self.resource, "count.json")
        r = requests.get(url)
        return r.json()

    def subscribe(self, topic: str, address: str):
        url = os.path.join(self.base_uri, self.resource + ".json")
        data = {
          "webhook": {
            "topic": topic,
            "address": address,
            "format": "json"
          }
        }

        data = json.dumps(data)

        r = requests.post(url, data=data, headers={"Content-Type": "application/json"})
        return r.json()

    
    def update(self):
        raise NotImplementedError('Method Not Implemented')

    def get_all_webhooks(self):
        url = os.path.join(self.base_uri, self.resource + ".json")
        r = requests.get(url)
        return r.json()

    def get_webhook(self, id):
        url = os.path.join(self.base_uri, self.resource, "{}.json".format(id))
        r = requests.get(url)
        return r.json()

    def unsubscribe(self, id):
        url = os.path.join(self.base_uri, self.resource, "{}.json".format(id))
        r = requests.delete(url)
        return r.json()









