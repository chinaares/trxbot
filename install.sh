if [ ! -f "./vendor/autoload.php" ]; then
  echo '安装依赖包'
  composer install
fi