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







