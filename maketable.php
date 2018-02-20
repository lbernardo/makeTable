<?php
/**
 * Script maketable
 * User: lbernardo
 * Date: 19/02/18
 * Time: 21:03
 */



$command = isset($argv[1]) ? $argv[1] : null;

// Config
$GLOBALS['__mk_config__'] = include __DIR__."/config.php";

// List configs
$list_tables = [];
// List migrates
$list_migrates = [];

// Connect default
if (isset($GLOBALS['__mk_config__']['default'])) {
    $config = $GLOBALS['__mk_config__']['default'];
    $PDO = new \PDO("mysql:host={$config['host']};dbname={$config['dbname']}",$config['username'],$config['password']);

    // Verify migrations
    $PDO->query("CREATE TABLE IF NOT EXISTS __migrations (id INT AUTO_INCREMENT,name VARCHAR(255) NOT NULL, version INT NOT NULL,PRIMARY KEY(id))");

    // List versions
    $consult = $PDO->query("SELECT * FROM __migrations ORDER BY name");
    while ($fetch = $consult->fetch(PDO::FETCH_OBJ)) {
        array_push($list_tables,$fetch->name);
    }
} else {
    print "\033[01;31mError not found config default\033[0m\n";
    exit;
}

// List migrates
$openDir = opendir(__DIR__."/migrates");
while ($read = readdir($openDir)) {
    if ($read!="." && $read!=".." && strstr($read,".php")) {
        array_push($list_migrates , $read);
    }
}

switch ($command) {

    default:
    case 'help':
        print "php maketable *COMMAND*\n";
        print "COMMAND:\n";
        print "list - List versions\n";
        print "up - Up version database\n";
        print "down - Down version database\n";
        print "make *NAME* - Create new version\n";
    break;
    // UP version
    case 'up':
        up();
    break;
    case 'down':
        down();
    break;
    // Make
    case 'make':
        make();
    break;
    case 'list':
        _list();
    break;
}


/**
 * Up version
 */
function up()
{
    global $list_migrates,$list_tables,$PDO;

    $version = get_latest_version();
    $version++;

    foreach ($list_migrates as $migrate) {
        if (!in_array($migrate,$list_tables)) {
            $className = "v".str_replace(".php",null,$migrate);
            include __DIR__."/migrates/$migrate";
            $obj = new $className;
            $obj->up();

            // Create migration
            if ($PDO->query("INSERT INTO __migrations SET `name`='$migrate',`version`='$version'"))
                print "\033[01;32m$migrate create\033[0m\n";
            else
                print "\033[01;31m$migrate not create\033[0m\n";

        }
    }

}

/**
 * Down
 */
function down()
{
    global $PDO;

    $version = get_latest_version();

    // Consult list
    $consult = $PDO->query("SELECT name FROM __migrations WHERE `version`={$version}");
    while ($fetch = $consult->fetch(PDO::FETCH_OBJ)) {
        $migrate = $fetch->name;
        $className = "v".str_replace(".php",null,$migrate);
        include __DIR__."/migrates/$migrate";
        $obj = new $className;
        $obj->down();
        // Create migration
        if ($PDO->query("DELETE FROM __migrations WHERE `name`='$migrate'"))
            print "\033[01;32m$migrate down\033[0m\n";
        else
            print "\033[01;31m$migrate not down\033[0m\n";
    }

}

/**
 * List migrates
 */
function _list()
{
    global $list_migrates,$list_tables;

    foreach ($list_migrates as $migrate) {
        if (!in_array($migrate,$list_tables)) {
            print "\033[01;32m + {$migrate}\033[0m\n";
        }else{
            print "\033[01;33m * {$migrate}\033[0m\n";
        }
    }
}


/**
 * Generate make
 */
function make()
{
    global $argv;
    $name = ucwords(isset($argv[2]) ? $argv[2] : null);

    // Verify name
    if (empty($name)) {
        print "\033[01;31mNot found name\033[0m\n";
        exit;
    }

    $timestamp = strtotime("now");

    $source = "<?php\n\n"
        ."require_once __DIR__.\"/../vendor/autoload.php\";\n"
        ."use MakeTable\Table;\n\n"
        ."class v{$timestamp}_{$name} {\n"
        ."\tpublic function up()\n"
        ."\t{\n"
        ."\t}\n\n"
        ."\tpublic function down()\n"
        ."\t{\n"
        ."\t}\n\n"
        ."}";

    $file = __DIR__."/migrates/{$timestamp}_{$name}.php";

    $fopen = fopen($file,"w+");
    fwrite($fopen,$source);
    fclose($fopen);

    print "\033[01;32mCreate $file\033[0m\n";

}


/**
 * Get version
 */
function get_latest_version()
{
    global $PDO;
    $consult = $PDO->query("SELECT `version` FROM __migrations ORDER BY `version` DESC LIMIT 1");
    if ($consult->rowCount()) {
        $consult = $consult->fetch(PDO::FETCH_OBJ);
        $version = $consult->version;
    } else {
        $version = 0;
    }
    return $version;
}

