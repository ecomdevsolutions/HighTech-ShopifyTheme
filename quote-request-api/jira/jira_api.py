import requests
from requests.auth import HTTPBasicAuth


class JiraAPI:
    def __init__(self, username, token):
        self.username = username
        self.token = token
        self.base_url = "https://roverrobotics.atlassian.net/rest/api/latest/"
        self.auth = HTTPBasicAuth(self.username, self.token)

    def get_issue(self, issue_key):
        res = requests.get(f'{self.base_url}/issue/{issue_key}', auth=self.auth)
        return res.json()

    def create_issue(self, data):
        try:
            res = requests.post(f'{self.base_url}/issue/', auth=self.auth, json=data)
            if not res.ok:
                raise ValueError(f"Error: Invalid API call to Jira {res.text} \n\n {data}")
            return res.json()
        except requests.exceptions.RequestException as e:
            print(e)
            raise Exception(f'Error Posting to Jira API, {e}')
        except Exception as e:
            print(e)

    def get_transitions(self, issue_key):
        res = requests.get(f'{self.base_url}/issue/{issue_key}/transitions?expand=transitions.fields', auth=self.auth)
        return res.json()

    def set_transition(self, issue_key, id):
        data = {
            "transition": {
                "id": id
            }
        }
        res = requests.post(f'{self.base_url}/issue/{issue_key}/transitions', auth=self.auth, json=data)
        return res.text



test_token = "QRKWZUlEF364ARIRNd72360B"
customer_shipping_field_id = "10047"

j = JiraAPI("tyler@digilabs.io", test_token)

data = {
        "fields": {
            "project":
                {
                    "key": "ERP"
                },
            "summary": f"TEST NAME",
            "description": f"TEST",

            "issuetype": {
                "name": "ERP - Sales Leads and Orders"
            },
            "customfield_10055": "TEST",
            "customfield_10047": "TEST ADDRESS"
        },

    }


created = j.create_issue(data)
issue_key = created['key']
print(issue_key)
#status = j.get_transitions(issue_key)
transition = j.set_transition(issue_key, "111")





