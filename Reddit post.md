***If you have any ideas or suggestions, please let me know. I really need ideas***

----

**Links:**

[CivTrade](http://civtrade.com)

[GitHub repo](https://github.com/minicl55/civtrade)

[As-it-happens changelog (I update this as I make changes/get ideas)](https://github.com/minicl55/civtrade/issues)

----

**API Information:**

All *verified* accounts on CivTrade have access to the API. Verifying your account is easy and takes about 10 seconds. If you aren't verified yet, just log in to CivTrade and take a look at the [top panel](https://i.imgur.com/Cy3bjsO.png).

The reason you are required to have a verified account is to make sure that all logged actions are actually you and not an imposter.

Once you verify your account, scroll to the bottom fo the UserCP. You should see [an API information panel](http://i.imgur.com/2vmcB0I.png) panel. It will be updated as APIs are added. Currently, there is only one API - cities.php

The cities API takes no argruments and returns a list of cities with the amount of offers. As of right now, the page returns:

`["",1,"aurora",3,"Ember",1,"Etherium",1,"fellowship or orion",1,"New Senntisten",2,"orion",15,"the jungle",1,"Titan",1]`

The first string is the city name, followed by an int of the total number of offers.

If you're getting the error `Invalid API token OR your account is not verified OR your API token has been suspended`, you have an invalid API token, your account isn't verified or your API token has been suspended. PM me on reddit if you have any troubles.

***If you need an API made, tell me. The reason there's only one right now is because that's the only one that's been requested. I'm very open to making new ones***

----

**Changelog:**

- Fixed the last remaining SQLi-possible area, I don't know how I missed it the first time but they should all be gone now

- Fixed a few XSS areas, they *should* be removed.

- Fixed /control's padding on the User Info panel

- Added defaults to the MySQL database for user settings

- Added settings in /other/req.php to easily change/disable a few major areas

- Added a **very giltchy and rudamentary** PM system. It should, quite frankly, not be used right now, it's still in very early beta.

- Moved away from the MySQL root user and made one with just needed perms

- http://beta.civtrade.com is now where I'll test my changes before pushing them to the main server/github

- Notes moved to cookies instead of being in the URL, meaning:

 - The URL won't be cluttered
 
 - Notes go away after one view
 
//TODO: 7 and above

----

**Future plans**

//Todo: Talk about the stuff 597 and I talked about
