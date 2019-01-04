QUIQQER Tailwind CSS
====================



Paketname:

    quiqqer/template-tailwindcss


Features
--------



Installation
------------

The package name is: quiqqer/template-tailwindcss


Contribute
----------


Support
-------

If you have found a bug or want to make improvements,
then you can write an e-mail to support@pcsg.de.

Developer
---------

### Install Tailwind CSS for developing

Standardmäßig beinhaltet das Template die von *Tailwind CSS* generierte Datei (`bin/css/style.css`). 
Wenn du entwickeln möchtest, solltest du zunächst *Tailwind CSS* installieren (z.B. über NPM), deine Änderungen direkt 
in der Input CSS Datei (`tailwind/tailwind.style.css`) oder in der Tailwind Config Datei (`tailwind/tailwind.config.js`) übernehmen 
und die Output Datei (`bin/css/style.css`) neu generieren. Details dazu findest du in der offiziellen [Tailwind CSS Dokumentation](https://tailwindcss.com/docs/installation).

#### 1. Install *Tailwind CSS* via npm

Install *Tailwind CSS* in tailwind folder in your template root directory:

```bash
cd tailwind

# Using npm
npm install tailwindcss --save-dev

# Using Yarn
yarn add tailwindcss --dev

```

#### 2. Use the config file and adapt it to your prefer

You can find the config file in tailwind folder (`tailwind/tailwind.config.js`) in the template root directory. 
You don't have to generate new config file unless you want to start with default tailwind config file. 
Further information about configuration you can find in the official [Tailwind CSS documentation - Configuration](https://tailwindcss.com/docs/configuration).


#### 3. Add or change components and utils

If you want to change or add new components and utils, edit the tailwind style file (`tailwind/tailwind.style.css`).
You can find more information in the official [Tailwind CSS documentation - Use Tailwind in your CSS](https://tailwindcss.com/docs/installation#3-use-tailwind-in-your-css).

#### 4. Generate new output css file

Every time you make changes to config file or input css file, you have to generate new out css file. 
Use the following command (starting from template root directory):

```bash
./tailwind/node_modules/.bin/tailwind build ./tailwind/tailwind.style.css -c ./tailwindcss/tailwind.config.js -o ./bin/css/style.css
``` 


License
-------

GPL-3.0+