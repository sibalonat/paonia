<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

## About PAONIA
HOY GitHubers, PAONIA is a word, is a place, in the old Yllrian(Now Albania). Im a big fan of Umberto Ecco, when he says that a name can only come as a result of a verb. So PAONIA for me is a verb, its a the name of the city, but also a verb. A verb because it describes the vowel nature of our language in the past. So this is how the name PAONIA came to be. Thinking about these stuff. 
As for the app. 
The app is a compilation of some very advanced stuff that you could do in LARAVEL and i tried them, wrote them in a very easy to read way, and you can see the entirety of approaches i followed for backend and frontend.

## Collection in LARAVEL

There are four MODELS for the data of this application. The models are Video, Webinar, Article, Podcast. All of them display as a single collection in Landing, without the need to actually go to each model. So ive compiled all models with concat method in collection. This was done to prevent models for mantaining the same id, becase then we would have had [article(id=1), video(id=1), podcast(id=1),  webinar(id=1)]. With concat, your return a new set of keys. Thus, if you publish article before podcast, and you set to get the latest, you would have the article(id=1), podcast(id=2) and the following models based on when they were published. So this allowed to manage the set of collection into one single collection, in a very beautiful way. Thus this may serve as a way for you to think of doing something similar in the expressive and elegant methodology that laravel provides through the use of collection methods. After concatinating them(a form of merging, but as we said new set of id-s), we try to set the route to look for an id and slug. This that would get a specific id of the content returned, and then we render it if it matches up an id in the collection in the controller. 

[in the method parameter, i passed, $id, $slug and route i passed /paonian/{id}]
To filter a collection with the id i came across a very nice solution in Stack Overflow. To use this. 
So i used filter, and the id in the route i matched with the id of the item that came from the collection of models. 

# Comments in PAONIA
each item in the front had to have comments, likes and comment likes. PRETTY complicated if you ask me.
Lets start with the comments, this is what i did:
- created a parent_id attribute in schema of the db. 
- set the model relations i started with ARTICLE article had to have many comments -> this would allow to have as many level deep of comments, related to parent comment that you commented on.
- becuase i was feeling lucky, i went all in and transformed before long the schema of the comments to polymophic relations. 
(if you dont know what this are, you just think of it as relations from the future. It relates your data model to any model that i applies through the method in the model. so for example item->comments()->save($comment **[that you previously created, or you gone create]** ))
- i created an attribute for owner of the comment
- there is a separation in the backend and front between the comments that didnt have replies and comments that had replies, with the name root. So in your data collection when you gone dd($comments[name]) you get a separtion between the levels, so we have a root comment and then we have a loop with the parent_id attribute. This loops over and over and creates relations between the comments. 

## The likes
The likes were easy. I wanted to create something on my own, but i figured since there are already made sulution i went with
[rtconner]**[laravel-likeable]**
It creates a trait and then you can use it whenever you want. 
I started with the models such as the ARTICLE
and went on then to comments since the comments were already related to the article, i didnt need to filter them. I just needed to make that the liked comment was the liked comment, and unliked comment was that exact comment. Pretty simple: 
** [addLike(Comment $comment) { $comment->like() }] **
easy peasy. 

## Pivot Tables
I could have done them with polymorphic but i was a bit lazzy and i didnt get them that much in the begining. So i did the long run, thus with pivot_tables. So youll find tables with names such as article_tag, video_tag, thread_tag, podcast_tag and so on. I with i could have known better, but i mean now i would use polymorphic more in the future. Thats a token!

## Mentions
When a user want to mention another person you pres @and immediatly will popup names registered in the site. 

There are a lot of other stuff that im not writing here, lots more to be honest. But you have some keys on what to look for at least. 

I would suggest not to use the application as is. Since it has many and many stuff. But it doesnt have a seed. This was done intentionally. Sorry, for ive been in your shoes where you look for code that you have no contribution and you use it. I would though give you a very well writen app, where you could see some stuff that you dont find in any tutorial articles. 

- First i would look for the database and the tables.
- Then the composer to see what is installed and if it makes sense to install them as well
- Go to routes and see the public routes those that dont need a authenticating, those that need some type of authenenticating and then those dedicated to content creation or admins
- There are two middlewares i would suggest checking them both but focuss how was the admin implemented
- head to the models, see relations data collections have with another, how where they implemented. There are some cases for example for likes that we use trait from the vendor package so for this, would only be enough to use the trait as i did. There are dedicated collection related to models, such as comments. 
- After finishing with the models, go to controllers check the refactored section for the admin, where there are function for creating, editing, updating and deleting data. So done with that go straight to HomeController since its the most fatest of them all. 

These are my suggestions. 


## License

PAONIa is bassed on LARAVEL and is Free to use, under [Creative Commons 4.0](https://creativecommons.org/licenses/by/4.0/) **[thank me, somewhere, its fair]** and [MIT license](https://opensource.org/licenses/MIT).
