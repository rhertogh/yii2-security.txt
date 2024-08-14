# https://patorjk.com/software/taag/#p=display&h=1&f=Slant&t=Yii2%20security.txt
cat <<'MSG'
__  __ _  _  ___                                     _  __            __         __
\ \/ /(_)(_)|__ \    _____ ___   _____ __  __ _____ (_)/ /_ __  __   / /_ _  __ / /_
 \  // // / __/ /   / ___// _ \ / ___// / / // ___// // __// / / /  / __/| |/_// __/
 / // // / / __/   (__  )/  __// /__ / /_/ // /   / // /_ / /_/ /_ / /_ _>  < / /_
/_//_//_/ /____/  /____/ \___/ \___/ \__,_//_/   /_/ \__/ \__, /(_)\__//_/|_| \__/
                                                         /____/

MSG

echo "PHP version: ${PHP_VERSION}"
echo "DB driver: ${YII_DB_DRIVER}"

if ! shopt -oq posix; then
  if [ -f /usr/share/bash-completion/bash_completion ]; then
    . /usr/share/bash-completion/bash_completion
  elif [ -f /etc/bash_completion.d/yii ]; then
    . /etc/bash_completion.d/yii
  fi
fi
