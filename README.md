# Parsing sites

Client / Server.
The original idea was to parse telegram news so that, without being distracted by the browser, you can read them in the terminal,
at first there was one script file that was parsed every time a command was executed, but it was time consuming,
then I decided to write a client-server architecture. What would the Server Side work on the crown and separately without touching the terminal. The client side works with the terminal. The common point of contact between the parties is the storage.json file and for the terminal stats.txt

## Installation

1) Clone from repository
2) In root project folder - run ```composer install```
3) Edit file in root folder ```config.json```
4) Set server side ```crontab -e``` in you`r terminal
```bash
*/15 * * * * php {PATH TO CLONED PROJECT}/index.php --processing=server --output=cli
0 */2 * * * php {PATH TO CLONED PROJECT}/index.php --processing=cleaner --diff_cleaning_day=3
```
5) Edit you`r .bashrc file for show News in Cli
   Bash Code:
```bash
show__news() {
    if [[ -n $(tail -n 1 {PATH TO CLONED PROJECT}/stats.txt) ]]; then
        echo ''
        echo -e $(tail -n 1 {PATH TO CLONED PROJECT}/stats.txt)
    fi
}
```
And in line where  ```if [ "$color_prompt" = yes ]; then```
paste the code inside first row PS1='..$(show__news)\n..'
```bash
As Example:
PS1='${debian_chroot:+($debian_chroot)}$(show__news)\n\n\[\033[01;32m\]\u\[\033[00m\]:\[\033[01;34m\]\W\[\033[00m\]\$ '
```

And after executing crontab, you should see the amount of new news depending on your settings
```bash
News : [ kikobzor: 0 | rian_ru: 1 | forpost_sev: 0 ]
```

6) Now the config and classes are configured for the site https://tgstat.ru/en/channel/, respectively, the classes for parsing content are also configured for the home page markup of these pages.

###### If you need to parse another site, then in the folder both in the Client and in the Server "PagesClass" specify the folder that is specified in the config as a field - PageClass. The folder should contain two classes for the Server - Content.php, Page.php And for the Client only Content.php

## Usage Linux

As Notify Window if has
```bash
php {PATH TO CLONED PROJECT}/index.php --processing=client --output=notify
```

As Cli in Terminal
```bash
php {PATH TO CLONED PROJECT}/index.php --processing=client --output=cli
```
## Alias in .bashrc
```bash
alias last_news="php {PATH TO CLONED PROJECT}/index.php --processing=client --output=cli"
```

## Contributing

Pull requests are welcome. For major changes, please open an issue first
to discuss what you would like to change.

## License

[MIT](https://choosealicense.com/licenses/mit/)