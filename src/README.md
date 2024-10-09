## Setup

Copy `config.example.php` into `config.php` and change configuration values
for your needs.

## File structure

- `admin/` - administration related logic
- `common/` - common JS scripts
- `data/` - CSV source files (empty in this git repo, but present on FTP storage)
- `images/` - images used by this plugin
- `includes/` - core plugin classes and functionality
- `public/` - class for public plugin functionality related logic
- `catv_genealogy_tools.php` - main plugin file
- `config.php` - plugin configuration file (see #Setup)
- `uninstall.php` - uninstallation script (run by wordpress on plugin delete)