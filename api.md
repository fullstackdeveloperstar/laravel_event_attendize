##user login
	post
	http://159.203.99.60/api/login
	-request:
		email:rubby.star@hotmail.com
		password:***
	-response:
		{
		    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEyLCJpc3MiOiJodHRwOi8vMTU5LjIwMy45OS42MC9hcGkvbG9naW4iLCJpYXQiOjE1MjYzMDYyNTAsImV4cCI6MTUyNjMwOTg1MCwibmJmIjoxNTI2MzA2MjUwLCJqdGkiOiJuNUVMeFplb1BXSzFrcUROIn0.ct6N9I6WHEpC6RLudw_ceFfRcoiySLKKpDkhtB9djh4"
		}

