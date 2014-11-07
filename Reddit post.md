**Before we get to the API...** 

/u/Eeazt made a [fantastic comment](https://www.reddit.com/r/Civcraft/comments/2l01n6/civtrade_is_now_open_source_last_30_hours_of/clqucvw) a little bit ago:

>I'll absolutely be using this from now on provided we can maneuver a mass transition from civcraftexchange over here.

I'd like to ask you guys, what would it take to make a migration? As far as I can tell the only downside to CivTrade over /r/CivcraftExchange is the amount of people using the subreddit.

I'm only making this because I truely think it's a better alternative. I don't have ads, charge diamonds for stickied posts, etc... It's all because I truely think we can make something better. 

**TL;DR: What would it take to make you move to CivTrade?**

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

`["aurora",3,"Ember",1,"Etherium",1,"fellowship or orion",1,"minus-minus",1,"New Senntisten",2,"orion",15,"the jungle",1,"Titan",1]`

The first string is the city name, followed by an int of the total number of offers.

If you're getting the error `Invalid API token OR your account is not verified OR your API token has been suspended`, you have an invalid API token, your account isn't verified or your API token has been suspended. PM me on reddit if you have any troubles.

***If you need an API made, tell me. The reason there's only one right now is because that's the only one that's been requested. I'm very open to making new ones***

----

**Future plans**

//Todo: Talk about the stuff 597 and I talked about

----

**Changelog:**

- Added an API!

- Fixed the last remaining SQLi-possible area, I don't know how I missed it the first time but they should all be gone now

- Fixed a few XSS areas, they *should* be removed entirely.

Those were both very serious security holes, hopefully it's pretty secure now.

- Added a **very giltchy and rudamentary** PM system. It should, quite frankly, not be used right now, it's still in very early beta.

- http://beta.civtrade.com is now where I'll test my changes before pushing them to the main server/github. 
 
- Errors have been disabled on the main site (this should have been done a while ago)

- Removed copy to clipboard (do you really need something to help you with this?)

- Created a reset password thing, to reset your password hop on to civcraft and type **/msg gastriko register resetpw**

----

**Minor bug fixes:**

- Fixed /control's padding on the User Info panel

- Added defaults to the MySQL database for user settings

- Added settings in /other/req.php to easily change/disable a few major areas

- Moved away from the MySQL root user and made one with just needed perms

- [Notes](https://i.imgur.com/WHTbPaf.png) moved to cookies instead of being in the URL, meaning:

 - The URL won't be cluttered
 
 - Notes go away after one view
 
- Removed a hard-coded key

- Locations weren't returning anything, now they are

----

***Once again, PLEASE give me any suggestions on how to make this a better website or improve it in some way***
