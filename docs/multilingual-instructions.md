#Multilingual Support Installation Instructions
 
#####Requirements
To run the the console command cakephp-scripts needs to be installed
For editing of the .po files, the program poedit must be installed
A "Locale" folder must be placed inside jaunt/src/
Desired languages to be added must have their own folder inside jaunt/src/Locale
A locale folder to hold the first default.pot file

#####Instructions
The strings that need to be translated must be covered in gettext which should look like: <? __('example string') ?> because after running the i18n console command
it will only generate translation templates for strings covered by the gettext. Before running the command, first install cakephp-scripts by running 
sudo apt-get install cakephp-scripts. Next, navigate to the folder where you wish the translations to be made; these should be in the client-facing pages
under jaunt/src/Template/Users. Inside here, run "cake i18n extract" and let it create a locale folder in jaunt/src/Template/Users. Note that this locale folder
is different from the Locale folder that will hold all the translations. After the console command is run, there will be a default.pot file generated inside the locale folder.

Inside jaunt/src create a Locale folder that will hold all the translations. For each new translation, create a new folder such as "Spanish" or "French."
To create a new translation, a new .po file must be generated from the pot file. First install poedit via sudo apt-install poedit. Next open up the default.pot file in the
jaunt/src/Template/Users/locale folder with poedit. To edit a language, first you must edit the settings to validate the translation. Enter a language into the settings
and then depending on the language chosen, use http://docs.translatehouse.org/projects/localization-guide/en/latest/l10n/pluralforms.html?id=l10n/pluralforms to determine
the plural forms that are used. After inputting all the translations to the strings, save the new default.pot file in the corresponding language folder in jaunt/src/Locale. 

Occasionally language change does not work after
a refresh and instead you have to delete the files inside the jaunt/tmp/cache folder or close the browser completely and open it again to see the change in language. 

Languages are attached to each user via the "language" column in the users table. The default for each new user is set to "English." By changing this value to "Spanish" would let the user have the Spanish page displayed to them after logging in. This is done in the beforeFilter method of the appController where the locale is set to the language value of the user.
