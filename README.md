# Supermetrics coding task

To run this coding task, first copy the `config.inc.php.sample` to `config.inc.php` and fill in the values.

Once the configuration file is set up, you can either run the application with PHP 7.3 CLI, or you can use
Docker container to do the same, with following command:
```docker build '.' -t 'supermetrics' && docker run --interactive --tty --rm 'supermetrics'```

The data display could be better, it could use HTML template or such to show a nice web page. 
Also the data processing could be more generic, maybe use some objects to sort the data instead of running the arrays.
