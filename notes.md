Postgreece SQL = Postgresql
Ms Squeel = MySQL
Squalatte = SQLite

posts     → id, user_id, content, image_path, created_at
follows   → id, follower_id, following_id, created_at
likes     → id, user_id, post_id, created_at
comments  → id, user_id, post_id, content, created_at

Model->Services->Controllers->Routes

User->Post
User->Comment
User->Message
User->Add friend
User->Can see friend's post

newsfeed->don't update in real-time

users -> chat_participants -> chats -> messages

## Roadmap ##
feed algorithm
people algorithm

PROBLEM:
SMTP is blocked on render 

ONGOING:
# TODO: add authentication/security layer in websocket server
# TODO: share post
# TODO: caching on feed 

HOTFIX:
* CSRF - no tokens yet on any form

DONE:
# TODO: delete post
# TODO: edit post that contains with images
# TODO: pagination on feed
# TODO: signup password minimum requirement
# TODO: signup validation
# TODO: add photos to post
# TODO: forgot password
# TODO: change profile picture
# TODO: improve login page (VERY IMPORTANT)
# TODO: additional information (work)
# TODO: friend list page
# TODO: mobile view 
# TODO: include posts to search results
* Profile page authorization
* IDOR vulnerability: MessageController::showChat
# TODO: edit post
# TODO: list of friends on profile 
# TODO: create redirection page for clicking notification event Go here -> notification.php line 10
# TODO: Change name with 30 days constraint
# TODO: enhance conversation list and news-feed design
# TODO: poke
# TODO: comment
# TODO: Track Send message implementation 
# TODO: Conversation list
# TODO: Message 
# TODO: Add friend
1. Search -> User
2. User search list results should have id and clickable
3. clicked list(user) -> redirect to view profile
4. view profile -> click message from view profile
5. clicked user from chat list -> redirect to message page -> chatbox 
