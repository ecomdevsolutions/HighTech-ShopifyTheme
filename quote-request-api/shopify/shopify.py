

class ShopifyAPI:

    def __init__(self, shop, username, password, api_version):
        self.shop = shop
        self.username = username
        self.password = password
        self.api_version = api_version


    @property
    def base_uri(self):
        return "https://{}:{}@{}.myshopify.com/admin/api/{}/".format(
            self.username,
            self.password,
            self.shop,
            self.api_version
        )
