import requests
import getpass

def login(username,password) :
    payload = {
        'user' : username,
        'pass' : password
    }
    response = requests.post("http://localhost/api/apis/verify",data=payload)
    if(response.status_code == 200):
        return True
    elif (response.status_code == 403):
        return False
    else:
        pass

u = input("Enter Username :")
p = getpass.getpass("Enter Password :")

if(login(u,p)):
    print("Login Success")
else:
    print("Login Failed")

