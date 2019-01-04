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
Wenn du entwickeln möchtest, solltest du deine Änderungen direkt in der Input CSS Datei (`tailwind/tailwindStyle.css`) oder in der Tailwind Config Datei (`tailwind/tailwindConfig.js`). 
Siehe dazu die offiziele [Tailwind CSS Dokumentation](https://tailwindcss.com/docs/installation). 

### CSS mit TailwindCSS neu kompilieren

Ausgehend von dem Template-Ordner.

```bash
./tailwindcss/node_modules/.bin/tailwind build ./tailwindcss/dev.css -c ./tailwindcss/tailwind.template.js -o ./bin/css/style.css
```


License
-------

GPL-3.0+