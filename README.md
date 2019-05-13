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
```bash
theme watch
```
Now any changes you make in the ./shop directory will be uploaded and written to the theme specified in theme_id in config.yml

3. See **package.json** for a list of development scripts under **scripts**. Depending on your operating system you will need to change the commands

* For making Javascript and CSS changes see the ./source directory.
* You will need to compile both the Javascript and CSS using [webpack](https://webpack.js.org/)
* After making changes in any of the SASS or Javascript files run:


```bash
npm run shop:builder
```
This will compile your CSS and Javascript and export them into **application.js** & **application.css.liquid**
