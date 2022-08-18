import requests 
import json

response = requests.post("http://localhost/api/apis/about")
print("HTTP Status Code "+str(response.status_code))
# data = json.loads(response);


# print("Version: "+str(data['version']))
# print("Description: "+str(data['desc']))
