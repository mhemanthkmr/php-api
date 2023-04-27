#! /usr/bin/python3

from urllib import response
import requests
import getpass

response = ""


def login(username, email, password):
    payload = {
        'username': username,
        'pass': password,
        'email': email
    }
    response = requests.post("http://api.com/auth/signup", data=payload)
    print(response.text)
    if (response.status_code == 200):
        return 200
    elif (response.status_code == 400):
        return 400
    elif (response.status_code == 406):
        return 406
    else:
        pass


while True:

    u = input("Enter Username :")

    if u in ['']:
        print("Type Username Properly")
    else:
        break
while True:
    e = input("Enter Email : ")

    if e in ['']:
        print("Type Username Properly")
    else:
        break

while True:
    p = getpass.getpass("Enter Password :")

    if p in ['']:
        print("Type Username Properly")
    else:
        break

if (login(u, e, p) == 200):
    print("Account Created Successfully")
elif (login(u, e, p) == 406):
    print("Not Acceptable")
else:
    print("Bad Request")
