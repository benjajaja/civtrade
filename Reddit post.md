**Before we get to the API...** 

/u/Eeazt made a [fantastic comment](https://www.reddit.com/r/Civcraft/comments/2l01n6/civtrade_is_now_open_source_last_30_hours_of/clqucvw) a little bit ago:

>I'll absolutely be using this from now on **provided we can maneuver a mass transition from civcraftexchange over here.**

(Emphasis mine)

I'd like to ask you guys, what would it take to make a migration? As far as I can tell the only downside to CivTrade over /r/CivcraftExchange is the amount of people using the subreddit.

I'm only making this because I truely think it's a better alternative. I don't have ads, I don't charge diamonds for stickied posts, etc... It's all because I truely think we can make something better. 

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

***If you need an API made, tell me. The reason there's only one right now is because that's the only one that's been requested. I'm very open to making new ones to meet someone's specific needs***

----
<a name="plans"></a>
**Future plans**

/u/597 and I were disussing last Wednesday in Mumble about some possible ways to head, along wtih integration with CivCredit. I'm not sure if I'm allowed to say what I know about the future of CivCredit (I probably am, it's nothing major), but for now all I want to say is it may be a month or two before the API will be done. All I'm going to put is [this comment](https://www.reddit.com/r/CivCredit/comments/2kdy2f/api_request/clu29ok) that /u/TheCheaterman made about what the API will be once it's done.

Onto non-CivCredit related things...

Personally I subscribe to Google's philosophy that you should never announce a product without a working prototype, however I don't think it would work here (see issue #3). So, I'm going to go ahead and talk about it now.

/u/597 mentioned a system similar to [this](https://eve-central.com/), where the average prices of every trade were logged and dispalyed. People can still make offers at any price they want, this is just an addition to the current system.

The issues I see with it:

- Sort of destroys the idea of the free market. However, people already do this, just not with a formal system (ie, ~16i->1d, ~130d->prot, etc)

- It'd be **very** difficult to get an accurate average for every item in relation to every other item. There would be about of 400! (400 factorial, not **uhrmahgurd 400!!!!**) different item combonations, which is a [really, really, really big number.](https://i.imgur.com/FukNFmO.png) It may be smarter to only create it for diamond and iron (but still let people ask/offer anything, it just won't be added to the database if it's not i/d)

- **It would require a lot of trades set up before it would work.** This is the primary setback. To get an accurate idea of how much each item would cost on average, we'd need about 5 trades. Let's assume there's only 100 items regularly bought (armor/furnaces/doors/etc probably aren't bought *too* often). That would be a total of 500 trades set up. There's no technical limitation to this, my database can handle around 9.7 million offers, the only setback is people not using CivTrade. I've thought about trying to parce CivcraftExchange for data, however that may be pretty difficult because there's no standard followed for posting trades ([issue 6](https://www.reddit.com/r/Civcraft/comments/2iymxi/ive_spent_about_50_hours_working_on_an/)). If anyone has any ideas about how to promote CivTrade or get people to use it more, please let me know.

As always, any thoughts are much appriciated. 

Also I'm adding [new methods of sorting](https://github.com/minicl55/civtrade/issues/22)

----

**Changelog:**

- Added an API!

- Found and squashed a bug that caused the database to be queried once per offer every time the index page loaded, load times should be noticably faster now

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
