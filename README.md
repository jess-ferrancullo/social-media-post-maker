# FB, IG, and Twitter poster

## General Setup
- sail build
- sail up -d
- sail composer install
- sail artisan migrate
- npm install
- npm run build
- fill up your necessary keys in your env
- add your facebook page
- add your instagram account

## How to setup Facebook API
- DOCS to get Started: https://developers.facebook.com/docs/graph-api/get-started
- Go to https://developers.facebook.com/ and create an app
- Go to your facebook to create a page. (This is what were gonna use to make posts)
- Go to https://developers.facebook.com/tools/explorer/
- Generate access token for your page
- Add these permissions
    - user_videos
    - user_posts
    - publish_video
    - pages_show_list
    - pages_read_engagement
    - pages_manage_posts
 - Try to make a GET request /me/accounts
 - Now get the page details and save it on the `localhost/facebook/page-tokens/create`

## How to setup Instagram Graph API
- DOCS to get Started https://developers.facebook.com/docs/instagram-api/getting-started
- Switch your instagram account into professional mode
- In your facebook graph api dashboard, add these permissions
    - instagram_basic
    - instagram_content_publish
- In your facebook page, go to settings and connect it to the instagram account (WARNING!) You cant post anymore on that fb page as it will be turned into a business account, you have to ask for permission from meta, I suggest you connect a different fb page instead.
- Now select the page you connected in `localhost/instagram/connect`

## How to setup Twitter Api
- DOCS to get Started https://developer.twitter.com/en/docs/twitter-ads-api/getting-started
- Sign up for a dev account
- Create an app and go to dashboard
- Generate keys, we need Consumer key, Consumer Secret, Access Token, and Access Secret
- Go to your app and go to User authentication settings
- Select the read and write, in the bottom, there will be required inputs, but its not needed so just add whatever url you want.
- Regerenate the Access tokens and check if it has read and write
- Add the keys to your env

## Samples
![twitter_post](https://github.com/user-attachments/assets/88ffcd3f-5e23-4553-a516-c7d45ee0eeeb)
![fb_post](https://github.com/user-attachments/assets/e1a80c85-5f5e-48cf-9594-8e2a7e4737af)
![insta_post](https://github.com/user-attachments/assets/219d0728-7c78-4557-bda0-8c2c07d14cec)

## Tech Stack
- Laravel 10
- Laravel sail
- Laravel Livewire
- Tailwind CSS
- abraham/twitteroauth - Twitter Api
- Facebook Graph SDK for PHP - Facebook and Instagram Api

## Notes:
- For some reason instagram api will not upload a carosel that contains atleast one video. The api will just say media_id not available
- There are requirements for images and videos so please check documentations
- request errors can be found in laravel.log
- Jobs are used because posting them will take some time, retries and delay.
