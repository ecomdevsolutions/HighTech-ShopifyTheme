# Roverrobotics Web Ecosystem

This repo contains:
* https://roverrobotics.com
* https://blog.roverrobotics.com
* https://quote.roverrobotics.com
## General Setup for local development

1. Install [node.js](https://nodejs.org/en/) (node package manager)  to install foobar.

2. To install project dependencies run:

```bash
npm install
```

3. Install web-pack globally to compile Javascript and CSS source files.

```bash
npm install -g webpack@^3.10.0
```

4.  Install Shopify's [themekit](https://shopify.github.io/themekit/) for any work on the shopify website.

* For documentation on quote.roverrobotics.com see ./quote-request-api



## Development Workflow Shopify

1. See **config.yml** in root
* change theme_id to development theme ID on roverrobotics.myshopify.com.
2. Run themekit's file watch function:
for a list of commands run "**theme**" in your terminal

Now any changes you make in the ./shop directory will be uploaded and written to the theme specified in theme_id in config.yml

3. See **package.json** for a list of development scripts under **scripts**. Depending on your operating system you will need to change the commands currently it's setup to use linux bash.

* For making Javascript and CSS changes see the ./source directory.
* You will need to compile both the Javascript and CSS using [webpack](https://webpack.js.org/)
* After making changes in any of the SASS or Javascript files run:

The main command for development:

```bash
npm run shopify:watch
```
This will compile your CSS and Javascript and export them into **application.js** & **application.css.liquid** when a change occurs in the application. This command runs 3 critical commands needed to compile the application.
```
"shopify:watch": "concurrently \"npm run watch:shop_css\" \"npm run shop:builder\" \"npm run themekit:watch\"",
```

1. watch:shop_css takes the sass code and injects theme variables with a python script
2. shop:builder uses webpack to build the css and Javascript
3. themekit:watch runs themekit and tells it to watch files for changes and uploads to remote theme

## Development Workflow Quote Request Server
1. Install [Elastic Beanstalk](https://docs.aws.amazon.com/elasticbeanstalk/latest/dg/eb-cli3-install.html)
2. Get an authentication key [instructions](https://aws.amazon.com/getting-started/tutorials/set-up-command-line-elastic-beanstalk/)
3. See common [eb commands](https://docs.aws.amazon.com/elasticbeanstalk/latest/dg/eb-cli3-getting-started.html)
