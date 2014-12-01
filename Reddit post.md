**Before we get to the main points...**

/u/Eeazt made a [fantastic comment](https://www.reddit.com/r/Civcraft/comments/2l01n6/civtrade_is_now_open_source_last_30_hours_of/clqucvw) a little bit ago:

>I'll absolutely be using this from now on **provided we can maneuver a mass transition from civcraftexchange over here.**

(Emphasis mine)

I'd like to ask you guys, what would it take to make a migration? As far as I can tell the only downside to CivTrade over /r/CivcraftExchange is the amount of people using the subreddit.

I'm only making this because I truly think it's a better alternative. I don't have ads, I don't charge diamonds for stickied posts, etc... It's all because I truly think we can make something better. 

**TL;DR: What would it take to make you move to CivTrade?**

----

**Links:**

[CivTrade](http://civtrade.com)

[GitHub repo](https://github.com/minicl55/civtrade)

----

**New API:**

I added a new API. You still must be verified to use it (verification takes about 10 seconds, if anyone needs help just PM me or post here).

The new API is located at http://civtrade.com/api/userPosts.php. You need your API token to view it. An API token can be accesed at any time by logging in to the control panel and scrolling all the way down to the [API Information panel](http://i.imgur.com/0H3gNm1.png)

This page returns a list of all users that have posted at least once, and their total posts. For me, it returns:

`[{"name":"minicl55","amount":4}]`

On the real page, however, it will return all usernames and their amounts.

This command has an optional argument, user. If you append it to the URL as a GET parameter (http://civtrade.com/api/userPosts.php?token=token&user=user), it will ONLY return the amount of posts for that user. For example, if I were to put &user=minicl55, it would return `4`, nothing more.

If you're getting the error `Invalid API token OR your account is not verified OR your API token has been suspended`, you have an invalid API token, your account isn't verified or your API token has been suspended. PM me on reddit if you have any troubles.

***If you need an API made, tell me. The reason there's only two right now is because those are the only two that have been requested. I'm very open to making new ones to meet someone's specific needs***

----

**Auctions:**

A brand new feature has been added, auctions! Auctions are simple to set up. The old "create new post" system has been [slightly upgraded](https://i.imgur.com/GA1DuvH.png). A new field has been added to the bottom, "minimum auction increase".

To show how auctions work, I will make one and document it.

First, fill in all the fields in the new offer box, [like this](https://i.imgur.com/8FzS0dX.png) and click "create auction"

The card will look like [this](https://i.imgur.com/5MuwMoo.png), which is slightly different than a [non auction card](https://i.imgur.com/TrhyXCd.png). You are automatically set to the last bidder with the highest bid the starting value you put in the create new post panel.

Now, anyone can bid on your auction. [This is a picture of what it looks like to people other than the poster](https://i.imgur.com/M3LmXtA.png). 

If you want to bid on an auction, click "increase bid by [amount] [item]". [This is a picture of what happened after I clicked the bid button on my alt](https://i.imgur.com/axho45R.png)

Three things you should notice:

1) The current price went up by 3

2) The newest bidder was changed to my alt (minicl66). Additionally, because this account is unverified, a bold unverified tag was added to the name

3) I can no longer click the button on my alt, as I am the top bidder.

To end the auction, log in as the poster and click "Mark inactive". An auction can be brought back at any time by clicking "Show your inactive posts" and clicking "mark active"

----

**Post directly to /r/CivcraftExchange**

All users may now post directly to /r/CivcraftExchange with the push of a button.

This just opens up a tab with the information pre-set in the title/message box, the user must still click submit new post.

----

**New PM system:**

I re-did the PM system from scratch. It looks [somewaht similar](https://i.imgur.com/MzqaRQn.png) but the backend has been changed a lot.

Sending a PM is simple, just fill in the to and message fields. 

When viewing your PMs, you might notice that some panels look different. This is because [when you are the sender, they are dark blue](https://i.imgur.com/KYnKPRp.png). However, [if you're the receiver, they're light blue](https://i.imgur.com/wTrEFUV.png)

If you get a new PM, you'll see a banner at the top of every page that looks [like this](https://i.imgur.com/YiAcp5Z.png). Clicking on that link will bring you to the PM page.

Once you load the page, all PMs are marked as read. However, this logic is preformed *after* the page is constructed, so you'll be able to see the difference between [unread PMs](https://i.imgur.com/tYRM7r7.png) and [read PMs](https://i.imgur.com/3QsShGx.png). Additionally, the sender will be able to see the status of your message at any time. If it's unread, it will say unread in bold letters, [like this](https://i.imgur.com/Y20dvWH.png).

Auctions have a [slightly different button for the poster to post the highest bidder](https://i.imgur.com/BLlL1dK.png)

----

**Txapu integration:**

/u/the_gipsy has updated [txapu](http://txapu.com) to link to CivTrade whenever a city has an offer avaible. If there is no offer avaible, it looks like [this](https://i.imgur.com/sjJ4Ipe.png). If there is an offer, it looks like [this](http://i.imgur.com/6WI6dRV.png). Clicking the link will bring you to a CivTrade search for only that city. For example, [this](http://civtrade.com/?want=&have=&loc=orion) is the page for Orion.

This is an amazing example of people using the API, if anyone else needs one created I'm happy to do so, just let me know.

----

**New semi-important stuff:**

- Added settings

  - A new [settings panel](https://i.imgur.com/gJMi5Yq.png) has been added to the user control panel page. Currently the only two options are to lock the navbar (for smalls screens) and remove the "Now open source!" href from the navbar, but if anyone wants another option, just tell me and I'll add it.
  
  - Both settings default to false and are false for any user not logged in.

- Removed timestamps. The entire post info thing has been removed to give panels a cleaner feel.

- Added new API

- Added auctions

- Added a way to reset your password (just log on to CivCraft and type /msg gastriko register resetpw)

- Fixed a few issues with items not returning values

- City names now must match a city on Txapu

- Added a new button to post directly to /r/CivCraftExchange

----

***I really want some suggestions for stuff to work on, please let me know if you have any, even if you don't know how to code it, please let me know and I'll do it!***
