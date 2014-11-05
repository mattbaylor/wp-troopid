wp-troopid
==========

WordPress TroopID Verification and SSO plugin

### Usage
provides four shortcodes: 
- [troopid_link] provides a redirect path for troop id verification.
- [troopid_verified_container] outputs a div that will show "Verified" when troopid verification has been accomplished. Div id = troopid_verified class = troopid-verified.
- [troopid_popup] provides a popup that the user can complete (login) and then hands back the verification data (REST JSON)
- [troopid_formload] uses similar mechanism to [troopid_popup] but attempts to prepopulate input fields as follows:
  - first_name = name of the input that should be populated with TroopID information
  - last_name = name of the input that should be populated with the TroopID information
  - email = name of the input that should be populated with the TroopID information (sams as troopid username)
  - phone = name of input for phone information (unformatted)
  - zip = name of input for the zipcode information
  - verified = name of the input for the verified flag (true|false)
  - affiliation = name of the input for the current troop status (Service Member = Active, Veteran = Veteran, Retiree = Retiree, Spouse = Military Spouse, Family = Military Family)
  - service_started = name of the input for the service started date
  - service_ended = name of the input for the service end date
  - example:

```[troopid_formload first_name="first_name" last_name="last_name" email="email" phone="Phone" zip="zip" verified="verified" affiliation="Status"]```
  - example of returned JSON:

```{"id":"08c13e12e52369451876","verified":true,"affiliation":"Service Member","email":"test+active@id.me","first_name":"Ed","last_name":"Snider","phone":"5555555555","zip":"12345","service_ended":null,"service_started":null}```

  - returned JSON has recently changed. New example:

```{"id":"08c13e12e52369451876","verified":true,"affiliation":"Service Member","email":"test+active@id.me","fname":"Ed","lname":"Snider","phone":"5555555555","zip":"12345","service_ended":null,"service_started":null}```

### Installation
1. Upload the released zip file using the plugin uploader in WordPress. Plan to list in WordPress extend/plugins in near future.
2. [Sign up with ID.me to provision your application and get both your client_id and client_secret.](https://developer.id.me/developers/sign-up "ID.me Developer Signup") 
2. Configure values in the WordPress Admin >> Settings >> WordPress TroopID plugin
  1. id.me client_id: from the signup process with ID.me
  2. id.me client_secret: from the signup process with ID.me
  3. redirect uri: usually defaulted correctly. Should be (http|https)://[server_name]//wp-content/plugins/wp-troopid/wptroopid_redir.php unless you have written your own custom redirect handler
  4. run plugin in sanbox mode: true for live data, false for test data ([full list of test data here](https://developer.id.me/documentation/test_data )[broken link 10/14/2014 -- Contacting Id.me to see if they have another source of test data.]
  5. Size of the button: (small, medium, large)
  6. Color of the button: (red, black, light gray, dark gray, white)
  7. Shape of the button: (rounded, square)
    - visit [id.me's design asset page](https://developer.id.me/documentation/assets) for pictures of all the buttons

**IMPORTANT**
Per id.me you have to [contact](mailto:api@id.me) them in order to get the additional values (other than verified) returned for your specific application:
```Note: Your application can be configured to return additional fields such as name and email if you are using ID.me as an SSO solution. Contact us to get started.```

