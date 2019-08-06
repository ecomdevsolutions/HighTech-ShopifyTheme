from __future__ import print_function
from googleapiclient.discovery import build
from httplib2 import Http
import mimetypes
import email
from email.mime.multipart import MIMEMultipart
from email.mime.text import MIMEText
import base64
from google.oauth2 import service_account
from email.mime.base import MIMEBase



class GmailAPI:
    def __init__(self, email_from, email_to, email_subject, pdf=None, data=None):
        #list of services that your trying to access. Note you must enable them in gsuit
        self.SCOPES = ['https://www.googleapis.com/auth/gmail.send']
        #used for auth
        self.SERVICE_ACCOUNT_FILE = 'service-key.json'
        self.email_from = email_from
        self.email_to = email_to
        self.email_subject = email_subject
        self.pdf = pdf
        self.data = data
        if self.pdf:
            self.email_content = self.build_email_content('./templates/email_template.html')
            # authenticates using google service account for rover robotics
            # service object allows acces to gmail service
            self.service = self.service_account_login()
            # Call the Gmail API
            self.message = self.create_message()
        else:
            self.service = self.service_account_login()
            self.message = self.contact_message()

        self.user_id = "me"
        #sent = send_message(self.service,'me', message)

    def contact_message(self):
        message = MIMEText(f"Name: {self.data.get('name')} \n Email: {self.data.get('email')} \n Company: {self.data.get('company')} \n Message: {self.data.get('body')}")
        message['to'] = self.email_to
        message['from'] = self.email_from
        message['subject'] = self.email_subject
        raw = base64.urlsafe_b64encode(message.as_bytes())
        raw = raw.decode()
        return {'raw': raw}


    def create_message(self):
      """Create a message for an email.
      Args:
        sender: Email address of the sender.
        to: Email address of the receiver.
        subject: The subject of the email message.
        message_text: The text of the email message.
      Returns:
        An object containing a base64url encoded email object.
      """

      content_type = "application/pdf"

      main_type, sub_type = content_type.split('/', 1)

      message = MIMEMultipart()
      message['to'] = self.email_to
      message['from'] = self.email_from
      message['subject'] = self.email_subject
      msg = MIMEText(self.email_content, "html")
      message.attach(msg)
      attachment = MIMEBase(main_type, sub_type)
      attachment.set_payload(self.pdf.getvalue())
      attachment.add_header('Content-Disposition', 'attachment', filename="quote_request.pdf")
      email.encoders.encode_base64(attachment)
      message.attach(attachment)

      #base64.urlsafe_b64encode(message.as_string().encode()).decode()
      #return {'raw': base64.urlsafe_b64encode(message.as_string().encode()).decode()}

      raw = base64.urlsafe_b64encode(message.as_bytes())
      raw = raw.decode()
      return {'raw':raw}

    def send_message(self):
      """Send an email message.
      Args:
        service: Authorized Gmail API service instance.
        user_id: User's email address. The special value "me"
        can be used to indicate the authenticated user.
        message: Message to be sent.
      Returns:
        Sent Message.
      """
      try:
        message = (self.service.users().messages().send(userId=self.user_id, body=self.message)
                   .execute())
        print('Message Id: %s' % message['id'])
        return message
      except Exception as e:
          print(f"An error occurred: {e}")

    def service_account_login(self):
      credentials = service_account.Credentials.from_service_account_file(
              self.SERVICE_ACCOUNT_FILE, scopes=self.SCOPES)
      delegated_credentials = credentials.with_subject(self.email_from)
      service = build('gmail', 'v1', credentials=delegated_credentials)
      return service

    def build_email_content(self, template_dir):
        with open(template_dir, "r") as email:
            email_template = email.read()
            email.close()
            return email_template
